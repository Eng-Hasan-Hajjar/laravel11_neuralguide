@extends('layouts.admin')
@section('title', 'لوحة التحكم الرئيسية')

@section('content')
{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
    $cards = [
        ['label'=>'المعماريات', 'value'=>$stats['architectures'], 'icon'=>'fa-brain', 'color'=>'cyan'],
        ['label'=>'منشور', 'value'=>$stats['published'], 'icon'=>'fa-check-circle', 'color'=>'emerald'],
        ['label'=>'المستخدمون', 'value'=>$stats['users'], 'icon'=>'fa-users', 'color'=>'purple'],
        ['label'=>'الفئات', 'value'=>$stats['categories'], 'icon'=>'fa-tags', 'color'=>'amber'],
        ['label'=>'تجارب التدريب', 'value'=>$stats['experiments'], 'icon'=>'fa-flask', 'color'=>'blue'],
        ['label'=>'قيد التشغيل', 'value'=>$stats['running'], 'icon'=>'fa-spinner', 'color'=>'orange'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-slate-400 text-sm font-medium">{{ $card['label'] }}</span>
            <div class="w-9 h-9 bg-{{ $card['color'] }}-500/15 rounded-xl flex items-center justify-center">
                <i class="fa-solid {{ $card['icon'] }} text-{{ $card['color'] }}-400 text-sm"></i>
            </div>
        </div>
        <p class="text-3xl font-black text-white">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-2 gap-6">
    {{-- Latest Experiments --}}
    <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
            <i class="fa-solid fa-flask text-cyan-400"></i> آخر تجارب التدريب
        </h3>
        @if($latestExperiments->isEmpty())
            <p class="text-slate-500 text-sm text-center py-6">لا توجد تجارب بعد</p>
        @else
        <div class="space-y-3">
            @foreach($latestExperiments as $exp)
            <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                <div>
                    <p class="font-medium text-sm">{{ $exp->name }}</p>
                    <p class="text-xs text-slate-400">{{ $exp->architecture?->name }} · {{ $exp->framework }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full
                    {{ $exp->status === 'completed' ? 'bg-emerald-500/20 text-emerald-400' :
                       ($exp->status === 'running'   ? 'bg-blue-500/20 text-blue-400' :
                       ($exp->status === 'failed'    ? 'bg-red-500/20 text-red-400' : 'bg-slate-700 text-slate-400')) }}">
                    {{ $exp->statusLabel() }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
        <a href="{{ route('training.index') }}" class="mt-4 block text-center text-sm text-cyan-400 hover:underline">
            عرض جميع التجارب →
        </a>
    </div>

    {{-- Latest Users --}}
    <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6">
        <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
            <i class="fa-solid fa-users text-purple-400"></i> آخر المستخدمين
        </h3>
        <div class="space-y-3">
            @foreach($latestUsers as $user)
            <div class="flex items-center gap-3 py-2 border-b border-white/5 last:border-0">
                <div class="w-8 h-8 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-xs font-black">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm truncate">{{ $user->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                </div>
                <span class="text-xs text-slate-500">{{ $user->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
        <a href="{{ route('admin.users.index') }}" class="mt-4 block text-center text-sm text-purple-400 hover:underline">
            إدارة المستخدمين →
        </a>
    </div>

    {{-- Latest Architectures --}}
    <div class="bg-slate-800/50 border border-white/10 rounded-2xl p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <i class="fa-solid fa-brain text-amber-400"></i> آخر المعماريات المضافة
            </h3>
            <a href="{{ route('admin.architectures.create') }}"
               class="bg-cyan-600 hover:bg-cyan-500 px-4 py-2 rounded-xl text-sm font-bold transition">
                + إضافة معمارية
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/10 text-slate-400 text-right">
                        <th class="pb-3 font-medium">الاسم</th>
                        <th class="pb-3 font-medium">السنة</th>
                        <th class="pb-3 font-medium">الصعوبة</th>
                        <th class="pb-3 font-medium">منشور</th>
                        <th class="pb-3 font-medium">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestArchitectures as $arch)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition">
                        <td class="py-3 font-medium">{{ $arch->name }}</td>
                        <td class="py-3 text-slate-400">{{ $arch->year }}</td>
                        <td class="py-3">
                            <span class="text-xs px-2 py-1 rounded-full bg-slate-700">{{ $arch->difficulty }}</span>
                        </td>
                        <td class="py-3">
                            @if($arch->is_published)
                                <i class="fa-solid fa-circle-check text-emerald-400"></i>
                            @else
                                <i class="fa-solid fa-circle-xmark text-slate-600"></i>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.architectures.edit', $arch) }}"
                                   class="text-cyan-400 hover:text-cyan-300 text-xs">تعديل</a>
                                <form action="{{ route('admin.architectures.destroy', $arch) }}" method="POST"
                                      onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-300 text-xs">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
