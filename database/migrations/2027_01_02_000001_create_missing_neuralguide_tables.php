<?php
// database/migrations/2026_01_02_000001_create_missing_neuralguide_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── جدول الوسوم architecture_category ──────────────
        if (!Schema::hasTable('architecture_category')) {
            Schema::create('architecture_category', function (Blueprint $table) {
                $table->foreignId('architecture_id')->constrained()->cascadeOnDelete();
                $table->foreignId('category_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->primary(['architecture_id', 'category_id']);
            });
        }

        // ─── جدول pivot للاقتراحات architecture_suggestion ──
        if (!Schema::hasTable('architecture_suggestion')) {
            Schema::create('architecture_suggestion', function (Blueprint $table) {
                $table->foreignId('architecture_id')->constrained()->cascadeOnDelete();
                $table->foreignId('suggestion_id')->constrained()->cascadeOnDelete();
                $table->unsignedTinyInteger('score')->default(80);
                $table->unsignedTinyInteger('rank')->default(1);
                $table->text('reason')->nullable();
                $table->timestamps();
                $table->primary(['architecture_id', 'suggestion_id']);
            });
        }

        // ─── جدول الاقتراحات suggestions ─────────────────────
        if (!Schema::hasTable('suggestions')) {
            Schema::create('suggestions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->text('problem_text');
                $table->string('detected_domain', 50)->default('general');
                $table->string('input_language', 5)->default('ar');
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        // ─── جدول التعليقات comments ──────────────────────────
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('architecture_id')->constrained()->cascadeOnDelete();
                $table->tinyInteger('rating')->nullable()->comment('1-5');
                $table->text('body');
                $table->boolean('is_approved')->default(false);
                $table->timestamps();
                $table->index(['architecture_id', 'is_approved']);
            });
        }

        // ─── جدول المفضلة favorites ───────────────────────────
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('architecture_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->primary(['user_id', 'architecture_id']);
            });
        }

        // ─── جدول الملاحظات البحثية research_notes ───────────
        if (!Schema::hasTable('research_notes')) {
            Schema::create('research_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('architecture_id')->nullable()->constrained()->nullOnDelete();
                $table->string('title', 200);
                $table->text('body');
                $table->enum('visibility', ['private', 'public'])->default('private');
                $table->timestamps();
                $table->index(['user_id', 'visibility']);
            });
        }

        // ─── جدول مجموعات البيانات training_datasets ─────────
        if (!Schema::hasTable('training_datasets')) {
            Schema::create('training_datasets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('name', 200);
                $table->text('description')->nullable();
                $table->enum('type', ['csv', 'images', 'json', 'custom'])->default('csv');
                $table->string('task_type', 100)->nullable();
                $table->string('file_path', 500);
                $table->unsignedBigInteger('file_size')->default(0);
                $table->string('file_name', 255);
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        // ─── جدول تجارب التدريب training_experiments ──────────
        if (!Schema::hasTable('training_experiments')) {
            Schema::create('training_experiments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('architecture_id')->constrained()->cascadeOnDelete();
                $table->foreignId('dataset_id')
                    ->nullable()
                    ->references('id')->on('training_datasets')
                    ->nullOnDelete();
                $table->string('name', 200);
                $table->enum('framework', ['pytorch', 'tensorflow'])->default('pytorch');
                $table->json('hyperparameters')->nullable();
                $table->longText('generated_code')->nullable();
                $table->longText('custom_code')->nullable();
                $table->text('notes')->nullable();
                $table->enum('status', ['draft','queued','running','completed','failed'])->default('draft');
                $table->json('result_metrics')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'status']);
            });
        }

        // ─── جدول سجلات التشغيل training_runs ────────────────
        if (!Schema::hasTable('training_runs')) {
            Schema::create('training_runs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('training_experiment_id')->constrained()->cascadeOnDelete();
                $table->enum('status', ['queued','running','completed','failed'])->default('queued');
                $table->timestamp('started_at')->nullable();
                $table->timestamp('finished_at')->nullable();
                $table->longText('logs')->nullable();
                $table->json('metrics')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('training_runs');
        Schema::dropIfExists('training_experiments');
        Schema::dropIfExists('training_datasets');
        Schema::dropIfExists('research_notes');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('architecture_suggestion');
        Schema::dropIfExists('architecture_category');
        Schema::dropIfExists('suggestions');
    }
};
