<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    protected $fillable = [

        'name',

        'code',

        'description',

        'stock',

        'unit',

        'photo',

        'status',

        'user_id',

    ];


    protected $casts = [

        'stock' => 'decimal:2',

    ];


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


    /*
    |--------------------------------------------------------------------------
    | Relasi ke Riwayat Stok
    |--------------------------------------------------------------------------
    */

    public function movements(): HasMany
    {
        return $this->hasMany(
            InventoryMovement::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Stok
    |--------------------------------------------------------------------------
    */

    public function updateStockStatus(): void
    {
        if ($this->stock <= 0) {

            $this->status = 'habis';

        } elseif ($this->stock <= 5) {

            $this->status = 'stok_menipis';

        } else {

            $this->status = 'tersedia';

        }

        $this->save();
    }
}