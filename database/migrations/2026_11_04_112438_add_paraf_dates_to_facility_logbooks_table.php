<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facility_logbooks', function (Blueprint $table) {
            if (!Schema::hasColumn('facility_logbooks', 'paraf_teknisi_at')) {
                $table->timestamp('paraf_teknisi_at')->nullable();
            }
            if (!Schema::hasColumn('facility_logbooks', 'paraf_mantek_at')) {
                $table->timestamp('paraf_mantek_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('facility_logbooks', function (Blueprint $table) {
            $table->dropColumn(['paraf_teknisi_at', 'paraf_mantek_at']);
        });
    }
};