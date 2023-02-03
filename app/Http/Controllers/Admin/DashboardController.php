<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthAttempt;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use App\Models\Verification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $modals = [
            ['name' => 'orders', 'total' => Order::count()],
            ['name' => 'customers', 'total' => Customer::count()],
            ['name' => 'products', 'total' => Product::count()],
            ['name' => 'categories', 'total' => Category::count()],
            ['name' => 'brands', 'total' => Brand::count()],
            ['name' => 'attributes', 'total' => Attribute::count()],
        ];

        $pendingOrders = Order::where('status', 0)
            ->orderBy('id', 'desc')
            ->take(10)
            ->with(['location', 'customer'])
            ->get();

        $acceptedOrders = Order::where('status', 1)
            ->orderBy('id', 'desc')
            ->take(10)
            ->with(['location', 'customer'])
            ->get();

        $sentOrders = Order::where('status', 2)
            ->orderBy('id', 'desc')
            ->take(10)
            ->with(['location', 'customer'])
            ->get();

        $pendingOrdersCount = Order::where('status', 0)
            ->count();

        $acceptedOrdersCount = Order::where('status', 1)
            ->count();

        $sentOrdersCount = Order::where('status', 2)
            ->count();

        return view('admin.dashboard.index')
            ->with([
                'modals' => $modals,
                'pendingOrders' => $pendingOrders,
                'acceptedOrders' => $acceptedOrders,
                'sentOrders' => $sentOrders,
                'pendingOrdersCount' => $pendingOrdersCount,
                'acceptedOrdersCount' => $acceptedOrdersCount,
                'sentOrdersCount' => $sentOrdersCount,
            ]);
    }
}
