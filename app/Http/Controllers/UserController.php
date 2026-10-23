<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user.
     */
    public function index()
    {
        $users = User::with('technician')
            ->latest()
            ->get();

        // Tetap dikirim agar tidak merusak Blade lama
        // atau bagian lain yang masih membutuhkan data teknisi.
        $technicians = Technician::where('status', 'aktif')
            ->orderBy('name')
            ->get();

        return view(
            'users.index',
            compact('users', 'technicians')
        );
    }


    /**
     * Menampilkan halaman tambah user.
     */
    public function create()
    {
        $technicians = Technician::where('status', 'aktif')
            ->orderBy('name')
            ->get();

        return view(
            'users.create',
            compact('technicians')
        );
    }

/**
 * Menyimpan user baru.
 */
public function store(Request $request)
{
    
    /*
    |--------------------------------------------------------------------------
    | Validasi Request
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'username' => [
            'required',
            'string',
            'max:255',
            'unique:users,username',
        ],

        'password' => [
            'required',
            'string',
            'min:6',
        ],

        'role' => [
            'required',
            'in:admin,teknisi',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | Email Internal
    |--------------------------------------------------------------------------
    |
    | Email tidak ditampilkan kepada user.
    | Namun kolom email tetap wajib dan unique
    | di database.
    |
    */

    $email = $this->generateInternalEmail(
        $validated['username']
    );


    /*
    |--------------------------------------------------------------------------
    | Buat User
    |--------------------------------------------------------------------------
    */

    $user = User::create([
        'name' => $validated['name'],
        'username' => $validated['username'],
        'email' => $email,
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'technician_id' => null,
    ]);


    /*
    |--------------------------------------------------------------------------
    | Jika Role Teknisi
    |--------------------------------------------------------------------------
    |
    | Otomatis:
    | 1. Membuat data Technician
    | 2. Membuat QR Token
    | 3. Membuat file QR SVG
    | 4. Menghubungkan Technician ke User
    |
    */

    if ($validated['role'] === 'teknisi') {

        $technician = $this->createTechnician(
            $validated['name']
        );

        $user->update([
            'technician_id' => $technician->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Refresh User
        |--------------------------------------------------------------------------
        |
        | Memastikan data technician_id terbaru
        | sudah masuk ke object User.
        |
        */

        $user->refresh();
    }


    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    */

    ActivityLogger::create(
        'user',
        $user->id,
        'Tambah User',
        'Membuat akun user ' .
        $user->name .
        ' dengan role ' .
        $user->role
    );


    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('users.index')
        ->with(
            'success',
            'User berhasil dibuat.'
        );
}


    /**
     * Menampilkan halaman edit user.
     */
    public function edit($id)
    {
        
        $user = User::with('technician')
            ->findOrFail($id);

        $technicians = Technician::where('status', 'aktif')
            ->orderBy('name')
            ->get();

        return view(
            'users.edit',
            compact(
                'user',
                'technicians'
            )
        );
    }


    /**
     * Memperbarui data user.
     */
    public function update(
        Request $request,
        $id
    ) {
        $user = User::with('technician')
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $id,
            ],

            'role' => [
                'required',
                'in:admin,teknisi',
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
            ],
        ]);


        $oldRole = $user->role;
        $newRole = $request->role;


        /*
        |--------------------------------------------------------------------------
        | Update Data Dasar User
        |--------------------------------------------------------------------------
        */

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $newRole,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        |
        | Password hanya diubah jika field diisi.
        |
        */

        if ($request->filled('password')) {

            $user->update([
                'password' => Hash::make(
                    $request->password
                ),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN -> TEKNISI
        |--------------------------------------------------------------------------
        |
        | Jika sebelumnya Admin kemudian diubah
        | menjadi Teknisi, sistem membuat data teknisi
        | beserta QR TTD.
        |
        */

        if (
            $oldRole !== 'teknisi' &&
            $newRole === 'teknisi'
        ) {

            if (!$user->technician_id) {

                $technician = $this->createTechnician(
                    $request->name
                );

                $user->update([
                    'technician_id' => $technician->id,
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Sinkronisasi Nama Teknisi
        |--------------------------------------------------------------------------
        |
        | Jika user sudah menjadi Teknisi,
        | nama pada tabel technicians ikut diperbarui.
        |
        */

        if (
            $newRole === 'teknisi' &&
            $user->technician
        ) {

            $user->technician->update([
                'name' => $request->name,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | TEKNISI -> ADMIN
        |--------------------------------------------------------------------------
        |
        | Relasi technician dilepas dari user.
        |
        | Data teknisi TIDAK dihapus.
        |
        */

        if (
            $oldRole === 'teknisi' &&
            $newRole === 'admin'
        ) {

            $user->update([
                'technician_id' => null,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::create(
            'user',
            $user->id,
            'Update User',
            'Mengubah data user ' .
            $user->name
        );


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User berhasil diperbarui.'
            );
    }


    /**
     * Mengubah status teknisi.
     *
     * Status yang diperbolehkan:
     * - aktif
     * - mutasi
     */
    public function updateTechnicianStatus(
        Request $request,
        $id
    ) {
        $user = User::with('technician')
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Pastikan User adalah Teknisi
        |--------------------------------------------------------------------------
        */

        if (
            $user->role !== 'teknisi' ||
            !$user->technician
        ) {

            return back()->with(
                'error',
                'User ini tidak memiliki data teknisi.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Validasi Status
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'status' => [
                'required',
                'in:aktif,mutasi',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Status
        |--------------------------------------------------------------------------
        */

        $user->technician->update([
            'status' => $request->status,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        $statusText = $request->status === 'aktif'
            ? 'mengaktifkan kembali'
            : 'memutasi';


        ActivityLogger::create(
            'user',
            $user->id,
            'Update Status Teknisi',
            'User ' .
            $user->name .
            ' berhasil ' .
            $statusText
        );


        return back()->with(
            'success',
            'Status teknisi berhasil diperbarui.'
        );
    }


    /**
     * Menghapus user.
     */
    public function destroy($id)
    {
        $user = User::with('technician')
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        ActivityLogger::create(
            'user',
            $user->id,
            'Hapus User',
            'Menghapus user ' .
            $user->name
        );


        /*
        |--------------------------------------------------------------------------
        | Hapus Data Teknisi
        |--------------------------------------------------------------------------
        |
        | Jika user memiliki data teknisi,
        | data teknisi ikut dihapus.
        |
        */

        if ($user->technician) {

            $user->technician->delete();
        }


        /*
        |--------------------------------------------------------------------------
        | Hapus User
        |--------------------------------------------------------------------------
        */

        $user->delete();


        return back()->with(
            'success',
            'User berhasil dihapus.'
        );
    }


    /**
     * Membuat data teknisi baru beserta QR TTD.
     */
    private function createTechnician(
        string $name
    ): Technician {

        /*
        |--------------------------------------------------------------------------
        | Generate QR Token
        |--------------------------------------------------------------------------
        */

        $qrToken = Str::uuid()->toString();


        /*
        |--------------------------------------------------------------------------
        | Buat Data Teknisi
        |--------------------------------------------------------------------------
        */

        $technician = Technician::create([
            'name' => $name,
            'qr_token' => $qrToken,
            'status' => 'aktif',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Folder QR
        |--------------------------------------------------------------------------
        */

        $qrDirectory = public_path(
            'qrcodes/technicians'
        );


        if (!file_exists($qrDirectory)) {

            mkdir(
                $qrDirectory,
                0755,
                true
            );
        }


        /*
        |--------------------------------------------------------------------------
        | File QR
        |--------------------------------------------------------------------------
        */

        $qrFile =
            $qrDirectory .
            '/' .
            $qrToken .
            '.svg';


        /*
        |--------------------------------------------------------------------------
        | Generate QR TTD
        |--------------------------------------------------------------------------
        */

        QrCode::format('svg')
            ->size(300)
            ->generate(
                'TECHNICIAN-TTD:' . $qrToken,
                $qrFile
            );


        return $technician;
    }


    /**
     * Membuat email internal yang unik.
     *
     * Email tidak ditampilkan kepada user,
     * tetapi tetap diperlukan karena kolom email
     * pada database masih wajib dan unique.
     */
    private function generateInternalEmail(
        string $username
    ): string {

        $username = strtolower(
            trim($username)
        );


        $email = $username . '@airnav.local';

        $counter = 1;


        while (
            User::where(
                'email',
                $email
            )->exists()
        ) {

            $email =
                $username .
                $counter .
                '@airnav.local';

            $counter++;
        }


        return $email;
    }
}