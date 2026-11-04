<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facility_logbooks', function (Blueprint $table) {
            if (!Schema::hasColumn('facility_logbooks', 'signature_status')) {
                $table->string('signature_status')->nullable()->default('belum');
            }
            if (!Schema::hasColumn('facility_logbooks', 'paraf_teknisi')) {
                $table->longText('paraf_teknisi')->nullable();
            }
            if (!Schema::hasColumn('facility_logbooks', 'paraf_teknisi_name')) {
                $table->string('paraf_teknisi_name')->nullable();
            }
            if (!Schema::hasColumn('facility_logbooks', 'paraf_mantek')) {
                $table->longText('paraf_mantek')->nullable();
            }
            if (!Schema::hasColumn('facility_logbooks', 'paraf_mantek_name')) {
                $table->string('paraf_mantek_name')->nullable();
            }
            if (!Schema::hasColumn('facility_logbooks', 'status')) {
                $table->string('status')->default('Belum Diverifikasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facility_logbooks', function (Blueprint $table) {
            $table->dropColumn([
                'signature_status', 
                'paraf_teknisi', 
                'paraf_teknisi_name', 
                'paraf_mantek', 
                'paraf_mantek_name', 
                'status'
            ]);
        });
    }
};