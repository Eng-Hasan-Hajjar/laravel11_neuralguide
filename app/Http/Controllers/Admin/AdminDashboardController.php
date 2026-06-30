<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Architecture;
use App\Models\Category;
use App\Models\User;
use App\Models\TrainingExperiment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'architectures'     => Architecture::count(),
            'published'         => Architecture::where('is_published', true)->count(),
            'users'             => User::count(),
            'categories'        => Category::count(),
            'experiments'       => class_exists(TrainingExperiment::class) ? TrainingExperiment::count() : 0,
            'running'           => class_exists(TrainingExperiment::class) ? TrainingExperiment::where('status', 'running')->count() : 0,
        ];

        // Last 14 days registrations
        $days = collect(range(13, 0))->map(fn($i) => now()->subDays($i)->format('d M'));
        $regData = collect(range(13, 0))->map(
            fn($i) => User::whereDate('created_at', now()->subDays($i))->count()
        );
        $chart = ['labels' => $days->values(), 'data' => $regData->values()];

        $latestUsers    = User::latest()->take(8)->get();
        $latestArchitectures = Architecture::latest()->take(8)->get();
        $latestExperiments   = class_exists(TrainingExperiment::class)
            ? TrainingExperiment::with('user','architecture')->latest()->take(8)->get()
            : collect();

        return view('admin.dashboard.index', compact(
            'stats', 'chart', 'latestUsers', 'latestArchitectures', 'latestExperiments'
        ));
    }
}
