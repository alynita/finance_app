<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ppk_group_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppk_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained('pengajuan_items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppk_group_items');
    }
};
