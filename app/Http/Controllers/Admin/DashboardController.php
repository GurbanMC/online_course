<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthAttempt;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Course;
use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $modals = [
            ['name' => 'customers', 'total' => Customer::count(),'color' => '#2298F1',],
            ['name' => 'courses', 'total' => Course::count(),'color' => '#66B92E    ',],
            ['name' => 'categories', 'total' => Category::count(),'color'  => '#DA932C',],
            ['name' => 'attributes', 'total' => Attribute::count(),'color'  => '#D65B4A',],
        ];


        return view('admin.dashboard.index')
            ->with([
                'modals' => $modals,
            ]);

    }
}
