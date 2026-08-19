<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FacilityLogbook extends Model
{


protected $fillable = [

    'log_datetime',

    'technicians',

    'technician_id',

    'action_notes',

    'qr_token',

    'signature_status',

    'signed_at',

    'signed_by',

    'user_id'

];





public function technician()
{

    return $this->belongsTo(
        Technician::class
    );

}




public function user()
{

    return $this->belongsTo(
        User::class
    );

}



}