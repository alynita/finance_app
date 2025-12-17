<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengeluaran_barang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->string('kode_pengeluaran')->nullable();
            $table->string('bidang_bagian')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->string('nama_petugas_persediaan')->nullable();
            $table->string('nama_penyerah')->nullable();
            $table->date('tanggal_pengeluaran')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_id')
                ->references('id')->on('pengajuan')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_barang');
    }
};
