<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create('technicians', function (Blueprint $table) {


            $table->id();


            $table->string('name');


            $table->enum('signature_type', [
                'manual',
                'qr'
            ]);



            $table->string('signature_file')
                  ->nullable();



            $table->enum('status', [
                'aktif',
                'mutasi'
            ])
            ->default('aktif');



            $table->timestamps();


        });

    }



    public function down(): void
    {

        Schema::dropIfExists('technicians');

    }

};