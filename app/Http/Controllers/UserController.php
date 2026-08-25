<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Technician;
use App\Helpers\ActivityLogger;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LIST USER
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $users = User::with('technician')
            ->latest()
            ->get();

        return view(
            'users.index',
            compact('users')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH USER
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $technicians = Technician::where(
            'status',
            'aktif'
        )
        ->orderBy('name')
        ->get();

        return view(
            'users.create',
            compact('technicians')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN USER
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name'
                => 'required|string|max:255',

            'username'
                => 'required|string|max:255|unique:users,username',

            'email'
                => 'required|email|max:255|unique:users,email',

            'password'
                => 'required|string|min:6',

            'role'
                => 'required',

            'technician_id'
                => 'nullable|exists:technicians,id',

        ]);


        $user = User::create([

            'name'
                => $request->name,

            'username'
                => $request->username,

            'email'
                => $request->email,

            'password'
                => Hash::make(
                    $request->password
                ),

            'role'
                => $request->role,

            'technician_id'
                => $request->technician_id,

        ]);


        ActivityLogger::create(

            'user',

            $user->id,

            'Tambah User',

            'Membuat akun user '
            . $user->name
            . ' dengan role '
            . $user->role

        );


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User berhasil dibuat'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $technicians = Technician::where(
            'status',
            'aktif'
        )
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


    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $user = User::findOrFail($id);


        $request->validate([

            'name'
                => 'required|string|max:255',

            'username'
                => 'required|string|max:255|unique:users,username,' . $id,

            'email'
                => 'required|email|max:255|unique:users,email,' . $id,

            'role'
                => 'required',

            'technician_id'
                => 'nullable|exists:technicians,id',

        ]);


        $user->update([

            'name'
                => $request->name,

            'username'
                => $request->username,

            'email'
                => $request->email,

            'role'
                => $request->role,

            'technician_id'
                => $request->technician_id,

        ]);


        if ($request->filled('password')) {

            $user->update([

                'password'
                    => Hash::make(
                        $request->password
                    ),

            ]);
        }


        ActivityLogger::create(

            'user',

            $user->id,

            'Update User',

            'Mengubah data user '
            . $user->name

        );


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User berhasil diperbarui'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS USER
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $user = User::findOrFail($id);


        ActivityLogger::create(

            'user',

            $user->id,

            'Hapus User',

            'Menghapus user '
            . $user->name

        );


        $user->delete();


        return back()
            ->with(
                'success',
                'User berhasil dihapus'
            );
    }

}