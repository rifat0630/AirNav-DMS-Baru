<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('documents', function (Blueprint $table) {
        $table->id();

        $table->string('document_number')->unique();
        $table->string('title');

        $table->foreignId('category_id')->constrained()->cascadeOnDelete();

        $table->string('version')->default('1.0');
        $table->enum('status', ['Aktif', 'Revisi', 'Arsip'])->default('Aktif');

        $table->string('file_name');
        $table->string('file_path');
        $table->string('google_drive_id')->nullable();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
