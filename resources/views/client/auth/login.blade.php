<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@lang('app.login') - @lang('app.app-name')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
</head>
<body style="background-color: #1B2431">
@include('client.layouts.alert')
<!-- Section: Design Block -->
<section class="">
    <!-- Jumbotron -->
    <div class="px-4 py-5 px-md-5 text-center text-lg-start"    >
        <div class="container">
            <div class="row gx-lg-5 align-items-center">
                <div class="col-lg-6">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-5 col-4">
                            <img src="img/1.jpg" class="img-fluid p-3">
                            <img src="img/2.jpg" class="img-fluid p-3">
                        </div>
                        <div class="col-lg-6 col-md-5 col-sm-4 col-4">
                            <img src="img/3.jpg" class="img-fluid p-3">
                            <img src="img/4.jpg" class="img-fluid p-3">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="card">
                        <div class="card-body py-5 px-md-5">
                            <form action="{{ route('client.login') }}" method="post">
                                @csrf
                                @honeypot
                                <div class="form-outline mb-4">
                                    <label for="name">
                                        @lang('app.name')
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" class="form-control" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Username input -->
                                <div class="form-outline mb-4">
                                    <label for="username">
                                        @lang('app.username')
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" class="form-control" value="{{ old('username') }}" required autofocus>
                                    @error('username')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password input -->
                                <div class="form-outline mb-4">
                                    <label for="password">
                                        @lang('app.password')
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" class="form-control" value="{{ old('password') }}" required>
                                    @error('password')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Checkbox -->
                                <div class="form-check d-flex justify-content-star mb-4">
                                    <input class="form-check-input me-2" type="checkbox" value="1" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">
                                        @lang('app.rememberMe')
                                    </label>
                                </div>

                                <!-- Submit button -->
                                <button type="submit" class="btn btn-primary btn-block mb-4 w-100">
                                    @lang('app.login')
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Jumbotron -->
</section>
<!-- Section: Design Block -->

<script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>