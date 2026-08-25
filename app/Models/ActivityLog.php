<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ActivityLog extends Model
{

    protected $fillable = [

        'user_id',

        'document_id',

        'module',

        'reference_id',

        'activity',

        'description',

    ];



    public function user()
    {

        return $this->belongsTo(
            User::class
        );

    }



    public function document()
    {

        return $this->belongsTo(
            Document::class
        );

    }

}