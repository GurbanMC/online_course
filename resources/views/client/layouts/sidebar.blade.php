<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block border-end sidebar collapse" style="background-color: #1B2431">
    <div class="position-sticky py-2 sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link link-light text-secondary" href="{{ route('client.home.index') }}">
                    <i class="bi-grid-1x2-fill text-secondary me-1"></i> @lang('app.home')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link-light text-secondary" href="{{ route('client.courses.index') }}">
                    <i class="bi-filetype-mp4 text-secondary me-1"></i> @lang('app.courses')
                </a>
            </li>
            @can('categories')
                <li class="nav-item">
                    <a class="nav-link link-light text-secondary" href="{{ route('client.categories.index') }}">
                        <i class="bi-grid-fill text-secondary me-1"></i> @lang('app.categories')
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</nav>