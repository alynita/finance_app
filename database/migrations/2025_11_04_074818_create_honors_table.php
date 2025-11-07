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
        Schema::create('honors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('tujuan');
            $table->decimal('uang_harian', 15, 2);
            $table->decimal('persentase', 5, 2);
            $table->decimal('jumlah_dibayar', 15, 2);
            $table->string('nomor_rekening');
            $table->string('atas_nama');
            $table->string('bank');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('honors');
    }
};
