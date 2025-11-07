<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_items', function (Blueprint $table) {
            $table->string('status_ppk')->nullable()->default('pending');
            $table->text('catatan_ppk')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_items', function (Blueprint $table) {
            $table->dropColumn(['status_ppk', 'catatan_ppk']);
        });
    }
};
