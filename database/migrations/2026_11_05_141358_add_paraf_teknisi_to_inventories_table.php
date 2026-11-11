<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            if (!Schema::hasColumn('inventories', 'paraf_teknisi')) {
                $table->longText('paraf_teknisi')->nullable()->after('teknisi_bertugas');
            }
            if (!Schema::hasColumn('inventories', 'catatan_teknisi')) {
                $table->text('catatan_teknisi')->nullable()->after('paraf_teknisi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            if (Schema::hasColumn('inventories', 'paraf_teknisi')) {
                $table->dropColumn(['paraf_teknisi', 'catatan_teknisi']);
            }
        });
    }
};