<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    protected $fillable = [
        'name',
        'code',
        'stock',
        'unit',
        'serial_numbers',
        'photos',
        'condition',
        'user_id',
    ];

    protected $casts = [
        'serial_numbers' => 'array',
        'photos' => 'array',
        'stock' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION USER
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SERIAL NUMBER
    |--------------------------------------------------------------------------
    */

    public function getSerialNumbersListAttribute()
    {
        return $this->serial_numbers ?? [];
    }


    /*
    |--------------------------------------------------------------------------
    | FOTO
    |--------------------------------------------------------------------------
    */

    public function getPhotosListAttribute()
    {
        return $this->photos ?? [];
    }


    /*
    |--------------------------------------------------------------------------
    | KONDISI
    |--------------------------------------------------------------------------
    */

    public function getConditionLabelAttribute()
    {
        return match (strtolower($this->condition ?? 'normal')) {

            'rusak' => 'Rusak',

            default => 'Normal',

        };
    }
}