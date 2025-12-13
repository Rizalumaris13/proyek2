<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('siswa', function (Blueprint $table) {
        if (!Schema::hasColumn('siswa', 'kelas_id')) {
            $table->unsignedBigInteger('kelas_id')->nullable()->after('jenis_kelamin');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('set null');
        }
    });
}

public function down()
{
    Schema::table('siswa', function (Blueprint $table) {
        if (Schema::hasColumn('siswa', 'kelas_id')) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        }
    });
}
};
