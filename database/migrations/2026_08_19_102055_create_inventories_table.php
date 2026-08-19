<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {

            $table->id();

            // Nama barang
            $table->string('name');

            // Kode barang, boleh kosong
            $table->string('code')->nullable()->unique();

            // Keterangan barang
            $table->text('description')->nullable();

            // Jumlah stok
            $table->decimal('stock', 12, 2)->default(0);

            // Satuan: pcs, meter, roll, box, dll
            $table->string('unit')->default('pcs');

            // Foto barang
            $table->string('photo')->nullable();

            // Status stok
            $table->enum('status', [
                'tersedia',
                'stok_menipis',
                'habis'
            ])->default('habis');

            // User yang memasukkan data barang
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};