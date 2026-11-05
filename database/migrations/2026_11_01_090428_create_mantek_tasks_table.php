<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantek_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('assigned_to')->nullable(); // null = semua teknisi
            $table->date('target_date');
            $table->text('instruction')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status_terbit', ['Terbit', 'Draf'])->default('Draf');
            $table->string('status_pengerjaan')->default('Belum Selesai');
            $table->timestamps();

            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantek_tasks');
    }
};