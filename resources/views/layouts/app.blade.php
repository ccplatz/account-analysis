<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('style')
</head>

<body data-bs-theme="dark">
    <div id="app" class="container">
        <header class="d-flex justify-content-center py-3 mb-5">
            <ul class="nav nav-pills">
                <li class="nav-item"> <a href="{{ route('home') }}"
                        class="nav-link {{ Route::is('home') ? 'active' : '' }}">Home</a>
                </li>
                <li class="nav-item"> <a href="{{ route('files.index') }}"
                        class="nav-link {{ Route::is('files.index') ? 'active' : '' }}">Files</a>
                </li>
                <li class="nav-item"> <a href="{{ route('accounts.index') }}"
                        class="nav-link {{ Route::is('accounts.index') ? 'active' : '' }}">Accounts</a>
                </li>
                <li class="nav-item"> <a href="{{ route('categories.index') }}"
                        class="nav-link {{ Route::is('categories.index') ? 'active' : '' }}">Categories</a>
                </li>
            </ul>
        </header>

        @include('layouts.partials.messages')

        @yield('content')
    </div>
    @stack('scripts')
</body>

</html>
