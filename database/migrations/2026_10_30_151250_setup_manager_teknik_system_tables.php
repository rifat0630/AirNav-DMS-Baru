<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Permintaan Izin Akses (Admin & Teknisi harus minta izin ke Mantek)
        if (!Schema::hasTable('manager_teknik_access_requests')) {
            Schema::create('manager_teknik_access_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->text('reason')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        // 2. Modifikasi kolom pada manager_teknik_assignments jika belum mendukung draft / instruksi opsional
        if (Schema::hasTable('manager_teknik_assignments')) {
            Schema::table('manager_teknik_assignments', function (Blueprint $table) {
                if (!Schema::hasColumn('manager_teknik_assignments', 'is_published')) {
                    $table->boolean('is_published')->default(false)->after('status');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('manager_teknik_access_requests');
        if (Schema::hasTable('manager_teknik_assignments')) {
            Schema::table('manager_teknik_assignments', function (Blueprint $table) {
                $table->dropColumn('is_published');
            });
        }
    }
};