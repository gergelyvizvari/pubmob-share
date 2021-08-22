<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PubMob') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('header.scripts')
</head>

<body>
    <div id="app">
        <header class="v-sheet v-sheet--tile theme--dark v-toolbar v-app-bar  indigo"
            style="height: 64px; margin-top: 0px; transform: translateY(0px); left: 0px; right: 0px;"
            data-booted="true">
            <div class="v-toolbar__image">
                <div class="v-responsive v-image" style="height: 64px;">
                    <div class="v-responsive__sizer" style="padding-bottom: 56.25%;"></div>
                    <div class="v-image__image v-image__image--cover"
                        style="background-image: linear-gradient(to right top, rgba(19, 84, 122, 0.5), rgba(128, 208, 199, 0.8)), url(&quot;https://picsum.photos/1920/1080?random&quot;); background-position: center center;">
                    </div>
                    <div class="v-responsive__content" style="width: 1920px;"></div>
                </div>
            </div>
            <div class="v-toolbar__content" style="height: 64px;">
                <div class="v-toolbar__title">PubMob</div>
                @guest

                @else
                    <div class="v-divider border-0"></div>
                    <li class="nav-item dropdown v-list">
                        <a class="btn btn-light" href="{{ route('home') }}">
                            Kereső
                        </a>
                        <a class="btn btn-light" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Kijelentkezés
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endguest
            </div>
        </header>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @yield('footer.scripts')
</body>

</html>
