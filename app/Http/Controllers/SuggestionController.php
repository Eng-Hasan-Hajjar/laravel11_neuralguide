<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreSuggestionRequest;
use App\Models\SearchLog;
use App\Models\Suggestion;
use App\Services\NeuralSuggestionService;
use Illuminate\Http\Request;

class SuggestionController extends Controller {
    public function store(StoreSuggestionRequest $request, NeuralSuggestionService $service) {
        $analysis = $service->suggest($request->validated('problem_text'), 5);
        $suggestion = Suggestion::create([
            'user_id' => $request->user()?->id,
            'problem_text' => $request->validated('problem_text'),
            'detected_domain' => $analysis['domain'],
            'input_language' => 'ar',
            'metadata' => ['engine'=>'rule-based-v1'],
        ]);
        foreach ($analysis['results'] as $index => $item) {
            $suggestion->architectures()->attach($item['architecture']->id, [
                'score'=>$item['score'], 'rank'=>$index+1, 'reason'=>$item['reason']
            ]);
        }
        SearchLog::create([
            'user_id'=>$request->user()?->id, 'query'=>$request->validated('problem_text'),
            'ip_address'=>$request->ip(), 'user_agent'=>$request->userAgent(),
            'results_count'=>$analysis['results']->count(), 'metadata'=>['domain'=>$analysis['domain']]
        ]);
        return redirect()->route('suggestions.show', $suggestion);
    }
    public function show(Suggestion $suggestion) {
        $suggestion->load('architectures.categories','user');
        return view('suggestions.show', compact('suggestion'));
    }
}
