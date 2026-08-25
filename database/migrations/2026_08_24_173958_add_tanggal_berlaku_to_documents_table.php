<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->date('tanggal_berlaku')
                ->nullable()
                ->after('version');

        });
    }


    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->dropColumn('tanggal_berlaku');

        });
    }

};