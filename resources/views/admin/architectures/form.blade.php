@extends('layouts.app')
@section('title','نموذج معمارية')
@section('content')
<h1 class="text-3xl font-extrabold mb-6">{{ $architecture->exists ? 'تعديل' : 'إضافة' }} معمارية</h1>
<form method="POST" action="{{ $architecture->exists ? route('admin.architectures.update',$architecture) : route('admin.architectures.store') }}" class="grid gap-4 bg-white text-slate-900 rounded-3xl p-6">
@csrf @if($architecture->exists) @method('PUT') @endif
<input name="name" value="{{ old('name',$architecture->name) }}" placeholder="الاسم" class="rounded-xl">
<textarea name="short_description" placeholder="وصف قصير" class="rounded-xl">{{ old('short_description',$architecture->short_description) }}</textarea>
<textarea name="description" rows="5" placeholder="الوصف العلمي" class="rounded-xl">{{ old('description',$architecture->description) }}</textarea>
<div class="grid md:grid-cols-3 gap-3"><input name="year" value="{{ old('year',$architecture->year) }}" placeholder="السنة" class="rounded-xl"><select name="difficulty" class="rounded-xl">@foreach(['beginner','intermediate','advanced','research'] as $d)<option value="{{ $d }}" @selected(old('difficulty',$architecture->difficulty)==$d)>{{ $d }}</option>@endforeach</select><label><input type="checkbox" name="is_published" value="1" @checked(old('is_published',$architecture->is_published))> منشور</label></div>
<div class="grid md:grid-cols-2 gap-3"><input name="paper_title" value="{{ old('paper_title',$architecture->paper_title) }}" placeholder="عنوان الورقة" class="rounded-xl"><input name="arxiv_url" value="{{ old('arxiv_url',$architecture->arxiv_url) }}" placeholder="arXiv URL" class="rounded-xl"></div>
<input name="frameworks" value="{{ old('frameworks', implode(',', $architecture->frameworks ?? [])) }}" placeholder="frameworks مفصولة بفواصل" class="rounded-xl">
<input name="tags" value="{{ old('tags', implode(',', $architecture->tags ?? [])) }}" placeholder="tags مفصولة بفواصل" class="rounded-xl">
<textarea name="best_for" placeholder="مناسب لـ" class="rounded-xl">{{ old('best_for',$architecture->best_for) }}</textarea>
<textarea name="limitations" placeholder="القيود" class="rounded-xl">{{ old('limitations',$architecture->limitations) }}</textarea>
<textarea name="recommended_settings" placeholder="الإعدادات" class="rounded-xl">{{ old('recommended_settings',$architecture->recommended_settings) }}</textarea>
<textarea name="pytorch_example" rows="5" placeholder="PyTorch" class="rounded-xl">{{ old('pytorch_example',$architecture->pytorch_example) }}</textarea>
<textarea name="tensorflow_example" rows="5" placeholder="TensorFlow" class="rounded-xl">{{ old('tensorflow_example',$architecture->tensorflow_example) }}</textarea>
<div class="grid md:grid-cols-3 gap-2">@foreach($categories as $c)<label><input type="checkbox" name="category_ids[]" value="{{ $c->id }}" @checked(in_array($c->id, old('category_ids',$architecture->categories?->pluck('id')->all() ?? [])))> {{ $c->name }}</label>@endforeach</div>
<button class="rounded-xl bg-slate-950 text-white py-3 font-bold">حفظ</button>
</form>
@endsection
