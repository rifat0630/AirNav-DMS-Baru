<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;


class NotificationController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | SEMUA NOTIFIKASI
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $notifications = Notification::latest()
            ->get();


        return view(
            'notifications.index',
            compact('notifications')
        );

    }



    /*
    |--------------------------------------------------------------------------
    | BACA SATU NOTIFIKASI
    |--------------------------------------------------------------------------
    */

    public function read($id)
    {

        $notification =
            Notification::findOrFail($id);


        /*
        | Tandai sudah dibaca
        */

        $notification->update([
            'is_read' => 1
        ]);


        /*
        | Kalau punya URL,
        | langsung menuju halaman tujuan
        */

        if (
            !empty($notification->url)
        ) {

            return redirect()->to(
                $notification->url
            );

        }


        /*
        | Kalau tidak punya URL,
        | kembali ke halaman sebelumnya
        */

        return back();

    }



    /*
    |--------------------------------------------------------------------------
    | BACA SEMUA
    |--------------------------------------------------------------------------
    */

    public function readAll()
    {

        Notification::where(
            'is_read',
            0
        )->update([
            'is_read' => 1
        ]);


        return back();

    }



    /*
    |--------------------------------------------------------------------------
    | JUMLAH BELUM DIBACA
    |--------------------------------------------------------------------------
    */

    public function unreadCount()
    {

        $count = Notification::where(
            'is_read',
            0
        )->count();


        return response()->json([
            'count' => $count
        ]);

    }

}