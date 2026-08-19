<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {

        Schema::table('technicians', function (Blueprint $table) {


            $table->enum('status',[
                'aktif',
                'mutasi',
                'tidak_aktif'
            ])
            ->default('aktif')
            ->change();


        });

    }



    public function down()
    {

        Schema::table('technicians', function (Blueprint $table) {


            $table->enum('status',[
                'aktif',
                'mutasi'
            ])
            ->default('aktif')
            ->change();


        });

    }

};