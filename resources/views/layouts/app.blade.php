<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Online marketplace app') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>

<div id="app">
    <nav class="navbar-header navbar navbar-dark navbar-expand-lg">
        <div class="container-header container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a href="/">
                <x-icons.olx-svg title="Online marketplace app">{{ __('Online marketplace app') }}</x-icons.olx-svg>
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 ">
                    @auth
                        <a class="nav-link" href="{{ route('chat.rooms') }}">
                            {{ __('Messages') }}
                        </a>
                    @endauth
                    <li class="nav-item dropdown nav-drop">
                        <div class="nav-link dropdown-toggle cursor-pointer" id="dropdownMenuNav"
                             data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @auth
                                {{ Auth::user()->name }}
                            @else
                                {{ __('My account') }}
                            @endauth
                        </div>
                        <ul class="dropdown-menu nav-menu" aria-labelledby="dropdownMenuNav">
                            @guest
                                <li><a class="dropdown-item" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('login') }}">{{ __('Log in') }}</a></li>
                            @endguest
                            @auth
                                <li><a class="dropdown-item"
                                       href="{{ route('listings.user-listings') }}">{{ __('My listings') }}</a></li>
                                <li><a class="dropdown-item"
                                       href="{{ route('listings.create') }}">{{ __('Add a new listing') }}</a>
                                </li>
                                @can('admin')
                                    <li><a class="dropdown-item"
                                           href="{{ route('admin.categories.create') }}">{{ __('Add a new category') }}</a>
                                    </li>
                                @endcan
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}">
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="py-4">
        @yield('content')
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<x-flash/>
@stack('js')
</body>
</html>
