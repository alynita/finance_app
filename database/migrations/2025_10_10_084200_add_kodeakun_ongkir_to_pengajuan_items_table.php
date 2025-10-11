<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('pengajuan_items', function (Blueprint $table) {
            $table->string('kode_akun')->nullable();
            $table->integer('ongkir')->default(0);
        });
    }

    public function down()
    {
        Schema::table('pengajuan_items', function (Blueprint $table) {
            $table->dropColumn(['kode_akun', 'ongkir']);
        });
    }

};
