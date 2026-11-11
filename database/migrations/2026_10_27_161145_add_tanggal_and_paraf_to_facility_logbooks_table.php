<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facility_logbooks', function (Blueprint $table) {
            $table->date('tanggal_kejadian')->nullable();
            $table->date('tanggal_dibuat')->nullable();
            $table->dateTime('tanggal_waktu_selesai')->nullable();
            $table->text('paraf_teknisi')->nullable();
            $table->text('paraf_mantek')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('facility_logbooks', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kejadian', 'tanggal_dibuat', 'tanggal_waktu_selesai', 'paraf_teknisi', 'paraf_mantek']);
        });
    }
};