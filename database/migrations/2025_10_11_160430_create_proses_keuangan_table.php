<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proses_keuangan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id');
            $table->string('nomor_invoice')->nullable();
            $table->string('kode_akun')->nullable();
            $table->text('uraian')->nullable();
            $table->decimal('jumlah_pengajuan', 15, 2)->default(0);
            $table->decimal('pph_21', 10, 2)->default(0);
            $table->decimal('pph_22', 10, 2)->default(0);
            $table->decimal('pph_23', 10, 2)->default(0);
            $table->decimal('ppn', 10, 2)->default(0);
            $table->decimal('dibayarkan', 15, 2)->default(0);
            $table->string('no_rekening')->nullable();
            $table->string('bank')->nullable();
            $table->timestamps();

            $table->foreign('pengajuan_id')->references('id')->on('pengajuan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proses_keuangan');
    }
};
