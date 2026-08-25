<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;


class ActivityLogger
{


    public static function create(
        $module,
        $referenceId,
        $activity,
        $description
    )
    {

        ActivityLog::create([


            'user_id'
                => Auth::id(),


            'module'
                => $module,


            'reference_id'
                => $referenceId,


            'activity'
                => $activity,


            'description'
                => $description,


        ]);

    }


}