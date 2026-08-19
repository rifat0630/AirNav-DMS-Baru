<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    protected $fillable = [

        'inventory_id',

        'user_id',

        'type',

        'quantity',

        'stock_before',

        'stock_after',

        'note',

    ];


    /*
    |--------------------------------------------------------------------------
    | Relasi ke Barang
    |--------------------------------------------------------------------------
    */

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(
            Inventory::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Relasi ke User
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
}