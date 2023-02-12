<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Verification;
use Carbon\Carbon;
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
            ->take(5)
            ->get();
        $objs = Course::where('created_at', '>=', Carbon::today()->subMonth()->toDateString())
            ->with(['category:id'])
            ->inRandomOrder()
            ->take(50)
            ->get([
                'id', 'category_id', 'name_tm','full_name_tm', 'slug', 'price', 'created_at'
            ]);

        return view('client.home.index')
            ->with([
                'objs' => $objs,
                'categories' => $categories,
        ]);
    }
}
