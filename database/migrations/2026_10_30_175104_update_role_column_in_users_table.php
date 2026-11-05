<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom role menjadi string biasa (varchar 50) agar fleksibel dan tidak error lagi
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'teknisi'");
    }

    public function down(): void
    {
        // Rollback ke enum jika diperlukan
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teknisi') NOT NULL DEFAULT 'teknisi'");
    }
};