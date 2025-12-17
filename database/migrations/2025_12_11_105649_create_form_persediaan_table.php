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
        Schema::create('form_persediaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->string('nomor')->nullable();
            $table->string('bidang')->nullable();
            $table->json('items'); // simpan daftar barang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_persediaan');
    }
};
