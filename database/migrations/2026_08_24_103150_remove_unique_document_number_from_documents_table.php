<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropUnique(
                'documents_document_number_unique'
            );
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unique(
                'document_number',
                'documents_document_number_unique'
            );
        });
    }
};