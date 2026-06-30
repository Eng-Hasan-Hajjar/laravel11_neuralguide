<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Architecture;
use App\Models\TrainingExperiment;
use App\Models\TrainingDataset;
use App\Services\PythonCodeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainingExperimentController extends Controller
{
    public function __construct(private PythonCodeGeneratorService $codeGen) {}

    // ─── قائمة تجارب المستخدم ─────────────────────────────────
    public function index()
    {
        $experiments = TrainingExperiment::with('architecture', 'dataset')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('training.index', compact('experiments'));
    }

    // ─── إنشاء تجربة جديدة ────────────────────────────────────
    public function create(Request $request)
    {
        $architectures = Architecture::where('is_published', true)->get();
        $datasets      = TrainingDataset::where('user_id', auth()->id())->get();
        $selected      = $request->architecture_id
            ? Architecture::find($request->architecture_id)
            : null;

        return view('training.create', compact('architectures', 'datasets', 'selected'));
    }

    // ─── حفظ التجربة وتوليد الكود ────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:200',
            'architecture_id' => 'required|exists:architectures,id',
            'dataset_id'      => 'nullable|exists:training_datasets,id',
            'framework'       => 'required|in:pytorch,tensorflow',
            'epochs'          => 'required|integer|min:1|max:1000',
            'batch_size'      => 'required|integer|min:1|max:2048',
            'learning_rate'   => 'required|numeric|min:0.000001|max:1',
            'optimizer'       => 'required|in:adam,sgd,adamw,rmsprop',
            'loss_function'   => 'nullable|string|max:100',
            'notes'           => 'nullable|string|max:2000',
            'custom_code'     => 'nullable|string',
        ]);

        $architecture = Architecture::findOrFail($data['architecture_id']);

        // توليد كود Python تلقائياً
        $generatedCode = $this->codeGen->generate($architecture, $data);

        $experiment = TrainingExperiment::create([
            'user_id'         => auth()->id(),
            'architecture_id' => $data['architecture_id'],
            'dataset_id'      => $data['dataset_id'] ?? null,
            'name'            => $data['name'],
            'framework'       => $data['framework'],
            'hyperparameters' => [
                'epochs'        => $data['epochs'],
                'batch_size'    => $data['batch_size'],
                'learning_rate' => $data['learning_rate'],
                'optimizer'     => $data['optimizer'],
                'loss_function' => $data['loss_function'] ?? 'cross_entropy',
            ],
            'generated_code'  => $generatedCode,
            'custom_code'     => $data['custom_code'] ?? null,
            'notes'           => $data['notes'] ?? null,
            'status'          => 'draft',
        ]);

        return redirect()->route('training.show', $experiment)
            ->with('status', 'تم إنشاء التجربة وتوليد الكود بنجاح');
    }

    // ─── عرض التجربة وكودها ───────────────────────────────────
    public function show(TrainingExperiment $experiment)
    {
        $this->authorize('view', $experiment);
        $experiment->load('architecture', 'dataset', 'runs');
        return view('training.show', compact('experiment'));
    }

    // ─── تعديل التجربة ────────────────────────────────────────
    public function edit(TrainingExperiment $experiment)
    {
        $this->authorize('update', $experiment);
        $architectures = Architecture::where('is_published', true)->get();
        $datasets      = TrainingDataset::where('user_id', auth()->id())->get();
        return view('training.edit', compact('experiment', 'architectures', 'datasets'));
    }

    public function update(Request $request, TrainingExperiment $experiment)
    {
        $this->authorize('update', $experiment);
        $data = $request->validate([
            'name'          => 'required|string|max:200',
            'framework'     => 'required|in:pytorch,tensorflow',
            'epochs'        => 'required|integer|min:1|max:1000',
            'batch_size'    => 'required|integer|min:1|max:2048',
            'learning_rate' => 'required|numeric|min:0.000001|max:1',
            'optimizer'     => 'required|in:adam,sgd,adamw,rmsprop',
            'loss_function' => 'nullable|string|max:100',
            'notes'         => 'nullable|string|max:2000',
            'custom_code'   => 'nullable|string',
        ]);

        $architecture = $experiment->architecture;
        $generatedCode = $this->codeGen->generate($architecture, $data);

        $experiment->update([
            'name'      => $data['name'],
            'framework' => $data['framework'],
            'hyperparameters' => [
                'epochs'        => $data['epochs'],
                'batch_size'    => $data['batch_size'],
                'learning_rate' => $data['learning_rate'],
                'optimizer'     => $data['optimizer'],
                'loss_function' => $data['loss_function'] ?? 'cross_entropy',
            ],
            'generated_code' => $generatedCode,
            'custom_code'    => $data['custom_code'] ?? null,
            'notes'          => $data['notes'] ?? null,
        ]);

        return redirect()->route('training.show', $experiment)->with('status', 'تم التحديث بنجاح');
    }

    public function destroy(TrainingExperiment $experiment)
    {
        $this->authorize('delete', $experiment);
        $experiment->delete();
        return redirect()->route('training.index')->with('status', 'تم حذف التجربة');
    }

    // ─── تنزيل الكود كملف .py ────────────────────────────────
    public function downloadCode(TrainingExperiment $experiment)
    {
        $this->authorize('view', $experiment);
        $code     = $experiment->custom_code ?? $experiment->generated_code;
        $filename = Str::slug($experiment->name) . '.py';

        return response($code, 200, [
            'Content-Type'        => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    // ─── تحديث الكود المخصص (AJAX) ───────────────────────────
    public function updateCode(Request $request, TrainingExperiment $experiment)
    {
        $this->authorize('update', $experiment);
        $request->validate(['custom_code' => 'required|string']);
        $experiment->update(['custom_code' => $request->custom_code]);
        return response()->json(['ok' => true]);
    }

    // ─── تشغيل التجربة (محاكاة / قائمة انتظار) ───────────────
    public function run(TrainingExperiment $experiment)
    {
        $this->authorize('update', $experiment);

        if ($experiment->status === 'running') {
            return back()->withErrors(['error' => 'التجربة قيد التشغيل بالفعل']);
        }

        $run = $experiment->runs()->create([
            'status'     => 'queued',
            'started_at' => now(),
        ]);

        $experiment->update(['status' => 'running']);

        // Dispatch job for background execution
        // \App\Jobs\RunTrainingJob::dispatch($experiment, $run);

        return redirect()->route('training.show', $experiment)
            ->with('status', 'تم إرسال التجربة لقائمة التنفيذ');
    }
}
