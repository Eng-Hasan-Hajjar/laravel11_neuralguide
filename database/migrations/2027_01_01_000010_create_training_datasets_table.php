<?php
// database/migrations/2025_01_01_000010_create_training_datasets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('training_datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['csv', 'images', 'json', 'custom'])->default('csv');
            $table->string('task_type')->nullable();
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('file_name');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('training_datasets'); }
};
