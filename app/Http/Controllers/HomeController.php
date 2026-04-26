<?php
namespace App\Http\Controllers;
use App\Models\Architecture;
use App\Models\Category;
class HomeController extends Controller {
    public function index() {
        return view('home', [
            'architecturesCount' => Architecture::where('is_published', true)->count(),
            'categories' => Category::withCount('architectures')->get(),
            'featured' => Architecture::where('is_published', true)->latest()->take(6)->get(),
        ]);
    }
}
