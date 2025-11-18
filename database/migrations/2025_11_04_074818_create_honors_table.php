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
        Schema::create('honors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->date('waktu');
            $table->decimal('alokasi_anggaran', 15, 2);

            // Approval tracking
            $table->unsignedBigInteger('adum_id')->nullable();
            $table->timestamp('adum_approved_at')->nullable();
            $table->unsignedBigInteger('ppk_id')->nullable();
            $table->timestamp('ppk_approved_at')->nullable();

            // Pengaju / pembuat data
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('status')->default('pending');

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
