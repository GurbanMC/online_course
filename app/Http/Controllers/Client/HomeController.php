<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')
            ->with('parent')
            ->withCount([
                'courses as courses_count' => function ($query) {
                    $query->where('category_id', '>', 0);
                }
            ])
            ->get();

        return view('client.home.index')
            ->with([
                'categories' => $categories
            ]);

    }
}
