<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('pengeluaran_barang', function (Blueprint $table) {
            $table->foreignId('persediaan_id')
                ->nullable()
                ->after('pengajuan_id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('pengeluaran_barang', function (Blueprint $table) {
            $table->dropForeign(['persediaan_id']);
            $table->dropColumn('persediaan_id');
        });
    }
};
