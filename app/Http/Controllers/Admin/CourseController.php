<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseVideo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:255',
            'category' => 'nullable|integer|min:1|exists:categories,id',
        ]);

        $q = $request->q ?: null;
        $f_category = $request->category ?: null;

        $objs = Course::when($q, function ($query, $q) {
            return $query->where(function ($query) use ($q) {
                $query->orWhere('code', 'like', '%' . $q . '%');
                $query->orWhere('name_tm', 'like', '%' . $q . '%');
                $query->orWhere('name_en', 'like', '%' . $q . '%');
                $query->orWhere('full_name_tm', 'like', '%' . $q . '%');
                $query->orWhere('full_name_en', 'like', '%' . $q . '%');
                $query->orWhere('slug', 'like', '%' . $q . '%');
            });
        })
            ->when($f_category, function ($query, $f_category) {
                $query->where('category_id', $f_category);
            })
            ->with(['category.parent'])
            ->paginate(50)
            ->withQueryString();

        $categories = Category::whereNotNull('parent_id')->withCount('courses')
            ->orderBy('sort_order')
            ->get();

        return view('admin.course.index')
            ->with([
                'objs' => $objs,
                'categories' => $categories,
                'f_category' => $f_category,
            ]);
    }

    public function create()
    {
        $categories = Category::whereNotNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $attributes = Attribute::orderBy('sort_order')
            ->with('values')
            ->get();

        return view('admin.course.create')
            ->with([
                'categories' => $categories,
                'attributes' => $attributes,
            ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|integer|min:1',
            'level' => 'nullable|integer|min:1',
            'name_tm' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0',
            'discount_start' => 'nullable|date|after_or_equal:today',
            'discount_end' => 'nullable|date|after:discount_start',
            'videos' => 'nullable|array|min:0',
            'videos.*' => 'nullable|video|mimes:mp4|max:128',
        ]);
        $category = Category::findOrFail($request->category);
        $level = $request->has('level') ? AttributeValue::findOrFail($request->level) : null;
        $fullNameTm = $request->name_tm . ' ';
        $fullNameEn = ($request->name_en ?: $request->name_tm) . ' ';


        $obj = Course::create([
            'category_id' => $category->id,
            'level_id' => $level->id ?: null,
            'code' => 'c' . $category->id
                . (isset($level) ? '-g' . $level->id : ''),
            'name_tm' => $request->name_tm,
            'name_en' => $request->name_en ?: null,
            'full_name_tm' => isset($fullNameTm) ? $fullNameTm : null,
            'full_name_en' => isset($fullNameEn) ? $fullNameEn : null,
            'slug' => str()->slug($fullNameTm) . '-' . str()->random(100),
            'price' => $request->price,
            'discount_percent' => $request->discount_percent ?: 0,
            'discount_start' => $request->discount_start ?: Carbon::today(),
            'discount_end' => $request->discount_end ?: Carbon::today(),
            'description' => $request->description ?: null,
        ]);
        return 1;
        if ($request->has('videos')) {
            $firstVideoName = "";
            $i = 0;
            foreach ($request->videos as $video) {
                $name = str()->random(10) . '.' . $video->extension();
                if ($i == 0) {
                    $firstVideoName = $name;
                }
                Storage::putFileAs('public/p', $video, $name);
                CourseVideo::create([
                    'course_id' => $obj->id,
                    'video' => $name,
                ]);
                $i += 1;
            }
            $obj->video = $firstVideoName;
            $obj->update();
        }

        return to_route('admin.courses.index')
            ->with([
                'success' => @trans('app.course') . $obj->getName() . @trans('app.added') . '!'
            ]);
    }

    public function show()
    {
        //
    }

    public function edit($id)
    {
        $obj = Course::findOrFail($id);

        $categories = Category::whereNotNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $attributes = Attribute::orderBy('sort_order')
            ->with('values')
            ->get();

        $videos = CourseVideo::where('course_id', $id)
            ->get();

        return view('admin.course.edit')
            ->with([
                'obj' => $obj,
                'categories' => $categories,
                'attributes' => $attributes,
                'videos' => $videos,
            ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|integer|min:1',
            'gender' => 'nullable|integer|min:1',
            'color' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1',
            'name_tm' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0',
            'discount_start' => 'nullable|date|after_or_equal:today',
            'discount_end' => 'nullable|date|after:discount_start',
            'videos' => 'nullable|array|min:0',
            'videos.*' => 'nullable|video|mimes:mp4|max:260',
        ]);
        $category = Category::findOrFail($request->brand);
        $level = $request->has('level') ? AttributeValue::findOrFail($request->level) : null;
        $fullNameTm = $request->name_tm . ' ';
        $fullNameEn = ($request->name_en ?: $request->name_tm) . ' ';

        $obj = Course::findOrFail($id);
        $obj->category_id = $category->id;
        $obj->level_id = $level->id ?: null;
        $obj->code = 'c' . $category->id
            . (isset($level) ? '-g' . $level->id : '');
        $obj->name_tm = $request->name_tm;
        $obj->name_en = $request->name_en ?: null;
        $obj->full_name_tm = isset($fullNameTm) ? $fullNameTm : null;
        $obj->full_name_en = isset($fullNameEn) ? $fullNameEn : null;
        $obj->slug = str()->slug($fullNameTm) . '-' . str()->random(10);
        $obj->price = $request->price;
        $obj->discount_percent = $request->discount_percent ?: 0;
        $obj->discount_start = $request->discount_start ?: Carbon::today();
        $obj->discount_end = $request->discount_end ?: Carbon::today();

        if ($request->has('images')) {
            $firstVideoName = "";
            $i = 0;
            foreach ($request->videos as $video) {
                $name = str()->random(10) . '.' . $video->extension();
                if ($i == 0) {
                    $firstVideoName = $name;
                }
                Storage::putFileAs('public/p', $video, $name);
                CourseVideo::create([
                    'course_id' => $obj->id,
                    'video' => $name,
                ]);
                $i += 1;
            }
            $obj->video = $firstVideoName;
            $obj->update();
        }

        return to_route('admin.courses.index')
            ->with([
                'success' => @trans('app.course') . $obj->getName() . @trans('app.updated') . '!'
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
        $videos = CourseVideo::where('course_id', $id)
            ->get();
        if (count($videos) > 0){
            foreach ($videos as $video)
            {
                Storage::delete('public/p/' . $video);
            }
        }

        $obj = Course::findOrFail($id);
        $objName = $obj->getName();
        $obj->delete();

        return redirect()->back()
            ->with([
                'success' => trans('app.category') . ' (' . $objName . ') ' . trans('app.deleted') . '!'
            ]);
    }
}
