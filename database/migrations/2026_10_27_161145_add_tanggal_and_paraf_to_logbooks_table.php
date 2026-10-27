<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->date('tanggal_terjadi')->nullable();
            $table->date('tanggal_dibuat')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->string('paraf_teknisi')->nullable();
            $table->string('paraf_mantek')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_terjadi',
                'tanggal_dibuat',
                'waktu_selesai',
                'paraf_teknisi',
                'paraf_mantek'
            ]);
        });
    }
};