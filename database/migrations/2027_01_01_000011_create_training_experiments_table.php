<?php
// database/migrations/2025_01_01_000011_create_training_experiments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('training_experiments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('architecture_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dataset_id')->nullable()
                  ->references('id')->on('training_datasets')->nullOnDelete();
            $table->string('name');
            $table->enum('framework', ['pytorch', 'tensorflow'])->default('pytorch');
            $table->json('hyperparameters')->nullable();
            $table->longText('generated_code')->nullable();
            $table->longText('custom_code')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['draft','queued','running','completed','failed'])->default('draft');
            $table->json('result_metrics')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('training_experiments'); }
};
