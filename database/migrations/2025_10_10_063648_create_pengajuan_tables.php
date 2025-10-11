<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel header pengajuan
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke users
            $table->string('nama_kegiatan');
            $table->date('waktu_kegiatan');
            $table->string('jenis_pengajuan'); // kerusakan, pembelian, honorium, dll
            $table->enum('status', ['pending', 'approve', 'reject'])->default('pending');
            $table->timestamps();
        });

        // Tabel detail item pengajuan
        Schema::create('pengajuan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->onDelete('cascade');
            $table->string('tipe_item'); // kerusakan, pembelian, honorium

            // Kolom untuk pengajuan kerusakan / pembelian
            $table->string('nama_barang')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('jenis_kerusakan')->nullable();
            $table->integer('volume')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('ongkos_kirim', 15, 2)->nullable();
            $table->decimal('jumlah_dana_pengajuan', 15, 2)->nullable();
            $table->string('kro')->nullable(); // kode akun
            $table->string('foto')->nullable(); // path file foto

            // Kolom untuk honorium
            $table->date('tanggal')->nullable();
            $table->string('nama')->nullable();
            $table->string('jabatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_items');
        Schema::dropIfExists('pengajuan');
    }
};
