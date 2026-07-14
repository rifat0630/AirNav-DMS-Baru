<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_versions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('document_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('version');

            $table->string('file_name');

            $table->string('file_path');

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};