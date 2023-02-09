<nav class="navbar navbar-expand-md navbar-light border-bottom p-2" aria-label="navbar">
    <div class="container-xl">
        <a class="navbar-brand text-danger" href="{{ route('home') }}">@lang('app.app-name')</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbars" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <form action="{{ route('client.courses.index') }}" class="row align-items-center g-2" role="search" id="productFilter">
            <div class="input-group ms-5">
                <input type="search" class="form-control form-control-md w-75 rounded-0" name="q" placeholder="{{ @trans('app.search') }}" aria-label="Search" aria-describedby="search-addon" />
                <button type="submit" class="btn btn-danger rounded-0"><i class="bi-search"></i></button>
            </div>
        </form>

        <div class="collapse navbar-collapse" id="navbars">
            <ul class="navbar-nav ms-auto">
                @auth('customer_web')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                            <i class="bi-box-arrow-right"></i> {{ auth('customer_web')->user()['name'] }}
                        </a>
                    </li>
                    <form id="logoutForm" action="{{ route('logout') }}" method="post" class="d-none">
                        @csrf
                    </form>
                @else
                    <li class="nav-item">
                        <div class="btn btn-light w-100 p-0 rounded-0" style="border-color: #1a1e21">
                            <a class="nav-link fw-semibold text-dark" href="{{ route('client.register') }}">
                                @lang('app.login')
                            </a>
                        </div>
                    </li>
                    <li class="nav-item ms-3">
                        <div class="btn btn-danger w-100 p-0 rounded-0">
                            <a class="nav-link text-light fw-semibold" href="{{ route('client.register') }}">
                                @lang('app.register')
                            </a>
                        </div>
                    </li>
                @endauth
                @if(app()->getLocale() == 'en')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('language', 'tm') }}">
                            <img src="{{ asset('img/flag/tkm.png') }}" alt="Türkmen" style="height:25px;">
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('language', 'en') }}">
                            <img src="{{ asset('img/flag/eng.png') }}" alt="English" style="height:25px;">
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>