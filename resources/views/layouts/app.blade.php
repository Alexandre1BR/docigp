<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.medium_name') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @include('layouts.partials.environment')
    @livewireStyles
</head>

<body>
    <div id="app">
        @include('layouts.partials.menu')

        <main class="py-4">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col">
                        @yield('content')

                        <div>
                            @if(isset($slot))
                                {{ $slot }}
                            @endIf
                        </div>
                    </div>
                </div>

                <div class="row text-center mt-5">
                    <div class="col">
{{--                        @version--}}
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('layouts.partials.livereload')
    @include('layouts.partials.google-analytics')
    @livewireScripts
    <script src="{{ mix('js/alpine.js') }}" defer></script>

</body>


</html>
