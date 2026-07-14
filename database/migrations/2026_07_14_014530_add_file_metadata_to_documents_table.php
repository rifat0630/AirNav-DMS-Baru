<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->string('google_file_name')
                ->nullable()
                ->after('google_drive_id');

            $table->string('file_type')
                ->nullable()
                ->after('google_file_name');

            $table->bigInteger('file_size')
                ->nullable()
                ->after('file_type');

        });
    }


    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->dropColumn([
                'google_file_name',
                'file_type',
                'file_size'
            ]);

        });
    }
};