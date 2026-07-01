<?php

namespace App\Http\Controllers;

use App\Models\Architecture;
use App\Models\ResearchNote;
use Illuminate\Http\Request;

class ResearchNoteController extends Controller
{
    public function index()
    {
        $notes = auth()->user()->researchNotes()->with('architecture')->latest()->paginate(15);
        return view('notes.index', compact('notes'));
    }

    public function create(Request $request)
    {
        $architectures = Architecture::where('is_published', true)->get(['id','name']);
        $selected = $request->architecture_id;
        return view('notes.create', compact('architectures', 'selected'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string|max:200',
            'body'            => 'required|string|min:10',
            'architecture_id' => 'nullable|exists:architectures,id',
            'visibility'      => 'required|in:private,public',
        ]);

        ResearchNote::create([
            'user_id' => auth()->id(),
            ...$data,
        ]);

        return redirect()->route('notes.index')->with('status', 'تم حفظ الملاحظة');
    }

    public function show(ResearchNote $note)
    {
        abort_unless($note->visibility === 'public' || auth()->id() === $note->user_id, 403);
        $note->load('architecture', 'user');
        return view('notes.show', compact('note'));
    }

    public function edit(ResearchNote $note)
    {
        abort_unless(auth()->id() === $note->user_id, 403);
        $architectures = Architecture::where('is_published', true)->get(['id','name']);
        return view('notes.edit', compact('note', 'architectures'));
    }

    public function update(Request $request, ResearchNote $note)
    {
        abort_unless(auth()->id() === $note->user_id, 403);
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'body'       => 'required|string|min:10',
            'visibility' => 'required|in:private,public',
        ]);
        $note->update($data);
        return redirect()->route('notes.show', $note)->with('status', 'تم التحديث');
    }

    public function destroy(ResearchNote $note)
    {
        abort_unless(auth()->id() === $note->user_id || auth()->user()->role === 'admin', 403);
        $note->delete();
        return redirect()->route('notes.index')->with('status', 'تم الحذف');
    }
}
