<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light border-end sidebar collapse">
    <div class="position-sticky py-2 sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link link-dark" href="{{ route('admin.dashboard') }}">
                    <i class="bi-speedometer text-danger me-1"></i> @lang('app.dashboard')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link-dark" href="{{ route('admin.customers.index') }}">
                    <i class="bi-people-fill text-danger me-1"></i> @lang('app.customers')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link-dark" href="{{ route('admin.verifications.index') }}">
                    <i class="bi-shield-fill-check text-danger me-1"></i> @lang('app.verifications')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link-dark" href="{{ route('admin.courses.index') }}">
                    <i class="bi-box-fill text-danger me-1"></i> @lang('app.courses')
                </a>
            </li>
            @can('categories')
                <li class="nav-item">
                    <a class="nav-link link-dark" href="{{ route('admin.categories.index') }}">
                        <i class="bi-grid-fill text-danger me-1"></i> @lang('app.categories')
                    </a>
                </li>
            @endcan
            @can('attributes')
                <li class="nav-item">
                    <a class="nav-link link-dark" href="{{ route('admin.attributes.index') }}">
                        <i class="bi-palette-fill text-danger me-1"></i> @lang('app.attributes')
                    </a>
                </li>
            @endcan
            <li class="nav-item">
                <a class="nav-link link-dark" href="{{ route('admin.users.index') }}">
                    <i class="bi-people-fill text-danger me-1"></i> @lang('app.users')
                </a>
            </li>
        </ul>
    </div>
</nav>