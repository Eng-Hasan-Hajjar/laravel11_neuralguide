<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        return view('dashboard', [
            'suggestions' => $user->suggestions()
                ->latest()
                ->with('architectures')
                ->paginate(10),
            'favorites'   => $user->favorites()
                ->latest('favorites.created_at')
                ->take(8)
                ->get(),
            'notes'       => $user->researchNotes()
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
