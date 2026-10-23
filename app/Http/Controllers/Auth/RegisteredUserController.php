<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }


    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
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
                'unique:users,username',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | BUAT USER + TECHNICIAN
        |--------------------------------------------------------------------------
        |
        | Setiap akun yang melakukan registrasi otomatis
        | mendapatkan role TEKNISI.
        |
        */

        $user = DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | Buat User
            |--------------------------------------------------------------------------
            */

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,

                /*
                |--------------------------------------------------------------------------
                | Role otomatis Teknisi
                |--------------------------------------------------------------------------
                */

                'role' => 'teknisi',

                'technician_id' => null,

                /*
                |--------------------------------------------------------------------------
                | User model sudah menggunakan cast hashed
                |--------------------------------------------------------------------------
                */

                'password' => $request->password,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Buat Technician
            |--------------------------------------------------------------------------
            */

            $qrToken = Str::uuid()->toString();

            $technician = Technician::create([
                'name' => $request->name,
                'qr_token' => $qrToken,
                'status' => 'aktif',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Hubungkan User dengan Technician
            |--------------------------------------------------------------------------
            */

            $user->update([
                'technician_id' => $technician->id,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Buat Folder QR
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
            | Buat File QR TTD
            |--------------------------------------------------------------------------
            */

            $qrFile = $qrDirectory
                . '/'
                . $qrToken
                . '.svg';


            QrCode::format('svg')
                ->size(300)
                ->generate(
                    'TECHNICIAN-TTD:' . $qrToken,
                    $qrFile
                );


            /*
            |--------------------------------------------------------------------------
            | Refresh User
            |--------------------------------------------------------------------------
            */

            $user->refresh();


            return $user;
        });


        /*
        |--------------------------------------------------------------------------
        | REGISTER EVENT
        |--------------------------------------------------------------------------
        */

        event(new Registered($user));


        /*
        |--------------------------------------------------------------------------
        | AUTO LOGIN
        |--------------------------------------------------------------------------
        */

        Auth::login($user);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('dashboard');
    }
}