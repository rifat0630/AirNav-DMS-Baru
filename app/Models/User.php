<?php

namespace App\Models;


use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


use App\Models\Document;
use App\Models\ActivityLog;
use App\Models\Technician;



class User extends Authenticatable
{

    use HasFactory, Notifiable;



    protected $fillable = [

        'name',

        'username',

        'email',

        'password',

        'role',

        'technician_id',

    ];




    protected $hidden = [

        'password',

        'remember_token',

    ];





    protected function casts(): array
    {

        return [

            'email_verified_at' => 'datetime',

            'password' => 'hashed',

        ];

    }







    /*
    |--------------------------------------------------------------------------
    | Relasi Dokumen
    |--------------------------------------------------------------------------
    */

    public function documents()
    {

        return $this->hasMany(
            Document::class
        );

    }







    /*
    |--------------------------------------------------------------------------
    | Relasi Activity Log
    |--------------------------------------------------------------------------
    */

    public function activityLogs()
    {

        return $this->hasMany(
            ActivityLog::class
        );

    }








    /*
    |--------------------------------------------------------------------------
    | Role
    |--------------------------------------------------------------------------
    */

    public function isAdmin()
    {

        return $this->role === 'admin';

    }





    public function isTeknisi()
    {

        return $this->role === 'teknisi';

    }





    public function isPegawai()
    {

        return $this->role === 'pegawai';

    }








    /*
    |--------------------------------------------------------------------------
    | Relasi User ke Teknisi
    |--------------------------------------------------------------------------
    */

    public function technician()
    {

        return $this->belongsTo(
            Technician::class,
            'technician_id'
        );

    }



}