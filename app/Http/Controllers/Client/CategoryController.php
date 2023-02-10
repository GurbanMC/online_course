<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        return
        $objs = Category::orderBy('sort_order')
            ->with('parent')
            ->withCount([
                'courses as courses_count' => function ($query) {
                    $query->where('category_id', '>', 0);
                }
            ])

            ->get();

        return view('client.category.index')
            ->with([
                'objs' => $objs
            ]);
    }
}
