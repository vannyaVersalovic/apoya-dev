<?php
// database/migrations/2026_03_09_174632_create_supports_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_page_id')->constrained()->cascadeOnDelete();
            $table->string('supporter_name');
            $table->text('message')->nullable();
            $table->unsignedTinyInteger('amount'); // 1–100
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supports');
    }
};