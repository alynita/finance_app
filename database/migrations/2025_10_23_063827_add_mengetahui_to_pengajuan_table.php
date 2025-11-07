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
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->string('mengetahui')->nullable(); // ex: timker_1, adum
            $table->foreignId('mengetahui_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('mengetahui_approved_at')->nullable();
            $table->string('mengetahui_keterangan')->nullable(); // untuk catatan approve/reject
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            //
        });
    }
};
