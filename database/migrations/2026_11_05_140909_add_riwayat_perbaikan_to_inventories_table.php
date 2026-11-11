<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            if (!Schema::hasColumn('inventories', 'riwayat_perbaikan')) {
                $table->longText('riwayat_perbaikan')->nullable()->after('paraf_mantek');
            }
            // Pastikan paraf_mantek bertipe longText agar muat menampung base64 gambar paraf
            if (Schema::hasColumn('inventories', 'paraf_mantek')) {
                $table->longText('paraf_mantek')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            if (Schema::hasColumn('inventories', 'riwayat_perbaikan')) {
                $table->dropColumn('riwayat_perbaikan');
            }
        });
    }
};