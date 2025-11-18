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
        Schema::table('honors', function (Blueprint $table) {
            $table->boolean('arsip')->default(0); // 0 = tidak arsip, 1 = arsip
        });
    }

    public function down()
    {
        Schema::table('honors', function (Blueprint $table) {
            $table->dropColumn('arsip');
        });
    }

};
