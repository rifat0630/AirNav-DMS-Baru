<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            if (!Schema::hasColumn('inventories', 'kondisi')) {
                $table->string('kondisi')->default('baik')->after('name'); // atau sesuaikan posisi kolom
            }
            if (!Schema::hasColumn('inventories', 'lokasi')) {
                $table->string('lokasi')->nullable()->after('kondisi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['kondisi', 'lokasi']);
        });
    }
};