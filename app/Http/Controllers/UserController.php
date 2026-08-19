<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{


    public function index()
    {

        $users = User::with('technician')
            ->get();


        return view(
            'users.index',
            compact('users')
        );

    }





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





    public function store(Request $request)
    {


        $request->validate([


            'name'=>'required',


            'username'=>'required',


            'email'=>'required|email',


            'password'=>'required',


            'role'=>'required',


            'technician_id'=>'nullable|exists:technicians,id'


        ]);





        User::create([


            'name'=>$request->name,


            'username'=>$request->username,


            'email'=>$request->email,


            'password'=>Hash::make(
                $request->password
            ),


            'role'=>$request->role,


            'technician_id'=>$request->technician_id


        ]);





        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'User berhasil dibuat'
            );


    }



}