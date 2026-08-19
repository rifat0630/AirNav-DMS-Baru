<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::table('facility_logbooks', function (Blueprint $table) {


            $table->foreignId('technician_id')
                ->nullable()
                ->after('id');


            $table->string('signature_type')
                ->nullable();


            $table->string('signature_file')
                ->nullable();


            $table->string('qr_token')
                ->nullable();


            $table->timestamp('signed_at')
                ->nullable();


        });

    }



    public function down(): void
    {

        Schema::table('facility_logbooks', function (Blueprint $table) {


            $table->dropColumn([
                'technician_id',
                'signature_type',
                'signature_file',
                'qr_token',
                'signed_at'
            ]);


        });

    }

};