<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Architecture;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArchitectureAdminController extends Controller {
    public function index(){ return view('admin.architectures.index', ['architectures'=>Architecture::latest()->paginate(20)]); }
    public function create(){ return view('admin.architectures.form', ['architecture'=>new Architecture(), 'categories'=>Category::all()]); }
    public function store(Request $request){ $data=$this->validateData($request); $data['slug']=Str::slug($data['name']); $data['frameworks']=$this->csv($request->frameworks); $data['tags']=$this->csv($request->tags); $a=Architecture::create($data); $a->categories()->sync($request->input('category_ids',[])); return redirect()->route('admin.architectures.index')->with('status','تمت الإضافة بنجاح'); }
    public function edit(Architecture $architecture){ return view('admin.architectures.form', ['architecture'=>$architecture, 'categories'=>Category::all()]); }
    public function update(Request $request, Architecture $architecture){ $data=$this->validateData($request); $data['slug']=Str::slug($data['name']); $data['frameworks']=$this->csv($request->frameworks); $data['tags']=$this->csv($request->tags); $architecture->update($data); $architecture->categories()->sync($request->input('category_ids',[])); return redirect()->route('admin.architectures.index')->with('status','تم التحديث'); }
    public function destroy(Architecture $architecture){ $architecture->delete(); return back()->with('status','تم الحذف'); }
    private function validateData(Request $r): array { return $r->validate(['name'=>'required|string|max:255','short_description'=>'required|string','description'=>'required|string','year'=>'nullable|integer|min:1940|max:2100','paper_title'=>'nullable|string|max:255','paper_url'=>'nullable|url','arxiv_url'=>'nullable|url','difficulty'=>'required|in:beginner,intermediate,advanced,research','data_requirement'=>'nullable|string','compute_requirement'=>'nullable|string','best_for'=>'nullable|string','limitations'=>'nullable|string','recommended_settings'=>'nullable|string','pytorch_example'=>'nullable|string','tensorflow_example'=>'nullable|string','is_published'=>'nullable|boolean']); }
    private function csv(?string $v): array { return collect(explode(',', (string)$v))->map(fn($x)=>trim($x))->filter()->values()->all(); }
}
