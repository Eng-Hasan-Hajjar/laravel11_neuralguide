<?php

namespace App\Http\Controllers;

use App\Models\Architecture;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Architecture $architecture)
    {
        $user = auth()->user();

        if ($user->favorites()->where('architecture_id', $architecture->id)->exists()) {
            $user->favorites()->detach($architecture->id);
            $msg = 'تمت إزالة المعمارية من المفضلة';
        } else {
            $user->favorites()->attach($architecture->id);
            $msg = 'تمت إضافة المعمارية إلى المفضلة';
        }

        return back()->with('status', $msg);
    }

    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with('categories')
            ->latest('favorites.created_at')
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }
}
