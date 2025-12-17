<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengeluaran_barang_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengeluaran_id');
            $table->unsignedBigInteger('pengajuan_item_id');

            // Field sesuai form
            $table->string('nama_barang');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->string('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('pengeluaran_id')
                ->references('id')->on('pengeluaran_barang')
                ->onDelete('cascade');

            $table->foreign('pengajuan_item_id')
                ->references('id')->on('pengajuan_items')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_barang_items');
    }
};
