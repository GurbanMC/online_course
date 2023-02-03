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
//        CATEGORY INDEX
//        $response = Http::get('http://127.0.0.1:8000/api/v1/categories', [
//            'api_id' => 'c11',
//        ]);

//        BRAND INDEX
//        $response = Http::get('http://127.0.0.1:8000/api/v1/brands', [
//            'api_id' => 'c11',
//        ]);

//        BRAND STORE
//        $response = Http::post('http://127.0.0.1:8000/api/v1/brands', [
//            'api_id' => 'c11',
//            'name' => 'Gala',
//        ]);

//        BRAND UPDATE
//        $response = Http::put('http://127.0.0.1:8000/api/v1/brands/13', [
//            'api_id' => 'c11',
//            'name' => 'Ýeňiş',
//        ]);

//        return $response;

        return view('client.home.index');
    }
}
