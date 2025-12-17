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
        Schema::table('pengajuan_items', function (Blueprint $table) {
            $table->integer('jumlah_tersedia')->nullable()->after('volume');
            $table->enum('status_persediaan', ['ADA','TIDAK_ADA','SEBAGIAN'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_items', function (Blueprint $table) {
            //
        });
    }
};
