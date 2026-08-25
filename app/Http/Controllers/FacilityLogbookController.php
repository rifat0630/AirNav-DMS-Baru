<?php

namespace App\Http\Controllers;


use App\Models\FacilityLogbook;
use App\Models\Technician;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use SimpleSoftwareIO\QrCode\Facades\QrCode;



class FacilityLogbookController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {


        $query = FacilityLogbook::with([
            'technician',
            'user'
        ]);



        if($request->search){


            $search = $request->search;


            $query->where(function($q) use($search){


                $q->where(
                    'action_notes',
                    'like',
                    "%$search%"
                )

                ->orWhere(
                    'technicians',
                    'like',
                    "%$search%"
                );


            });



        }



        $logbooks = $query
            ->latest()
            ->get();



        return view(
            'logbook.index',
            compact('logbooks')
        );

    }








    /*
    |--------------------------------------------------------------------------
    | CREATE
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
            'logbook.create',
            compact('technicians')
        );


    }









    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {


        $request->validate([


            'technician_id'
                =>
                'required|exists:technicians,id',



            'action_notes'
                =>
                'required'


        ]);





        $technician = Technician::findOrFail(
            $request->technician_id
        );






        /*
        Buat token QR
        */

        $token = Str::uuid();







        /*
        Simpan QR SVG
        */

        $fileName = $token.'.svg';



        $path = public_path(
            'qrcodes/'.$fileName
        );



        if(!file_exists(
            public_path('qrcodes')
        )){


            mkdir(
                public_path('qrcodes'),
                0777,
                true
            );


        }



        QrCode::format('svg')
            ->size(300)
            ->generate(

                route(
                    'logbook.sign',
                    $token
                ),

                $path

            );








        FacilityLogbook::create([



            'technician_id'
                =>
                $technician->id,



            'technicians'
                =>
                $technician->name,



            'log_datetime'
                =>
                now(),



            'action_notes'
                =>
                $request->action_notes,



            'user_id'
                =>
                Auth::id(),



            'signature_type'
                =>
                'qr',



            'signature_file'
                =>
                $fileName,



            'qr_token'
                =>
                $token,



            'signature_status'
                =>
                'belum',



        ]);







        return redirect()

            ->route('logbook.index')

            ->with(
                'success',
                'Logbook berhasil dibuat'
            );



    }









    /*
    |--------------------------------------------------------------------------
    | HALAMAN SCAN QR
    |--------------------------------------------------------------------------
    */

    public function sign($token)
    {


        $logbook =
            FacilityLogbook::where(
                'qr_token',
                $token
            )
            ->firstOrFail();





        return view(
            'logbook.sign',
            compact('logbook')
        );


    }









    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI TEKNISI
    |--------------------------------------------------------------------------
    */

    public function signConfirm($token)
    {


        $logbook =
            FacilityLogbook::where(
                'qr_token',
                $token
            )
            ->firstOrFail();






        $user = Auth::user();





        /*
        wajib teknisi
        */

        if(
            $user->role != 'teknisi'
        ){

            return back()
            ->with(
                'error',
                'Akun bukan teknisi'
            );


        }







        $logbook->update([



            'signature_status'
                =>
                'terverifikasi',



            'signed_at'
                =>
                now(),



            'signed_by'
                =>
                $user->id



        ]);








        return redirect()

            ->route(
                'logbook.sign',
                $token
            )

            ->with(
                'success',
                'Logbook berhasil diverifikasi'
            );


    }









    /*
    |--------------------------------------------------------------------------
    | SCAN DARI ADMIN
    |--------------------------------------------------------------------------
    */

    public function scan($id)
    {


        $logbook =
            FacilityLogbook::findOrFail($id);



        return redirect()

            ->route(
                'logbook.sign',
                $logbook->qr_token
            );


    }









    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {


        $logbook =
            FacilityLogbook::findOrFail($id);



        $technicians =
            Technician::where(
                'status',
                'aktif'
            )
            ->get();




        return view(
            'logbook.edit',
            compact(
                'logbook',
                'technicians'
            )
        );


    }









    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    )
    {



        $logbook =
            FacilityLogbook::findOrFail($id);



        $request->validate([


            'technician_id'
                =>
                'required',


            'action_notes'
                =>
                'required'


        ]);





        $technician =
            Technician::find(
                $request->technician_id
            );





        $logbook->update([


            'technician_id'
                =>
                $technician->id,



            'technicians'
                =>
                $technician->name,



            'action_notes'
                =>
                $request->action_notes



        ]);





        return redirect()

            ->route('logbook.index')

            ->with(
                'success',
                'Logbook diperbarui'
            );



    }









    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {



        $logbook =
            FacilityLogbook::findOrFail($id);





        if($logbook->signature_file){


            $file =
            public_path(
                'qrcodes/'.$logbook->signature_file
            );


            if(file_exists($file)){


                unlink($file);


            }


        }






        $logbook->delete();





        return back()

            ->with(
                'success',
                'Logbook dihapus'
            );


    }



}