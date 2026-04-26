<?php
namespace Database\Seeders;
use App\Models\Architecture;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email'=>'admin@neuralguide.test'], ['name'=>'NeuralGuide Admin','password'=>Hash::make('password'),'role'=>'admin']);
        $categories = collect([
            ['الرؤية الحاسوبية','vision','تصنيف الصور، الكشف، التقسيم، الطب'],
            ['معالجة اللغة الطبيعية','nlp','النصوص العربية، الترجمة، التلخيص، المحادثة'],
            ['السلاسل الزمنية','time_series','التنبؤ والبيانات المتتابعة'],
            ['البيانات الجدولية','tabular','الاحتيال، الائتمان، تصنيف العملاء'],
            ['النماذج التوليدية','generative','GAN, VAE, Diffusion'],
            ['الرسوم البيانية','graph','GNN والعلاقات والعقد'],
            ['التعلم المعزز','reinforcement','الروبوتات، الألعاب، التحكم'],
            ['أنظمة التوصية','recommendation','ترتيب المنتجات والمحتوى'],
            ['الصوت والكلام','audio','التعرف على الكلام والتصنيف الصوتي'],
        ])->mapWithKeys(fn($c)=>[$c[1]=>Category::firstOrCreate(['slug'=>$c[1]], ['name'=>$c[0],'description'=>$c[2]])]);

        $items = $this->architectures();
        foreach ($items as $item) {
            $cats = $item['categories']; unset($item['categories']);
            $item['slug'] = Str::slug($item['name']);
            $arch = Architecture::updateOrCreate(['slug'=>$item['slug']], $item);
            $arch->categories()->sync(collect($cats)->map(fn($slug)=>$categories[$slug]->id)->all());
        }
    }

    private function code(string $name): array
    {
        return [
            "pytorch_example" => "import torch\nimport torch.nn as nn\n\nclass {$name}Model(nn.Module):\n    def __init__(self, in_dim=128, out_dim=10):\n        super().__init__()\n        self.net = nn.Sequential(nn.Linear(in_dim,256), nn.ReLU(), nn.Dropout(0.2), nn.Linear(256,out_dim))\n    def forward(self, x):\n        return self.net(x)\n\nmodel = {$name}Model()\n",
            "tensorflow_example" => "import tensorflow as tf\nmodel = tf.keras.Sequential([\n    tf.keras.layers.Input(shape=(128,)),\n    tf.keras.layers.Dense(256, activation='relu'),\n    tf.keras.layers.Dropout(0.2),\n    tf.keras.layers.Dense(10, activation='softmax')\n])\nmodel.compile(optimizer='adam', loss='sparse_categorical_crossentropy', metrics=['accuracy'])\n",
        ];
    }

    private function architectures(): array
    {
        $base = [
            ['CNN','vision',1998,'شبكات التفاف كلاسيكية لمعالجة الصور','ممتازة عندما تكون البيانات صورًا ذات أنماط محلية.','beginner'],
            ['ResNet','vision',2015,'شبكة عميقة تستخدم الوصلات المتبقية لتسهيل التدريب','خيار قوي لتصنيف الصور واستخراج السمات.','intermediate'],
            ['EfficientNet','vision',2019,'توازن مدروس بين العمق والعرض والدقة','مناسب عندما تريد دقة عالية مع كلفة حسابية أقل.','intermediate'],
            ['U-Net','vision',2015,'معمارية encoder-decoder للتقسيم الدقيق','شائعة في الصور الطبية وتحديد المناطق.','intermediate'],
            ['YOLO','vision',2016,'كشف أجسام سريع في الزمن الحقيقي','مناسب للمراقبة والروبوتات والتطبيقات الفورية.','intermediate'],
            ['Vision Transformer','vision',2020,'استخدام آلية الانتباه مع الصور','مناسب للبيانات الكبيرة ونقل التعلم.','advanced'],
            ['RNN','time_series',1986,'شبكات متكررة للبيانات المتتابعة','أساس تعليمي للسلاسل الزمنية والنصوص القصيرة.','beginner'],
            ['LSTM','time_series',1997,'تحافظ على الذاكرة طويلة المدى في التسلسلات','مناسبة للتنبؤ والمجسات والنصوص.','intermediate'],
            ['GRU','time_series',2014,'بديل أبسط وأسرع من LSTM','جيدة عندما تكون البيانات محدودة أو التدريب سريعًا مطلوبًا.','intermediate'],
            ['Temporal Convolutional Network','time_series',2018,'التفاف سببي للسلاسل الزمنية','مناسب للتنبؤ المستقر مع توازي أفضل.','advanced'],
            ['Transformer','nlp',2017,'معمارية الانتباه الذاتي الحديثة','أساس معظم نماذج اللغة والترجمة والتلخيص.','advanced'],
            ['BERT','nlp',2018,'تمثيلات ثنائية الاتجاه لفهم اللغة','ممتاز للتصنيف، الأسئلة، واستخراج الكيانات.','intermediate'],
            ['GPT-style Decoder','nlp',2018,'مولد نصوص يعتمد decoder-only transformer','مناسب للتوليد والمحادثة وإكمال النص.','advanced'],
            ['Seq2Seq with Attention','nlp',2015,'ترجمة وتوليد تسلسلات مع انتباه','جيد للتعلم وفهم الترجمة والتلخيص.','intermediate'],
            ['TabNet','tabular',2019,'شبكة عميقة مخصصة للبيانات الجدولية مع انتباه','مناسبة للبيانات المصرفية والائتمانية.','intermediate'],
            ['MLP','tabular',1958,'شبكة تغذية أمامية عامة','خط أساس قوي للبيانات المنظمة بعد المعالجة.','beginner'],
            ['Autoencoder','generative',1986,'ضغط وتمثيل وإعادة بناء البيانات','مناسب لاكتشاف الشذوذ وتقليل الأبعاد.','beginner'],
            ['Variational Autoencoder','generative',2013,'نموذج احتمالي لتوليد عينات جديدة','مناسب للتمثيلات الكامنة والتوليد الخفيف.','advanced'],
            ['GAN','generative',2014,'مولد ومميز في تدريب تنافسي','مناسب لتوليد الصور وتحسينها لكنه صعب التدريب.','advanced'],
            ['Diffusion Model','generative',2020,'توليد تدريجي بإزالة الضوضاء','قوي جدًا لتوليد الصور والصوت والفيديو.','research'],
            ['Graph Convolutional Network','graph',2016,'تعلم على العقد والحواف','مناسب للشبكات الاجتماعية والجزيئات.','advanced'],
            ['Graph Attention Network','graph',2018,'انتباه على الجيران في الرسم البياني','يفيد عند اختلاف أهمية العلاقات.','advanced'],
            ['Deep Q-Network','reinforcement',2015,'تعلم معزز بقيم Q عميقة','مناسب للألعاب والقرارات المنفصلة.','advanced'],
            ['Actor-Critic','reinforcement',2016,'يجمع بين سياسة وقيمة','مناسب للتحكم والروبوتات.','advanced'],
            ['Matrix Factorization','recommendation',2006,'تحليل مصفوفة المستخدم-العنصر','خط أساس لأنظمة التوصية.','beginner'],
            ['Neural Collaborative Filtering','recommendation',2017,'توصية عميقة بتفاعلات غير خطية','مناسب لمنصات المحتوى والمنتجات.','intermediate'],
            ['WaveNet','audio',2016,'التفاف سببي مولد للصوت','مناسب لتوليد الكلام والصوت.','advanced'],
            ['DeepSpeech','audio',2014,'تعرف على الكلام بنهاية إلى نهاية','مناسب لبناء ASR أولي.','advanced'],
            ['Conformer','audio',2020,'يجمع attention وconvolution للصوت','قوي للتعرف على الكلام الحديث.','research'],
            ['Siamese Network','vision',1993,'تعلم التشابه بين مدخلين','مناسب للتحقق من الوجوه والتطابق.','intermediate'],
            ['Capsule Network','vision',2017,'يمثل العلاقات المكانية عبر كبسولات','مفيد بحثيًا لفهم البنية الهندسية.','research'],
            ['Neural ODE','time_series',2018,'نمذجة مستمرة بالمعادلات التفاضلية','مناسب للبيانات العلمية غير المنتظمة.','research'],
        ];
        return array_map(function($x){
            [$name,$cat,$year,$short,$best,$difficulty]=$x; $code=$this->code(str_replace(['-',' '],'',$name));
            return array_merge([
                'name'=>$name,'short_description'=>$short,'description'=>$short.' تعتمد هذه المعمارية على مبادئ تعلم عميق موثقة وتستخدم كخيار عملي أو بحثي بحسب حجم البيانات والمتطلبات.',
                'year'=>$year,'paper_title'=>$name.' original / related paper','paper_url'=>'https://arxiv.org/','arxiv_url'=>'https://arxiv.org/',
                'difficulty'=>$difficulty,'data_requirement'=> $difficulty==='beginner'?'من مئات إلى آلاف العينات':'من آلاف إلى ملايين العينات حسب المهمة',
                'compute_requirement'=> $difficulty==='research'?'GPU/TPU قوي وتجارب متعددة':'GPU متوسط أو بيئة Colab غالبًا تكفي',
                'best_for'=>$best,'limitations'=>'قد تتطلب ضبطًا للمعاملات وتنظيفًا جيدًا للبيانات وتقييمًا صارمًا لتجنب فرط التخصيص.',
                'frameworks'=>['PyTorch','TensorFlow/Keras'],'recommended_settings'=>"optimizer: AdamW\nlearning_rate: 1e-3 to 3e-5\nbatch_size: 16-128\nearly_stopping: true",
                'tags'=>[$cat,$name],'is_published'=>true,'categories'=>[$cat],
            ], $code);
        }, $base);
    }
}
