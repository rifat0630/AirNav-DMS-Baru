<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Document;
use App\Models\User;

use App\Services\NotificationService;



class DashboardController extends Controller
{


    public function index()
    {


        /*
        |--------------------------------------------------------------------------
        | GENERATE NOTIFICATION
        |--------------------------------------------------------------------------
        */

        app(NotificationService::class)
            ->generate();




        /*
        |--------------------------------------------------------------------------
        | DASHBOARD DATA
        |--------------------------------------------------------------------------
        */


        $totalDocuments = Document::count();


        $totalCategories = Category::count();


        $totalUsers = User::count();


        $activeDocuments = Document::where(
            'status',
            'Aktif'
        )->count();





        return view('dashboard', [

            'totalDocuments' => $totalDocuments,

            'totalCategories' => $totalCategories,

            'totalUsers' => $totalUsers,

            'activeDocuments' => $activeDocuments,

        ]);


    }


}