<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facility_logbooks', function (Blueprint $table) {
            if (!Schema::hasColumn('facility_logbooks', 'paraf_teknisi')) {
                $table->longText('paraf_teknisi')->nullable();
                $table->string('paraf_teknisi_name')->nullable();
                $table->longText('paraf_mantek')->nullable();
                $table->string('paraf_mantek_name')->nullable();
                $table->string('status')->default('Belum Diverifikasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facility_logbooks', function (Blueprint $table) {
            $table->dropColumn(['paraf_teknisi', 'paraf_teknisi_name', 'paraf_mantek', 'paraf_mantek_name', 'status']);
        });
    }
};