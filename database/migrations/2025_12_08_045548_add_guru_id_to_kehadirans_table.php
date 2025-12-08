<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kehadirans', function (Blueprint $table) {
            // Tambah kolom guru_id
            $table->foreignId('guru_id')->nullable()->after('siswa_id')->constrained('gurus');
            
            // Hapus duplikat data (jika ada)
            DB::statement('DELETE k1 FROM kehadirans k1 INNER JOIN kehadirans k2 WHERE k1.id < k2.id AND k1.siswa_id = k2.siswa_id AND k1.tanggal = k2.tanggal');
            
            // Tambah constraint unique (optional)
            $table->unique(['siswa_id', 'tanggal', 'guru_id']);
        });
    }

    public function down()
    {
        Schema::table('kehadirans', function (Blueprint $table) {
            $table->dropUnique(['siswa_id', 'tanggal', 'guru_id']);
            $table->dropForeign(['guru_id']);
            $table->dropColumn('guru_id');
        });
    }
};