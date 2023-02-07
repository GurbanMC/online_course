<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block border-end sidebar collapse" style="background-color: #1B2431">
    <div class="position-sticky py-2 sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link link-light text-secondary" href="{{ route('admin.dashboard') }}">
                    <i class="bi-grid-1x2-fill text-secondary me-1"></i> @lang('app.dashboard')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link-light text-secondary" href="{{ route('admin.customers.index') }}">
                    <i class="bi-people-fill text-secondary me-1"></i> @lang('app.customers')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link-light text-secondary" href="{{ route('admin.verifications.index') }}">
                    <i class="bi-shield-fill-check text-secondary me-1"></i> @lang('app.verifications')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link-light text-secondary" href="{{ route('admin.courses.index') }}">
                    <i class="bi-filetype-mp4 text-secondary me-1"></i> @lang('app.courses')
                </a>
            </li>
            @can('categories')
                <li class="nav-item">
                    <a class="nav-link link-light text-secondary" href="{{ route('admin.categories.index') }}">
                        <i class="bi-grid-fill text-secondary me-1"></i> @lang('app.categories')
                    </a>
                </li>
            @endcan
            @can('attributes')
                <li class="nav-item">
                    <a class="nav-link link-light text-secondary" href="{{ route('admin.attributes.index') }}">
                        <i class="bi-palette-fill text-secondary me-1"></i> @lang('app.attributes')
                    </a>
                </li>
            @endcan
            <li class="nav-item">
                <a class="nav-link link-light text-secondary" href="{{ route('admin.users.index') }}">
                    <i class="bi-people-fill text-secondary me-1"></i> @lang('app.users')
                </a>
            </li>
        </ul>
    </div>
</nav>