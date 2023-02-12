<div class="container-fluid my-5">
    <div class="row row-cols-2 row-cols-lg-4 row-cols-xl-4 g-3">
        @foreach($objs as $course)
            <div class="col" data-aos="fade-up">
                @include('client.app.course')
            </div>
        @endforeach
    </div>
</div>