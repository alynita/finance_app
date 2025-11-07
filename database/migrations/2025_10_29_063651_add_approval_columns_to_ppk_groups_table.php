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
        Schema::table('ppk_groups', function (Blueprint $table) {
            $table->boolean('adum_approved_process')->default(0);
            $table->unsignedBigInteger('adum_id')->nullable();
            $table->boolean('ppk_approved_process')->default(0);
            $table->unsignedBigInteger('ppk_id')->nullable();
            $table->boolean('verifikator_approved_process')->default(0);
            $table->unsignedBigInteger('verifikator_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('ppk_groups', function (Blueprint $table) {
            $table->dropColumn([
                'adum_approved_process', 'adum_id',
                'ppk_approved_process', 'ppk_id',
                'verifikator_approved_process', 'verifikator_id'
            ]);
        });
    }

};
