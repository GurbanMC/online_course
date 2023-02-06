<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthAttempt;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Course;
use App\Models\Verification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $modals = [
            ['name' => 'customers', 'total' => Customer::count()],
            ['name' => 'courses', 'total' => Course::count()],
            ['name' => 'categories', 'total' => Category::count()],
            ['name' => 'attributes', 'total' => Attribute::count()],
        ];

        return view('admin.dashboard.index')
            ->with([
                'modals' => $modals,
            ]);
    }
}
