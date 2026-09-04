<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {

            if (!Schema::hasColumn('inventories', 'serial_numbers')) {
                $table->json('serial_numbers')
                    ->nullable()
                    ->after('code');
            }

            if (!Schema::hasColumn('inventories', 'photos')) {
                $table->json('photos')
                    ->nullable()
                    ->after('serial_numbers');
            }

            if (!Schema::hasColumn('inventories', 'condition')) {
                $table->string('condition')
                    ->default('normal')
                    ->after('photos');
            }

        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {

            if (Schema::hasColumn('inventories', 'serial_numbers')) {
                $table->dropColumn('serial_numbers');
            }

            if (Schema::hasColumn('inventories', 'photos')) {
                $table->dropColumn('photos');
            }

            if (Schema::hasColumn('inventories', 'condition')) {
                $table->dropColumn('condition');
            }

        });
    }
};