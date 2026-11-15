<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('fasilitas_alat');
            $table->enum('shift', ['Pagi', 'Siang', 'Malam']);
            $table->text('uraian_pekerjaan');

            // 3 Tanggal Manual
            $table->date('tanggal_terjadi');
            $table->date('tanggal_dibuat');
            $table->dateTime('waktu_selesai')->nullable();

            // Live Paraf Teknisi (Base64 PNG dari Canvas)
            $table->longText('paraf_teknisi')->nullable();
            $table->timestamp('paraf_teknisi_at')->nullable();

            // Catatan & Live Paraf Manager Teknik (Mantek)
            $table->foreignId('mantek_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_mantek')->nullable();
            $table->longText('paraf_mantek')->nullable();
            $table->timestamp('paraf_mantek_at')->nullable();

            $table->enum('status', ['Menunggu Paraf Mantek', 'Terverifikasi'])->default('Menunggu Paraf Mantek');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};