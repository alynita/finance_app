<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('honorarium', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id'); // relasi ke tabel pengajuan
            $table->date('tanggal')->nullable();
            $table->string('nama')->nullable();
            $table->string('jabatan')->nullable();

            // bagian uraian honor
            $table->decimal('jumlah_honor', 15, 2)->nullable();
            $table->integer('bulan')->nullable();
            $table->decimal('total_honor', 15, 2)->nullable();
            $table->decimal('pph_21', 15, 2)->nullable();
            $table->decimal('jumlah', 15, 2)->nullable();

            $table->string('no_rekening')->nullable();
            $table->string('bank')->nullable();

            $table->timestamps();

            $table->foreign('pengajuan_id')->references('id')->on('pengajuan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('honorarium');
    }
};
