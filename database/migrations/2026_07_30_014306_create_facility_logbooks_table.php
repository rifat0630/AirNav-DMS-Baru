<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facility_logbooks', function (Blueprint $table) {
            $table->id();
            $table->dateTime('log_datetime'); // Tanggal & Jam Kegiatan
            $table->text('action_notes');     // Catatan / Tindakan
            $table->string('technicians');    // Nama Teknisi
            
            // Relasi ke tabel users
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_logbooks');
    }
};