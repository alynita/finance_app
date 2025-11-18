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
        Schema::table('honor_details', function (Blueprint $table) {
            $table->integer('uang_transport')->nullable()->after('uang_harian');
            $table->string('jenis_uang')->nullable()->after('jabatan'); 
        });
    }

    public function down()
    {
        Schema::table('honor_details', function (Blueprint $table) {
            $table->dropColumn(['uang_transport', 'jenis_uang']);
        });
    }

};
