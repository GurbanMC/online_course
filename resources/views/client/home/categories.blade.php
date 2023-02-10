    @if($category->parent_id)
        {{ $category->parent->name_tm }} /
    @endif
    <a href="{{ route('category', $category->slug) }}" class="text-decoration-none">
        {{ $category->name_tm }} <i class="bi-box-arrow-up-right"></i>
    </a>
