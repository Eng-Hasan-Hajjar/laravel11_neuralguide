<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\TrainingExperiment;
use App\Models\TrainingDataset;
use App\Policies\TrainingExperimentPolicy;
use App\Policies\TrainingDatasetPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind NeuralSuggestionService as singleton
        $this->app->singleton(
            \App\Services\NeuralSuggestionService::class
        );

        // Bind PythonCodeGeneratorService as singleton
        $this->app->singleton(
            \App\Services\PythonCodeGeneratorService::class
        );
    }

    public function boot(): void
    {
        // ─── Register Policies ────────────────────────────────
        Gate::policy(TrainingExperiment::class, TrainingExperimentPolicy::class);
        Gate::policy(TrainingDataset::class,    TrainingDatasetPolicy::class);
    }
}
