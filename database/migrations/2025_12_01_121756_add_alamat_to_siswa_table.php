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
        $table->text('alamat')->nullable();
    });
}

public function down()
{
    Schema::table('siswa', function (Blueprint $table) {
        $table->dropColumn('alamat');
    });
}
};