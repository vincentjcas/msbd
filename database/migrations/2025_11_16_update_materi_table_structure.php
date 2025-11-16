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
        Schema::table('materi', function (Blueprint $table) {
            // Add judul column (keep judul_materi for now)
            $table->string('judul', 200)->after('id_kelas')->nullable();
            
            // Add new file columns
            $table->string('file_path', 255)->after('deskripsi')->nullable();
            $table->string('file_name', 255)->after('file_path')->nullable();
            $table->bigInteger('file_size')->after('file_name')->nullable();
        });
        
        // Copy data from judul_materi to judul
        DB::statement('UPDATE materi SET judul = judul_materi WHERE judul IS NULL');
        
        // Now we can drop judul_materi and file_materi
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn(['judul_materi', 'file_materi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            // Add back old columns
            $table->string('judul_materi', 200)->after('id_kelas')->nullable();
            $table->string('file_materi', 255)->after('deskripsi')->nullable();
        });
        
        // Copy data back
        DB::statement('UPDATE materi SET judul_materi = judul WHERE judul_materi IS NULL');
        
        // Drop new columns
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn(['judul', 'file_path', 'file_name', 'file_size']);
        });
    }
};
