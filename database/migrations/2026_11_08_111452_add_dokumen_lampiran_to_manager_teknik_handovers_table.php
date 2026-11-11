<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manager_teknik_handovers', function (Blueprint $table) {
            if (!Schema::hasColumn('manager_teknik_handovers', 'dokumen_lampiran')) {
                $table->string('dokumen_lampiran')->nullable()->after('paraf_digital');
            }
        });
    }

    public function down(): void
    {
        Schema::table('manager_teknik_handovers', function (Blueprint $table) {
            if (Schema::hasColumn('manager_teknik_handovers', 'dokumen_lampiran')) {
                $table->dropColumn('dokumen_lampiran');
            }
        });
    }
};