<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    public function index()
    {
        if (Cookie::has('cart')) {
            $cartIds = explode(',', Cookie::get('cart'));
        } else {
            $cartIds = [];
        }

        $courses = [];
        foreach (array_count_values($cartIds) as $id => $count) {
            $courses[] = [
                'courses' => Course::with(['category'])
                    ->findOrFail($id),
                'count' => $count,
            ];
        }

        return view('cart.index')
            ->with([
                'courses' => $courses,
            ]);
    }


    public function add($id)
    {
        $course = Course::findOrFail($id);

        if (Cookie::has('cart')) {
            $cartIds = explode(',', Cookie::get('cart'));
            $cartIds[] = $course->id;
            Cookie::queue('cart', implode(',', $cartIds), 60 * 24 * 3);
        } else {
            Cookie::queue('cart', $course->id, 60 * 24 * 3);
        }

        return redirect()->back();
    }


    public function remove($id)
    {
        $course = Course::findOrFail($id);

        if (Cookie::has('cart')) {
            $cartIds = explode(',', Cookie::get('cart'));
            // remove elements
            Cookie::queue('cart', implode(',', $cartIds), 60 * 24 * 3);
        } else {
            Cookie::queue('cart', '', 60 * 24 * 3);
        }

        return redirect()->back();
    }
}
