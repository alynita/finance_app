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
            $table->decimal('pph21', 15, 2)->default(0);
            $table->decimal('pph22', 15, 2)->default(0);
            $table->decimal('pph23', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('dibayarkan', 15, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_items', function (Blueprint $table) {
            $table->dropColumn(['pph21', 'pph22', 'pph23', 'ppn', 'dibayarkan']);
        });
    }

};
