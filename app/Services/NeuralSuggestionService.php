<?php

namespace App\Services;

use App\Models\Architecture;

class NeuralSuggestionService
{
    private array $rules = [
        'vision' => [
            'keywords'      => ['صورة','صور','تصنيف صور','رؤية','أشعة','طبي','كاميرا','وجه','كشف أجسام','image','vision','classification','detection','segmentation','medical imaging'],
            'architectures' => ['CNN','ResNet','EfficientNet','Vision Transformer','U-Net','YOLO'],
            'reason'        => 'المشكلة تحتوي على مؤشرات مرتبطة بالرؤية الحاسوبية ومعالجة الصور.',
            'weight'        => 1.5,
        ],
        'nlp' => [
            'keywords'      => ['نص','نصوص','لغة','ترجمة','شعر','تلخيص','محادثة','مراجعة','رأي','تحليل مشاعر','chat','text','nlp','sentiment','translation','summarization','language'],
            'architectures' => ['Transformer','BERT','LSTM','GPT'],
            'reason'        => 'المشكلة مرتبطة بمعالجة اللغة الطبيعية أو توليد النصوص.',
            'weight'        => 1.4,
        ],
        'time_series' => [
            'keywords'      => ['أسهم','سعر','أسعار','تنبؤ','زمنية','عملات','مبيعات','طقس','درجة حرارة','forecast','time series','stock','prediction','sequential'],
            'architectures' => ['LSTM','Transformer','RNN','GRU'],
            'reason'        => 'المشكلة تحتوي على بيانات زمنية أو تنبؤ مستقبلي.',
            'weight'        => 1.3,
        ],
        'generation' => [
            'keywords'      => ['توليد صور','توليد صورة','رسم','توليد نص','إبداع','توليد موسيقى','generate','diffusion','creative','synthesis'],
            'architectures' => ['Diffusion Model','GAN','VAE'],
            'reason'        => 'المشكلة مرتبطة بتوليد المحتوى الإبداعي.',
            'weight'        => 1.3,
        ],
        'anomaly' => [
            'keywords'      => ['احتيال','شذوذ','خلل','كشف','بنك','معاملات','anomaly','fraud','outlier','defect'],
            'architectures' => ['AutoEncoder','Graph Neural Network','Transformer'],
            'reason'        => 'المشكلة مرتبطة باكتشاف الشذوذ أو الاحتيال.',
            'weight'        => 1.2,
        ],
        'graph' => [
            'keywords'      => ['شبكات اجتماعية','علاقات','رسم بياني','مولكيول','جزيئات','graph','nodes','edges','social network','molecule'],
            'architectures' => ['Graph Neural Network','Graph Attention Network'],
            'reason'        => 'المشكلة تحتوي على بيانات ذات بنية رسوم بيانية.',
            'weight'        => 1.2,
        ],
        'reinforcement' => [
            'keywords'      => ['ألعاب','روبوت','تحكم','مكافأة','بيئة','وكيل','game','robot','agent','reward','control','reinforcement'],
            'architectures' => ['Deep Q-Network','Actor-Critic'],
            'reason'        => 'المشكلة تحتوي على عنصر القرار والمكافأة — تعلم معزز.',
            'weight'        => 1.2,
        ],
        'audio' => [
            'keywords'      => ['صوت','كلام','موسيقى','تعرف صوت','audio','speech','voice','music','recognition'],
            'architectures' => ['WaveNet','Conformer','LSTM'],
            'reason'        => 'المشكلة مرتبطة بمعالجة الصوت والكلام.',
            'weight'        => 1.2,
        ],
        'recommendation' => [
            'keywords'      => ['توصية','تنبؤ تفضيلات','نظام توصية','منتج','فيلم','recommendation','collaborative filtering','rating'],
            'architectures' => ['Neural Collaborative Filtering','Matrix Factorization'],
            'reason'        => 'المشكلة مرتبطة بأنظمة التوصية وتصفية التعاون.',
            'weight'        => 1.1,
        ],
    ];

    // ─────────────────────────────────────────────────────────
    public function suggest(string $problem): array
    {
        $text   = mb_strtolower($problem);
        $scores  = [];
        $reasons = [];
        $detectedDomain = 'general';

        foreach ($this->rules as $domain => $rule) {
            foreach ($rule['keywords'] as $keyword) {
                if (str_contains($text, mb_strtolower($keyword))) {
                    $detectedDomain = $domain;
                    $w = $rule['weight'] ?? 1.0;
                    foreach ($rule['architectures'] as $i => $name) {
                        // كلمة أولى تزن أكثر (الترتيب يعكس الأولوية)
                        $boost = $w * (1 + 0.05 * (count($rule['architectures']) - $i));
                        $scores[$name]  = ($scores[$name] ?? 60) + (int)(15 * $boost);
                        $reasons[$name] = $rule['reason'];
                    }
                }
            }
        }

        // fallback عام إذا لم تُكتشف كلمات
        if (empty($scores)) {
            $scores  = ['Transformer'=>85,'EfficientNet'=>80,'CNN'=>75,'LSTM'=>70,'ResNet'=>65];
            foreach ($scores as $name => $s) {
                $reasons[$name] = 'معمارية متعددة الاستخدامات ومناسبة لمشاكل التعلم العميق المتنوعة.';
            }
        }

        arsort($scores);
        $topNames = array_slice(array_keys($scores), 0, 5);

        // جلب النماذج من DB مع الترتيب المطلوب
        $architectures = Architecture::whereIn('name', $topNames)
            ->where('is_published', true)
            ->get()
            ->sortBy(fn($a) => array_search($a->name, $topNames))
            ->values()
            ->map(function ($arch) use ($scores, $reasons) {
                $arch->suggestion_score  = min($scores[$arch->name] ?? 75, 100);
                $arch->suggestion_reason = $reasons[$arch->name] ?? 'مناسبة للمشكلة المدخلة.';
                return $arch;
            });

        return [
            'domain'        => $detectedDomain,
            'analysis'      => 'تم تحليل وصف المشكلة وربط الكلمات المفتاحية بأنسب معماريات الشبكات العصبية عبر نظام قواعد خبير. النتائج مرتبة حسب درجة الملاءمة.',
            'architectures' => $architectures,
        ];
    }
}
