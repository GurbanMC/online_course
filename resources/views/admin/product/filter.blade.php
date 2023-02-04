<form action="{{ route('admin.products.index') }}" class="row align-items-center g-2" role="search" id="productFilter">

    <div class="col-auto">
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-danger">@lang('app.clear') <i class="bi-x"></i></a>
    </div>
    <div class="col">
        <select class="form-select form-select-sm" name="ordering" id="ordering" size="1" onchange="$('form#productFilter').submit();">
            <option value>@lang('app.ordering'): @lang('app.default')</option>
            @foreach(config()->get('mysettings.ordering')['a'] as $ordering)
                <option value="{{ $ordering }}" {{ $ordering == $f_order ? 'selected' : '' }}>
                    @lang('app.' . $ordering)
                </option>
            @endforeach
        </select>
        @error('ordering')
        <div class="alert alert-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="col">
        <select class="form-select form-select-sm" name="brand" id="brand" size="1" onchange="$('form#productFilter').submit();">
            <option value>@lang('app.brands')</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ $brand->id == $f_brand ? 'selected' : '' }}>
                    {{ $brand->name . ' (' . $brand->products_count . ')' }}
                </option>
            @endforeach
        </select>
        @error('brand')
        <div class="alert alert-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="col">
        <select class="form-select form-select-sm" name="category" id="category" size="1" onchange="$('form#productFilter').submit();">
            <option value>@lang('app.categories')</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $f_category ? 'selected' : '' }}>
                    {{ $category->getName() . ' (' . $category->products_count . ')' }}
                </option>
            @endforeach
        </select>
        @error('category')
        <div class="alert alert-danger mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="col">
        <input class="form-control form-control-sm" type="search" name="q" placeholder="{{ @trans('app.search') }}">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-dark btn-sm"><i class="bi-search"></i></button>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.products.create') }}" class="btn btn-danger btn-sm">
            <i class="bi-plus-lg"></i> @lang('app.add')
        </a>
    </div>
</form>
