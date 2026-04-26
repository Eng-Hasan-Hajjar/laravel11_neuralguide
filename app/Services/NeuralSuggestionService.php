<?php

namespace App\Services;

use App\Models\Architecture;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NeuralSuggestionService
{
    private array $rules = [
        'vision' => ['صورة','صور','تصنيف','كشف','تقسيم','وجوه','طبي','اشعة','object','image','segmentation','detection','cnn','vision'],
        'nlp' => ['نص','لغة','ترجمة','تلخيص','شعر','محادثة','سؤال','إجابة','sentiment','text','nlp','language','transformer','bert','gpt'],
        'time_series' => ['زمنية','أسهم','تنبؤ','سعر','مبيعات','طقس','sensor','forecast','time series','lstm','gru'],
        'tabular' => ['احتيال','معاملات','جدول','ائتمان','تصنيف عملاء','churn','fraud','tabular','bank'],
        'generative' => ['توليد','إبداع','صور جديدة','فيديو','صوت','diffusion','gan','generate','vae'],
        'graph' => ['رسم بياني','علاقات','شبكات اجتماعية','جزيئات','knowledge graph','graph','node','edge'],
        'reinforcement' => ['روبوت','لعبة','قرار','تحكم','مسار','agent','reinforcement','policy','reward'],
        'recommendation' => ['توصية','اقتراح','منتجات','أفلام','مستخدمين','recommendation','ranking'],
        'audio' => ['صوت','كلام','تعرف على الكلام','موسيقى','audio','speech','wav'],
    ];

    public function suggest(string $text, int $limit = 5): array
    {
        $domain = $this->detectDomain($text);
        $tokens = $this->tokens($text);

        $architectures = Architecture::query()
            ->where('is_published', true)
            ->with('categories')
            ->get();

        $ranked = $architectures->map(function (Architecture $architecture) use ($domain, $tokens) {
            $score = 0;
            $haystack = Str::lower(implode(' ', [
                $architecture->name,
                $architecture->short_description,
                $architecture->description,
                $architecture->best_for,
                implode(' ', $architecture->tags ?? []),
                $architecture->categories->pluck('slug')->join(' '),
                $architecture->categories->pluck('name')->join(' '),
            ]));

            foreach ($tokens as $token) {
                if (Str::contains($haystack, Str::lower($token))) {
                    $score += mb_strlen($token) > 4 ? 8 : 4;
                }
            }

            foreach ($architecture->categories as $category) {
                if ($category->slug === $domain) {
                    $score += 55;
                }
            }

            if (Str::contains($haystack, $domain)) {
                $score += 20;
            }

            $score += match ($architecture->difficulty) {
                'beginner' => 4,
                'intermediate' => 6,
                'advanced' => 3,
                default => 1,
            };

            return [
                'architecture' => $architecture,
                'score' => $score,
                'reason' => $this->buildReason($architecture, $domain, $score),
            ];
        })->sortByDesc('score')->take($limit)->values();

        return ['domain' => $domain, 'results' => $ranked];
    }

    public function detectDomain(string $text): string
    {
        $normalized = Str::lower($text);
        $scores = [];
        foreach ($this->rules as $domain => $keywords) {
            $scores[$domain] = 0;
            foreach ($keywords as $keyword) {
                if (Str::contains($normalized, Str::lower($keyword))) {
                    $scores[$domain] += mb_strlen($keyword) > 4 ? 2 : 1;
                }
            }
        }
        arsort($scores);
        return array_key_first($scores) ?: 'general';
    }

    private function tokens(string $text): array
    {
        $clean = preg_replace('/[^\p{Arabic}a-zA-Z0-9\-\s]/u', ' ', $text);
        return collect(preg_split('/\s+/u', $clean, -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn($t) => mb_strlen($t) >= 3)
            ->unique()
            ->take(80)
            ->values()
            ->all();
    }

    private function buildReason(Architecture $architecture, string $domain, int $score): string
    {
        $categoryNames = $architecture->categories->pluck('name')->join('، ');
        return "تم ترشيح {$architecture->name} لأن خصائصها العلمية تقع ضمن مجال {$categoryNames}، وتناسب نمط المشكلة المكتشف ({$domain}). درجة المطابقة التقريبية: {$score}. راجع القيود ومتطلبات البيانات قبل التنفيذ الإنتاجي.";
    }
}
