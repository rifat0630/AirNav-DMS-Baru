<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class FacilityLogbook extends Model
{


    use HasFactory;




    protected $fillable = [


        'technician_id',


        'technicians',


        'action_notes',


        'log_datetime',


        'user_id',


        'signature_type',


        'signature_file',


        'qr_token',


        'signed_at',


    ];








    /*
    |--------------------------------------------------------------------------
    | Relasi Teknisi
    |--------------------------------------------------------------------------
    */


    public function technician()
    {


        return $this->belongsTo(
            Technician::class,
            'technician_id'
        );


    }








    /*
    |--------------------------------------------------------------------------
    | Relasi User Pembuat
    |--------------------------------------------------------------------------
    */


    public function user()
    {


        return $this->belongsTo(
            User::class,
            'user_id'
        );


    }





}