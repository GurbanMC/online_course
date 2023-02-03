<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'ordering' => 'nullable|string|in:nameAsc,nameDesc,priceLow,priceHigh,newest,oldest',
            'category' => 'nullable|integer|min:1|exists:categories,id',
            'brand' => 'nullable|integer|min:1|exists:brands,id',
        ]);

        $q = $request->q ?: null;
        $f_category = $request->category ?: null;
        $f_brand = $request->brand ?: null;
        $f_order = $request->ordering;
        $ordering = $f_order ? config()->get('mysettings.ordering')['b'][$f_order] : null;

        $objs = Product::when($q, function ($query, $q) {
            return $query->where(function ($query) use ($q) {
                $query->orWhere('code', 'like', '%' . $q . '%');
                $query->orWhere('name_tm', 'like', '%' . $q . '%');
                $query->orWhere('name_en', 'like', '%' . $q . '%');
                $query->orWhere('full_name_tm', 'like', '%' . $q . '%');
                $query->orWhere('full_name_en', 'like', '%' . $q . '%');
                $query->orWhere('slug', 'like', '%' . $q . '%');
                $query->orWhere('barcode', 'like', '%' . $q . '%');
            });
        })
            ->when($f_brand, function ($query, $f_brand) {
                $query->where('brand_id', $f_brand);
            })
            ->when($f_category, function ($query, $f_category) {
                $query->where('category_id', $f_category);
            })
            ->when($ordering, function ($query, $ordering) {
                return $query->orderBy($ordering[0], $ordering[1]);
            }, function ($query) {
                return $query->inRandomOrder();
            })
            ->with(['brand', 'category.parent'])
            ->withCount('orderProducts')
            ->paginate(50)
            ->withQueryString();

        $categories = Category::whereNotNull('parent_id')->withCount('products')
            ->orderBy('sort_order')
            ->get();
        $brands = Brand::orderBy('name')->withCount('products')->get();

        return view('admin.product.index')
            ->with([
                'objs' => $objs,
                'brands' => $brands,
                'categories' => $categories,
                'f_brand' => $f_brand,
                'f_category' => $f_category,
                'f_order' => $f_order,
            ]);
    }

    public function create()
    {
        $categories = Category::whereNotNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $brands = Brand::orderBy('name')
            ->get();

        $attributes = Attribute::orderBy('sort_order')
            ->with('values')
            ->get();

        return view('admin.product.create')
            ->with([
                'categories' => $categories,
                'brands' => $brands,
                'attributes' => $attributes,
            ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|integer|min:1',
            'category' => 'required|integer|min:1',
            'gender' => 'nullable|integer|min:1',
            'color' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1',
            'name_tm' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount_percent' => 'nullable|integer|min:0',
            'discount_start' => 'nullable|date|after_or_equal:today',
            'discount_end' => 'nullable|date|after:discount_start',
            'images' => 'nullable|array|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg|max:128|dimensions:width=1000,height=1000',
        ]);
        $brand = Brand::findOrFail($request->brand);
        $category = Category::findOrFail($request->brand);
        $gender = $request->has('gender') ? AttributeValue::findOrFail($request->gender) : null;
        $color = $request->has('color') ? AttributeValue::findOrFail($request->color) : null;
        $size = $request->has('size') ? AttributeValue::findOrFail($request->size) : null;

        $fullNameTm = $brand->name . ' '
            . $request->name_tm . ' '
            . (isset($gender) ? $gender->name_tm . ' ' : '')
            . (isset($color) ? $color->name_tm . ' ' : '')
            . $category->product_name_tm
            . (isset($size) ? ', ' . $size->name_tm : '');
        $fullNameEn = $brand->name . ' '
            . ($request->name_en ?: $request->name_tm) . ' '
            . (isset($gender) ? ($gender->name_en ?: $gender->name_tm) . ' ' : '')
            . (isset($color) ? ($color->name_en ?: $color->name_tm) . ' ' : '')
            . ($category->product_name_en ?: $category->product_name_tm)
            . (isset($size) ? ', ' . ($size->name_en ?: $size->name_tm) : '');

        $obj = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'gender_id' => $gender->id ?: null,
            'color_id' => $color->id ?: null,
            'size_id' => $size->id ?: null,
            'code' => 'c' . $category->id
                . '-b' . $brand->id
                . (isset($gender) ? '-g' . $gender->id : '')
                . (isset($color) ? '-' . $color->id : ''),
            'name_tm' => $request->name_tm,
            'name_en' => $request->name_en ?: null,
            'full_name_tm' => isset($fullNameTm) ? $fullNameTm : null,
            'full_name_en' => isset($fullNameEn) ? $fullNameEn : null,
            'slug' => str()->slug($fullNameTm) . '-' . str()->random(10),
            'barcode' => $request->barcode ?: null,
            'price' => $request->price,
            'stock' => $request->stock,
            'discount_percent' => $request->discount_percent ?: 0,
            'discount_start' => $request->discount_start ?: Carbon::today(),
            'discount_end' => $request->discount_end ?: Carbon::today(),
        ]);

        if ($request->has('images')) {
            $firstImageName = "";
            $i = 0;
            foreach ($request->images as $image) {
                $name = str()->random(10) . '.' . $image->extension();
                if ($i == 0) {
                    $firstImageName = $name;
                }
                Storage::putFileAs('public/p', $image, $name);
                ProductImage::create([
                    'product_id' => $obj->id,
                    'image' => $name,
                ]);
                $i += 1;
            }
            $obj->image = $firstImageName;
            $obj->update();
        }

        return to_route('admin.products.index')
            ->with([
                'success' => @trans('app.product') . $obj->getName() . @trans('app.added') . '!'
            ]);
    }

    public function show()
    {
        //
    }

    public function edit($id)
    {
        $obj = Product::findOrFail($id);

        $categories = Category::whereNotNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $brands = Brand::orderBy('name')
            ->get();

        $attributes = Attribute::orderBy('sort_order')
            ->with('values')
            ->get();

        $images = ProductImage::where('product_id', $id)
            ->get();

        return view('admin.product.edit')
            ->with([
                'obj' => $obj,
                'categories' => $categories,
                'brands' => $brands,
                'attributes' => $attributes,
                'images' => $images,
            ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'brand' => 'required|integer|min:1',
            'category' => 'required|integer|min:1',
            'gender' => 'nullable|integer|min:1',
            'color' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1',
            'name_tm' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'discount_percent' => 'nullable|integer|min:0',
            'discount_start' => 'nullable|date|after_or_equal:today',
            'discount_end' => 'nullable|date|after:discount_start',
            'images' => 'nullable|array|min:0',
            'images.*' => 'nullable|image|mimes:jpg,jpeg|max:260|dimensions:width=1000,height=1000',
        ]);
        $brand = Brand::findOrFail($request->brand);
        $category = Category::findOrFail($request->brand);
        $gender = $request->has('gender') ? AttributeValue::findOrFail($request->gender) : null;
        $color = $request->has('color') ? AttributeValue::findOrFail($request->color) : null;
        $size = $request->has('size') ? AttributeValue::findOrFail($request->size) : null;

        $fullNameTm = $brand->name . ' '
            . $request->name_tm . ' '
            . (isset($gender) ? $gender->name_tm . ' ' : '')
            . (isset($color) ? $color->name_tm . ' ' : '')
            . $category->product_name_tm
            . (isset($size) ? ', ' . $size->name_tm : '');
        $fullNameEn = $brand->name . ' '
            . ($request->name_en ?: $request->name_tm) . ' '
            . (isset($gender) ? ($gender->name_en ?: $gender->name_tm) . ' ' : '')
            . (isset($color) ? ($color->name_en ?: $color->name_tm) . ' ' : '')
            . ($category->product_name_en ?: $category->product_name_tm)
            . (isset($size) ? ', ' . ($size->name_en ?: $size->name_tm) : '');

        $obj = Product::findOrFail($id);
        $obj->category_id = $category->id;
        $obj->brand_id = $brand->id;
        $obj->gender_id = $gender->id ?: null;
        $obj->color_id = $color->id ?: null;
        $obj->size_id = $size->id ?: null;
        $obj->code = 'c' . $category->id
            . '-b' . $brand->id
            . (isset($gender) ? '-g' . $gender->id : '')
            . (isset($color) ? '-' . $color->id : '');
        $obj->name_tm = $request->name_tm;
        $obj->name_en = $request->name_en ?: null;
        $obj->full_name_tm = isset($fullNameTm) ? $fullNameTm : null;
        $obj->full_name_en = isset($fullNameEn) ? $fullNameEn : null;
        $obj->slug = str()->slug($fullNameTm) . '-' . str()->random(10);
        $obj->barcode = $request->barcode ?: null;
        $obj->price = $request->price;
        $obj->stock = $request->stock;
        $obj->discount_percent = $request->discount_percent ?: 0;
        $obj->discount_start = $request->discount_start ?: Carbon::today();
        $obj->discount_end = $request->discount_end ?: Carbon::today();

        if ($request->has('images')) {
            $firstImageName = "";
            $i = 0;
            foreach ($request->images as $image) {
                $name = str()->random(10) . '.' . $image->extension();
                if ($i == 0) {
                    $firstImageName = $name;
                }
                Storage::putFileAs('public/p', $image, $name);
                ProductImage::create([
                    'product_id' => $obj->id,
                    'image' => $name,
                ]);
                $i += 1;
            }
            $obj->image = $firstImageName;
            $obj->update();
        }

        return to_route('admin.products.index')
            ->with([
                'success' => @trans('app.product') . $obj->getName() . @trans('app.updated') . '!'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $orderProduct = OrderProduct::where('product_id', $id)
            ->get();
        if (count($orderProduct) > 0) {
            return redirect()->back()
                ->with([
                    'error' => trans('app.error') . '!'
                ]);
        }

        $images = ProductImage::where('product_id', $id)
            ->get();
        if (count($images) > 0){
            foreach ($images as $image)
            {
                Storage::delete('public/p/' . $image);
            }
        }

        $obj = Product::findOrFail($id);
        $objName = $obj->getName();
        $obj->delete();

        return redirect()->back()
            ->with([
                'success' => trans('app.category') . ' (' . $objName . ') ' . trans('app.deleted') . '!'
            ]);
    }
}
