<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('honor_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('honor_id')->constrained('honors')->onDelete('cascade');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('tujuan');
            $table->integer('jumlah_hari');
            $table->decimal('uang_harian', 15, 2);
            $table->decimal('pph21', 5, 2);
            $table->decimal('potongan_lain', 15, 2)->nullable();
            $table->decimal('jumlah_dibayar', 15, 2)->nullable();
            $table->string('nomor_rekening');
            $table->string('atas_nama');
            $table->string('bank');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honor_details');
    }
};
