<?php

namespace App\Services;

use App\Models\Architecture;

class NeuralSuggestionService
{
    public function suggest(string $problem): array
    {
        $text = mb_strtolower($problem);

        $rules = [
            'vision' => [
                'keywords' => ['صورة', 'صور', 'تصنيف صور', 'رؤية', 'أشعة', 'طبي', 'image', 'vision', 'classification'],
                'architectures' => ['CNN', 'ResNet', 'EfficientNet', 'Vision Transformer', 'U-Net', 'YOLO'],
                'reason' => 'المشكلة تحتوي على مؤشرات مرتبطة بالرؤية الحاسوبية ومعالجة الصور.',
            ],

            'nlp' => [
                'keywords' => ['نص', 'نصوص', 'لغة', 'ترجمة', 'شعر', 'تلخيص', 'محادثة', 'chat', 'text', 'nlp'],
                'architectures' => ['Transformer', 'LSTM', 'BERT'],
                'reason' => 'المشكلة مرتبطة بمعالجة اللغة الطبيعية أو توليد النصوص.',
            ],

            'time_series' => [
                'keywords' => ['أسهم', 'سعر', 'أسعار', 'تنبؤ', 'زمنية', 'عملات', 'مبيعات', 'forecast', 'time series'],
                'architectures' => ['LSTM', 'Transformer', 'RNN'],
                'reason' => 'المشكلة تحتوي على بيانات زمنية أو تنبؤ مستقبلي.',
            ],

            'fraud' => [
                'keywords' => ['احتيال', 'بنك', 'معاملات', 'كشف شذوذ', 'anomaly', 'fraud'],
                'architectures' => ['AutoEncoder', 'Graph Neural Network', 'Transformer'],
                'reason' => 'المشكلة مرتبطة باكتشاف الشذوذ أو الاحتيال.',
            ],

            'generation' => [
                'keywords' => ['توليد صور', 'توليد صورة', 'رسم', 'تصميم', 'generate image', 'diffusion'],
                'architectures' => ['Diffusion Model', 'GAN', 'VAE'],
                'reason' => 'المشكلة مرتبطة بتوليد الصور أو المحتوى الإبداعي.',
            ],

            'graph' => [
                'keywords' => ['شبكات اجتماعية', 'علاقات', 'رسم بياني', 'graph', 'nodes', 'edges'],
                'architectures' => ['Graph Neural Network'],
                'reason' => 'المشكلة تحتوي على علاقات أو بنية رسوم بيانية.',
            ],
        ];

        $scores = [];
        $reasons = [];
        $detectedDomain = 'general';

        foreach ($rules as $domain => $rule) {
            foreach ($rule['keywords'] as $keyword) {
                if (str_contains($text, mb_strtolower($keyword))) {
                    $detectedDomain = $domain;

                    foreach ($rule['architectures'] as $name) {
                        $scores[$name] = ($scores[$name] ?? 60) + 15;
                        $reasons[$name] = $rule['reason'];
                    }
                }
            }
        }

        if (empty($scores)) {
            $scores = [
                'Transformer' => 85,
                'EfficientNet' => 80,
                'CNN' => 75,
                'LSTM' => 70,
            ];

            foreach ($scores as $name => $score) {
                $reasons[$name] = 'تم اختيار هذه المعمارية كخيار عام مناسب للعديد من مشكلات التعلم العميق.';
            }
        }

        arsort($scores);

        $names = array_slice(array_keys($scores), 0, 5);

        $architectures = Architecture::query()
            ->whereIn('name', $names)
            ->get()
            ->sortBy(fn ($architecture) => array_search($architecture->name, $names))
            ->values()
            ->map(function ($architecture) use ($scores, $reasons) {
                $architecture->suggestion_score = min($scores[$architecture->name] ?? 80, 100);
                $architecture->suggestion_reason = $reasons[$architecture->name] ?? 'مناسبة للمشكلة المدخلة.';

                return $architecture;
            });

        return [
            'domain' => $detectedDomain,
            'analysis' => 'تم تحليل وصف المشكلة وربط الكلمات المفتاحية بأقرب معماريات الشبكات العصبية المناسبة. يعتمد هذا الترشيح على نظام قواعد أولي قابل للتطوير لاحقًا باستخدام نموذج لغوي متقدم.',
            'architectures' => $architectures,
        ];
    }
}