<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update tabel izin - tambah kolom approved_at
        Schema::table('izin', function (Blueprint $table) {
            if (!Schema::hasColumn('izin', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('catatan_approval');
            }
        });

        // Ubah enum status_approval
        DB::statement("ALTER TABLE `izin` MODIFY `status_approval` ENUM('pending','approved','rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('izin', function (Blueprint $table) {
            if (Schema::hasColumn('izin', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
        });

        DB::statement("ALTER TABLE `izin` MODIFY `status_approval` ENUM('pending','disetujui','ditolak') DEFAULT 'pending'");
    }
};
