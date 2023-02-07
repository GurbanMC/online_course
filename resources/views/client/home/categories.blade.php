<div class="container-xxl py-3">
    <div class="d-flex justify-content-between align-items-center border-bottom py-2 mb-3">
        <a href="{{ route('categories.show', $categories['category']->slug) }}" class="link-secondary"></a>
    </div>
    <div class="row row-cols-2 row-cols-lg-4 row-cols-xl-4 g-3">
        @foreach($courses as $course)
            <div class="col" data-aos="fade-up">
                @include('client.app.category')
            </div>
        @endforeach
    </div>
</div>