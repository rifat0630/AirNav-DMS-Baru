<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Barang
            |--------------------------------------------------------------------------
            */

            $table->foreignId('inventory_id')
                ->constrained('inventories')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | User yang melakukan transaksi
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Jenis transaksi
            |--------------------------------------------------------------------------
            */

            $table->enum('type', [
                'masuk',
                'keluar'
            ]);


            /*
            |--------------------------------------------------------------------------
            | Jumlah barang
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('quantity');


            /*
            |--------------------------------------------------------------------------
            | Stok sebelum dan sesudah transaksi
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('stock_before');

            $table->unsignedInteger('stock_after');


            /*
            |--------------------------------------------------------------------------
            | Keterangan
            |--------------------------------------------------------------------------
            */

            $table->text('note')->nullable();


            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};