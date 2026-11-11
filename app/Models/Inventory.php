<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    // Membuka proteksi mass assignment agar semua input tersimpan tanpa dibuang Eloquent
    protected $guarded = [];
}