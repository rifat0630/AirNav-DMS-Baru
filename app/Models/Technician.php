<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\FacilityLogbook;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'qr_token',
        'status',
    ];


    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->hasOne(
            User::class,
            'technician_id'
        );
    }


    /**
     * Relasi ke Logbook
     */
    public function logbooks()
    {
        return $this->hasMany(
            FacilityLogbook::class,
            'technician_id'
        );
    }
}