<?php

namespace App\Http\Controllers\Client;

use App\Models\Attribute;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Course;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'u' => 'nullable|array', // users
            'u.*' => 'nullable|integer|min:0|distinct',
            'c' => 'nullable|array', // categories
            'c.*' => 'nullable|integer|min:0|distinct',
            'v' => 'nullable|array', // values
            'v.*' => 'nullable|array',
            'v.*.*' => 'nullable|integer|min:0|distinct',
            'has_discount' => 'nullable|boolean',
            'has_credit' => 'nullable|boolean',
        ]);
        $q = $request->q ?: null;
        $f_users = $request->has('u') ? $request->u : [];
        $f_categories = $request->has('c') ? $request->c : [];
        $f_values = $request->has('v') ? $request->v : [];
        $f_inStock = $request->has('in_stock') ? $request->in_stock : null;
        $f_hasDiscount = $request->has('has_discount') ? $request->has_discount : null;
        $f_hasCredit = $request->has('has_credit') ? $request->has_credit : null;

        $courses = Course::when($q, function ($query, $q) {
            return $query->where(function ($query) use ($q) {
                $query->orWhere('full_name_tm', 'like', '%' . $q . '%');
                $query->orWhere('full_name_en', 'like', '%' . $q . '%');
                $query->orWhere('slug', 'like', '%' . $q . '%');
            });
        })
            ->when($f_users, function ($query, $f_users) {
                $query->whereIn('user_id', $f_users);
            })
            ->when($f_categories, function ($query, $f_categories) {
                $query->whereIn('category_id', $f_categories);
            })
            ->when($f_values, function ($query, $f_values) {
                return $query->where(function ($query) use ($f_values) {
                    foreach ($f_values as $f_value) {
                        $query->whereHas('values', function ($query) use ($f_value) {
                            $query->whereIn('id', $f_value);
                        });
                    }
                });
            })
            ->when(isset($f_hasDiscount), function ($query) {
                return $query->where('discount_percent', '>', 0)
                    ->where('discount_start', '<=', Carbon::now()->toDateTimeString())
                    ->where('discount_end', '>=', Carbon::now()->toDateTimeString());
            })
            ->with('user')
            ->orderBy('random')
            ->paginate(24);

        $courses = $courses->appends([
            'q' => $q,
            'u' => $f_users,
            'c' => $f_categories,
            'v' => $f_values,
            'has_discount' => $f_hasDiscount,
            'has_credit' => $f_hasCredit,
        ]);

        // FILTER
        $users = User::orderBy('name')
            ->get();
        $categories = Category::orderBy('sort_order')
            ->orderBy('slug')
            ->get();
        $brands = Brand::orderBy('slug')
            ->get();
        $attributes = Attribute::with('values')
            ->orderBy('sort_order')
            ->get();

        return view('course.index')
            ->with([
                'q' => $q,
                'f_users' => collect($f_users),
                'f_categories' => collect($f_categories),
                'f_values' => collect($f_values)->collapse(),
                'f_hasDiscount' => $f_hasDiscount,
                'f_hasCredit' => $f_hasCredit,
                'courses' => $courses,
                'users' => $users,
                'categories' => $categories,
                'attributes' => $attributes,
            ]);
    }


    public function show($slug)
    {
        $course = Course::where('slug', $slug)
            ->with('user', 'category', 'values.attribute')
            ->firstOrFail();

        if (Cookie::has('p_v')) {
            $courseIds = explode(',', Cookie::get('p_v'));
            if (!in_array($course->id, $courseIds)) {
                $course->increment('viewed');
                $courseIds[] = $course->id;
                Cookie::queue('p_v', implode(',', $courseIds), 60 * 8);
            }
        } else {
            $course->increment('viewed');
            Cookie::queue('p_v', $course->id, 60 * 8);
        }

        $category = Category::findOrFail($course->category_id);
        $courses = Course::where('category_id', $category->id)
            ->with('user')
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('course.show')
            ->with([
                'course' => $course,
                'category' => $category,
                'courses' => $courses,
            ]);
    }


    public function create()
    {
        $categories = Category::orderBy('sort_order')
            ->orderBy('slug')
            ->get();
        $brands = Brand::orderBy('slug')
            ->get();
        $attributes = Attribute::with('values')
            ->orderBy('sort_order')
            ->get();

        return view('course.create')
            ->with([
                'categories' => $categories,
                'attributes' => $attributes,
            ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|integer|min:1',
            'brand' => 'required|integer|min:1',
            'values' => 'nullable|array',
            'values.*' => 'nullable|integer|min:0',
            'name_tm' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg|max:128|dimensions:width=1000,height=1000',
        ]);

        $category = Category::findOrFail($request->category);
        $brand = Brand::findOrFail($request->brand);

        $obj = Product::create([
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'name_tm' => $request->name_tm,
            'name_en' => $request->name_en ?: null,
            'full_name_tm' =>  $nameTm . ' ' . (isset($level) ? $level->name_tm . ' ' : '') . $category->course_name_tm,
            'full_name_en' =>   ($nameEn ?: $nameTm) . ' ' . (isset($level) ? ($level->name_en ?: $level->name_tm) . ' ' : '') . ($category->course_name_en ?: $category->course_name_tm),
            'description' => $request->description ?: null,
            'price' => round($request->price, 1),
        ]);
        $obj->save();
        $obj->values()->sync($request->has('values') ? array_filter($request->values) : []);


        return redirect()->back()
            ->with([
                'success' => 'Course (' . $obj->getFullName() . ') created!'
            ]);
    }


    public function edit($id)
    {
        $obj = Course::findOrFail($id);
        if (!$obj->isOwner()) {
            return abort(403);
        }

        $categories = Category::orderBy('sort_order')
            ->orderBy('slug')
            ->get();;
        $attributes = Attribute::with('values')
            ->orderBy('sort_order')
            ->get();

        return view('course.edit')
            ->with([
                'obj' => $obj,
                'categories' => $categories,
                'attributes' => $attributes,
            ]);
    }


    public function update(Request $request, $id)
    {
        $obj = Course::findOrFail($id);
        if (!$obj->isOwner()) {
            return abort(403);
        }

        $request->validate([
            'category' => 'required|integer|min:1',
            'brand' => 'required|integer|min:1',
            'values' => 'nullable|array',
            'values.*' => 'nullable|integer|min:0',
            'name_tm' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $category = Category::findOrFail($request->category);

        $obj->category_id = $category->id;
        $obj->name_tm = $request->name_tm;
        $obj->name_en = $request->name_en ?: null;
        $obj->full_name_tm = $brand->name . ' ' . $category->product_tm . ' ' . $request->name_tm;
        $obj->full_name_en = $brand->name . ' ' . ($category->product_en ?: $category->product_tm) . ' ' . ($request->name_en ?: $request->name_tm);
        $obj->description = $request->description ?: null;
        $obj->price = round($request->price, 1);
        $obj->update();
        $obj->values()->sync($request->has('values') ? array_filter($request->values) : []);


        return redirect()->back()
            ->with([
                'success' => 'Course (' . $obj->getFullName() . ') updated!'
            ]);
    }


    public function delete($id)
    {
        $obj = Course::findOrFail($id);
        if (!$obj->isOwner()) {
            return abort(403);
        }
        $objName = $obj->getFullName();
        $obj->delete();

        return redirect()->route('home')
            ->with([
                'success' => 'Course (' . $objName . ') deleted!'
            ]);
    }
}
