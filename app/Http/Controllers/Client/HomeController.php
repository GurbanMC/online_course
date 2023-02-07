<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Product;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')
            ->with('parent', 'courses')
            ->get();

        return view('client.home.index')
            ->with([
                'categories' => $categories
            ]);

    }


    public function language($locale)
    {
        switch ($locale) {
            case 'tm':
                session()->put('locale', 'tm');
                return redirect()->back();
                break;
            case 'en':
                session()->put('locale', 'en');
                return redirect()->back();
                break;
            default:
                return redirect()->back();
        }
    }
}
