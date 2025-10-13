<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            // foreign key untuk PJ
            $table->unsignedBigInteger('pj_id')->nullable();
            $table->foreign('pj_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('pj_approved_at')->nullable();

            // foreign key untuk ADUM
            $table->unsignedBigInteger('adum_id')->nullable();
            $table->foreign('adum_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('adum_approved_at')->nullable();

            // foreign key untuk PPK
            $table->unsignedBigInteger('ppk_id')->nullable();
            $table->foreign('ppk_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('ppk_approved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropForeign(['pj_id']);
            $table->dropForeign(['adum_id']);
            $table->dropForeign(['ppk_id']);
            $table->dropColumn(['pj_id', 'pj_approved_at', 'adum_id', 'adum_approved_at', 'ppk_id', 'ppk_approved_at']);
        });
    }
};
