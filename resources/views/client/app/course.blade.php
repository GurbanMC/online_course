<div class="position-relative bg-white border rounded">
    <div class="">
        <img src="{{  $course->image ? Storage::url('products/sm/' . $course->image) : asset('img/sm/product.jpg') }}"
             alt="{{ $course->getFullName() }}" class="img-fluid rounded-start col-12">
    </div>
    <div>
        <div class="d-flex flex-column h-100 pb-3">
            <div class="fw-semibold mb-auto">
                <div class="p-2 h-3">
                    {{ $course->price }} <span class="fw-bold">TMT</span>
                </div>
                <div class="p-2">
                    {{ $course->full_name_tm  }}
                </div>
                <a href="{{ route('course.show', $course->slug) }}" class="link-dark text-decoration-none stretched-link">
                    {{ $course->name }}
                </a>
            </div>
        </div>
    </div>
</div>