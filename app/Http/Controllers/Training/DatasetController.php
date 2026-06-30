<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\TrainingDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = TrainingDataset::where('user_id', auth()->id())
            ->latest()->paginate(15);
        return view('training.datasets.index', compact('datasets'));
    }

    public function create()
    {
        return view('training.datasets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'type'        => 'required|in:csv,images,json,custom',
            'file'        => 'required|file|max:102400', // 100MB
            'task_type'   => 'nullable|string|max:100',
        ]);

        $file = $request->file('file');
        $path = $file->store('datasets/' . auth()->id(), 'local');

        $dataset = TrainingDataset::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'description' => $request->description,
            'type'        => $request->type,
            'task_type'   => $request->task_type,
            'file_path'   => $path,
            'file_size'   => $file->getSize(),
            'file_name'   => $file->getClientOriginalName(),
            'meta'        => [],
        ]);

        return redirect()->route('training.datasets.show', $dataset)
            ->with('status', 'تم رفع مجموعة البيانات بنجاح');
    }

    public function show(TrainingDataset $dataset)
    {
        $this->authorize('view', $dataset);
        return view('training.datasets.show', compact('dataset'));
    }

    public function destroy(TrainingDataset $dataset)
    {
        $this->authorize('delete', $dataset);
        Storage::disk('local')->delete($dataset->file_path);
        $dataset->delete();
        return redirect()->route('training.datasets.index')->with('status', 'تم حذف مجموعة البيانات');
    }
}
