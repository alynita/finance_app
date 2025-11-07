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
            $table->string('value')->after('id'); // bisa nullable kalau mau
        });
    }

    public function down()
    {
        Schema::table('kro_accounts', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }

};
