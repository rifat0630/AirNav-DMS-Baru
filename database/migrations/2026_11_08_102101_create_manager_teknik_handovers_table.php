<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manager_teknik_handovers', function (Blueprint $table) {
            $table->id();
            $table->string('judul_resume'); // Contoh: Resume Memori Jabatan Periode 2024-2026
            $table->string('pejabat_nama'); // Nama Manager Teknik
            $table->string('pejabat_nip')->nullable();
            $table->date('tanggal_mulai_tugas')->nullable();
            $table->date('tanggal_selesai_tugas')->nullable();
            $table->longText('uraian_sudah_selesai'); // Fasilitas selesai, kalibrasi beres, dokumen izin selesai
            $table->longText('uraian_sedang_berjalan'); // Pekerjaan yang masih on-progress di lapangan
            $table->longText('uraian_rencana_ke_depan'); // Backlog, jadwal kalibrasi tahun depan, pengadaan modul
            $table->text('catatan_khusus_pejabat_baru')->nullable(); // Pesan khusus kepemimpinan
            $table->longText('paraf_digital')->nullable(); // Tanda tangan / paraf digital canvas
            $table->enum('status_dokumen', ['Draf', 'Resmi Diarsipkan'])->default('Draf');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manager_teknik_handovers');
    }
};