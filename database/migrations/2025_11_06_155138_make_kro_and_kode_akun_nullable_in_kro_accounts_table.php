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
    Schema::table('kro_accounts', function (Blueprint $table) {
        $table->string('kro')->nullable()->change();
        $table->string('kode_akun')->nullable()->change();
    });
}

public function down()
{
    Schema::table('kro_accounts', function (Blueprint $table) {
        $table->string('kro')->nullable(false)->change();
        $table->string('kode_akun')->nullable(false)->change();
    });
}

};
