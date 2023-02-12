@if($category->parent_id)
    {{ $category->parent->name_tm }}<div> </div>
@endif
