<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | REFERENCE
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('notifications', 'reference')) {

            Schema::table('notifications', function (Blueprint $table) {

                $table->string('reference')
                    ->nullable()
                    ->after('type');

            });

        }


        /*
        |--------------------------------------------------------------------------
        | URL
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('notifications', 'url')) {

            Schema::table('notifications', function (Blueprint $table) {

                $table->text('url')
                    ->nullable()
                    ->after('reference');

            });

        }
    }


    public function down(): void
    {
        if (Schema::hasColumn('notifications', 'url')) {

            Schema::table('notifications', function (Blueprint $table) {

                $table->dropColumn('url');

            });

        }


        if (Schema::hasColumn('notifications', 'reference')) {

            Schema::table('notifications', function (Blueprint $table) {

                $table->dropColumn('reference');

            });

        }
    }
};