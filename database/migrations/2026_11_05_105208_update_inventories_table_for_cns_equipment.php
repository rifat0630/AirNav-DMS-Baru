<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            if (!Schema::hasColumn('inventories', 'nama_barang')) {
                $table->string('nama_barang')->nullable()->after('id');
            }
            if (!Schema::hasColumn('inventories', 'lokasi')) {
                $table->string('lokasi')->nullable()->after('nama_barang');
            }
            if (!Schema::hasColumn('inventories', 'jumlah')) {
                $table->integer('jumlah')->default(1)->after('lokasi');
            }
            if (!Schema::hasColumn('inventories', 'satuan')) {
                $table->string('satuan')->default('pcs')->after('jumlah');
            }
            if (!Schema::hasColumn('inventories', 'kondisi')) {
                $table->enum('kondisi', ['Normal', 'Perlu Perbaikan', 'Rusak Berat'])->default('Normal')->after('satuan');
            }
            if (!Schema::hasColumn('inventories', 'detail_kerusakan')) {
                $table->text('detail_kerusakan')->nullable()->after('kondisi');
            }
            if (!Schema::hasColumn('inventories', 'tanggal_rusak')) {
                $table->date('tanggal_rusak')->nullable()->after('detail_kerusakan');
            }
            if (!Schema::hasColumn('inventories', 'tanggal_perbaikan')) {
                $table->date('tanggal_perbaikan')->nullable()->after('tanggal_rusak');
            }
            if (!Schema::hasColumn('inventories', 'teknisi_bertugas')) {
                $table->string('teknisi_bertugas')->nullable()->after('tanggal_perbaikan');
            }
            if (!Schema::hasColumn('inventories', 'paraf_mantek')) {
                $table->string('paraf_mantek')->nullable()->after('teknisi_bertugas');
            }
            if (!Schema::hasColumn('inventories', 'status_penanganan')) {
                $table->string('status_penanganan')->default('Normal')->after('paraf_mantek');
            }
            if (!Schema::hasColumn('inventories', 'foto')) {
                $table->string('foto')->nullable()->after('status_penanganan');
            }
            if (!Schema::hasColumn('inventories', 'keterangan_foto')) {
                $table->text('keterangan_foto')->nullable()->after('foto');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn([
                'nama_barang', 'lokasi', 'jumlah', 'satuan', 'kondisi',
                'detail_kerusakan', 'tanggal_rusak', 'tanggal_perbaikan',
                'teknisi_bertugas', 'paraf_mantek', 'status_penanganan',
                'foto', 'keterangan_foto'
            ]);
        });
    }
};