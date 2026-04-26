<?php
namespace App\Http\Controllers;
use App\Models\Architecture;
use App\Models\Category;
use Illuminate\Http\Request;
class ArchitectureController extends Controller {
    public function index(Request $request) {
        $architectures = Architecture::with('categories')
            ->where('is_published', true)
            ->when($request->q, fn($q,$v)=>$q->where(fn($qq)=>$qq->where('name','like',"%$v%")->orWhere('description','like',"%$v%")->orWhere('best_for','like',"%$v%")))
            ->when($request->difficulty, fn($q,$v)=>$q->where('difficulty',$v))
            ->when($request->category, fn($q,$v)=>$q->whereHas('categories', fn($c)=>$c->where('slug',$v)))
            ->latest()->paginate(12)->withQueryString();
        return view('architectures.index', ['architectures'=>$architectures,'categories'=>Category::all()]);
    }
    public function show(Architecture $architecture) {
        abort_unless($architecture->is_published, 404);
        $architecture->load('categories','comments.user');
        return view('architectures.show', compact('architecture'));
    }
}
