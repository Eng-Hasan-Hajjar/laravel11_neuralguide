@extends('layouts.app')
@section('title','إدارة المعماريات')
@section('content')
<div class="flex justify-between items-center mb-6"><h1 class="text-3xl font-extrabold">إدارة المعماريات</h1><a class="rounded-xl bg-cyan-600 px-4 py-2 font-bold" href="{{ route('admin.architectures.create') }}">إضافة</a></div>
@if(session('status'))<p class="rounded-xl bg-green-700 p-3 mb-4">{{ session('status') }}</p>@endif
<table class="w-full text-right bg-white/10 rounded-2xl overflow-hidden"><thead><tr class="bg-white/10"><th class="p-3">الاسم</th><th>السنة</th><th>منشور</th><th></th></tr></thead><tbody>
@foreach($architectures as $a)<tr class="border-t border-white/10"><td class="p-3">{{ $a->name }}</td><td>{{ $a->year }}</td><td>{{ $a->is_published?'نعم':'لا' }}</td><td><a href="{{ route('admin.architectures.edit',$a) }}">تعديل</a></td></tr>@endforeach
</tbody></table>
{{ $architectures->links() }}
@endsection
