<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;


class ActivityLogController extends Controller
{

    public function index(Request $request)
    {

        $query = ActivityLog::with('user')
            ->latest();



        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if($request->filled('search')){


            $search = $request->search;



            $query->where(function($q) use ($search){


                $q->where(
                    'activity',
                    'like',
                    "%{$search}%"
                )


                ->orWhere(
                    'description',
                    'like',
                    "%{$search}%"
                )


                ->orWhere(
                    'module',
                    'like',
                    "%{$search}%"
                );


            });


        }




        /*
        |--------------------------------------------------------------------------
        | Filter Modul
        |--------------------------------------------------------------------------
        */

        if($request->filled('module')){

            $query->where(
                'module',
                $request->module
            );

        }

        $logs = $query->paginate(50)->withQueryString();
        return view(
            'activity_logs.index',
            compact('logs')
        );

    }


}