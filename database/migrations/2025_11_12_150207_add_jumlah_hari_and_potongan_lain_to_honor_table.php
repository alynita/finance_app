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
            $table->integer('jumlah_hari')->nullable();
            $table->decimal('potongan_lain', 5, 2)->nullable(); // disimpan dalam persen
        });
    }

    public function down()
    {
        Schema::table('honors', function (Blueprint $table) {
            $table->dropColumn(['jumlah_hari', 'potongan_lain']);
        });
    }

};
