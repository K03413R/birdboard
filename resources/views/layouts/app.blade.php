<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-200">
    <div id="app">
        <nav class="bg-white">
            <div class="container mx-auto">
                <div class="flex justify-between items-center py-2">
                    <h1>
                        <a class="navbar-brand" href="{{ url('/projects') }}">
                            <img src="/images/logo.svg" alt="Birdboard">
                        </a>
                    </h1>
                    <div>
                        <!-- Right Side Of Navbar -->
                        <div class="flex items-center ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <a class="text-accent mr-4 no-underline hover:underline" href="{{ route('login') }}">{{ __('Login') }}</a>

                                @if (Route::has('register'))
                                    <a class="text-accent no-underline hover:underline" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            @else
                                <dropdown align="right" width="200px">
                                    <template v-slot:trigger>
                                        <button class="flex items-center no-underline text-sm focus:outline-none">
                                            <img width="35"
                                                 class="rounded-full mr-3"
                                                 src="{{ gravatar_url(auth()->user()->email) }}">

                                            {{ auth()->user()->name }}
                                        </button>
                                    </template>
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-menu-link w-full text-left">Logout</button>
                                    </form>
                                </dropdown>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="container mx-auto py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
