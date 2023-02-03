<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'has_orders' => 'nullable|boolean',
            'has_addresses' => 'nullable|boolean',
            'has_favorites' => 'nullable|boolean',
        ]);
        $q = $request->q ?: null;
        $f_hasOrders = $request->has('has_orders') ? $request->has_order : null;
        $f_hasAddresses = $request->has('has_addresses') ? $request->has_addresses : null;
        $f_hasFavorites = $request->has('has_favorites') ? $request->has_favorites : null;

        $objs = Customer::when($q, function ($query, $q) {
            return $query->where(function ($query) use ($q) {
                $query->orWhere('name', 'like', '%' . $q . '%');
                $query->orWhere('username', 'like', '%' . $q . '%');
            });
        })
            ->when(isset($f_hasOrders), function ($query) use ($f_hasOrders) {
                if ($f_hasOrders) {
                    return $query->has('orders');
                } else {
                    return $query->doesntHave('orders');
                }
            })
            ->when(isset($f_hasAddresses), function ($query) use ($f_hasAddresses) {
                if ($f_hasAddresses) {
                    return $query->has('addresses');
                } else {
                    return $query->doesntHave('addresses');
                }
            })
            ->when(isset($f_hasFavorites), function ($query) use ($f_hasFavorites) {
                if ($f_hasFavorites) {
                    return $query->has('favorites');
                } else {
                    return $query->doesntHave('favorites');
                }
            })
            ->orderBy('id', 'desc')
            ->withCount(['orders', 'orderProducts', 'addresses', 'favorites'])
            ->paginate(50)
            ->withQueryString();

        return view('admin.customer.index')
            ->with([
                'objs' => $objs,
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $objs = Customer::findOrFail($id);

        return view('admin.customer.edit')
            ->with([
                'objs' => $objs
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
