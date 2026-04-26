<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use App\Services\NeuralSuggestionService;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function store(Request $request, NeuralSuggestionService $service)
    {
        $validated = $request->validate([
            'problem' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $result = $service->suggest($validated['problem']);

        $suggestion = Suggestion::create([
            'user_id' => auth()->id(),
            'problem_text' => $validated['problem'],
            'detected_domain' => $result['domain'] ?? 'general',
            'input_language' => 'ar',
            'metadata' => [
                'analysis' => $result['analysis'] ?? null,
                'created_from' => 'home_form',
            ],
        ]);

        $syncData = [];

        foreach ($result['architectures'] as $index => $architecture) {
            $syncData[$architecture->id] = [
                'score' => $architecture->suggestion_score ?? 80,
                'rank' => $index + 1,
                'reason' => $architecture->suggestion_reason ?? 'تم اختيار هذه المعمارية لأنها مناسبة لنوع المشكلة المدخلة.',
            ];
        }

        $suggestion->architectures()->sync($syncData);

        return redirect()->route('suggestions.show', $suggestion);
    }

    public function show(Suggestion $suggestion)
    {
        $suggestion->load([
            'architectures' => function ($query) {
                $query->orderBy('architecture_suggestion.rank');
            },
            'architectures.categories',
        ]);

        return view('suggestions.show', compact('suggestion'));
    }
}