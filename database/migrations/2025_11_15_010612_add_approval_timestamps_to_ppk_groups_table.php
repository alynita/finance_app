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
            $table->timestamp('adum_approved_at')->nullable();
            $table->timestamp('ppk_approved_at')->nullable();
            $table->timestamp('verifikator_approved_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('ppk_groups', function (Blueprint $table) {
            $table->dropColumn(['adum_approved_at', 'ppk_approved_at', 'verifikator_approved_at']);
        });
    }

};
