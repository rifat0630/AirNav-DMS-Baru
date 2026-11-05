<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kolom tambahan untuk tabel inventories (hanya dibuat jika belum ada)
        Schema::table('inventories', function (Blueprint $table) {
            if (!Schema::hasColumn('inventories', 'location')) {
                $table->string('location')->nullable()->after('name');
            }
            if (!Schema::hasColumn('inventories', 'condition')) {
                $table->enum('condition', ['normal', 'rusak', 'perlu_perbaikan'])->default('normal');
            }
            if (!Schema::hasColumn('inventories', 'unit')) {
                $table->string('unit')->default('pcs');
            }
            if (!Schema::hasColumn('inventories', 'photo')) {
                $table->string('photo')->nullable();
            }
            if (!Schema::hasColumn('inventories', 'photo_caption')) {
                $table->text('photo_caption')->nullable();
            }
            if (!Schema::hasColumn('inventories', 'damaged_at')) {
                $table->dateTime('damaged_at')->nullable();
            }
            if (!Schema::hasColumn('inventories', 'repaired_at')) {
                $table->dateTime('repaired_at')->nullable();
            }
            if (!Schema::hasColumn('inventories', 'technician_paraf')) {
                $table->string('technician_paraf')->nullable();
            }
            if (!Schema::hasColumn('inventories', 'mantek_paraf')) {
                $table->string('mantek_paraf')->nullable();
            }
        });

        // 2. Kolom masa berlaku untuk documents
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'expired_at')) {
                $table->date('expired_at')->nullable()->after('title');
            }
        });

        // 3. Tabel instruksi & tugas dari Mantek
        if (!Schema::hasTable('mantek_instructions')) {
            Schema::create('mantek_instructions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('assigned_technician_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title');
                $table->text('instruction_text')->nullable();
                $table->string('attachment')->nullable();
                $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
                $table->timestamps();
            });
        }

        // 4. Tabel jadwal dinas teknisi
        if (!Schema::hasTable('technician_schedules')) {
            Schema::create('technician_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->date('schedule_date');
                $table->string('shift'); // Pagi, Siang, Malam
                $table->text('daily_tasks')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_schedules');
        Schema::dropIfExists('mantek_instructions');

        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'expired_at')) {
                $table->dropColumn('expired_at');
            }
        });

        Schema::table('inventories', function (Blueprint $table) {
            $columns = [
                'condition', 'unit', 'photo', 'photo_caption',
                'damaged_at', 'repaired_at', 'technician_paraf', 'mantek_paraf'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('inventories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};