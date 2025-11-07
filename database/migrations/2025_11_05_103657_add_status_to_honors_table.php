<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('honors', function (Blueprint $table) {
            $table->enum('status', ['pending', 'adum_approved', 'ppk_approved'])->default('pending');
            $table->foreignId('adum_id')->nullable()->constrained('users');
            $table->timestamp('adum_approved_at')->nullable();
            $table->foreignId('ppk_id')->nullable()->constrained('users');
            $table->timestamp('ppk_approved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('honors', function (Blueprint $table) {
            $table->dropColumn(['status', 'adum_id', 'adum_approved_at', 'ppk_id', 'ppk_approved_at']);
        });
    }
};

