<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manager_teknik_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_teknik_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('instruction');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->date('target_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->text('technician_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manager_teknik_assignments');
    }
};