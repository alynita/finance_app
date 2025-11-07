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
        Schema::create('kro_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('kro');        // Contoh: 123.4567.8923
            $table->string('kode_akun');  // Contoh: A.345678
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kro_accounts');
    }
};
