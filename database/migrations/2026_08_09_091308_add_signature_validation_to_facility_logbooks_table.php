<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{


    public function up()
    {

        Schema::table('facility_logbooks', function (Blueprint $table) {


            if (!Schema::hasColumn('facility_logbooks','signed_at')) {

                $table->timestamp('signed_at')
                    ->nullable()
                    ->after('signature_status');

            }


            if (!Schema::hasColumn('facility_logbooks','signed_by')) {

                $table->foreignId('signed_by')
                    ->nullable()
                    ->after('signed_at');

            }


        });

    }





    public function down()
    {

        Schema::table('facility_logbooks', function (Blueprint $table) {


            if (Schema::hasColumn('facility_logbooks','signed_at')) {

                $table->dropColumn('signed_at');

            }


            if (Schema::hasColumn('facility_logbooks','signed_by')) {

                $table->dropColumn('signed_by');

            }


        });

    }


};