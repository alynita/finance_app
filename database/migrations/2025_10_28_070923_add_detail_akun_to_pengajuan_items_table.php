<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_items', function (Blueprint $table) {
            $table->string('detail_akun')->nullable()->after('nama_barang');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_items', function (Blueprint $table) {
            $table->dropColumn('detail_akun');
        });
    }
};
