<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_items', function (Blueprint $table) {
            $table->string('nama_pajak_baru')->nullable()->after('ppn');
            $table->decimal('persen_pajak_baru', 5, 2)->nullable()->after('nama_pajak_baru');
            $table->decimal('hasil_pajak_baru', 15, 2)->nullable()->after('persen_pajak_baru');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_items', function (Blueprint $table) {
            $table->dropColumn(['nama_pajak_baru', 'persen_pajak_baru', 'hasil_pajak_baru']);
        });
    }
};
