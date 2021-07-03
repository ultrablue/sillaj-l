<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- TODO Make partials for these, please!! -->
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/sillaj-l.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- OpenIconic -->
    <link href="{{asset('open-iconic-master/font/css/open-iconic.css')}}" rel="stylesheet">

</head>
<body>


<!-------------------------------------------------------------->
<div>
    <header class="lg:px-16 px-6 bg-white flex flex-wrap items-center lg:py-0 py-2 border-b-2">
        <div class="flex-1 flex justify-between items-center">
            <a href="{{route('home')}}">
                {{config('app.name')}}
            </a>
        </div>

        <label for="menu-toggle" class="pointer-cursor lg:hidden block">
            <svg class="fill-current text-red-900" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                <title>menu</title>
                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
            </svg>
        </label>
        <input class="hidden" type="checkbox" id="menu-toggle"/>

        <div class="hidden lg:flex lg:items-center lg:w-auto w-full" id="menu">
            <nav>
                <ul class="lg:flex items-center justify-between text-base text-gray-700 pt-4 lg:pt-0">


                    @guest
                        <li>
                            <a class="text-sm lg:p-4 py-3 px-0 block border-b-2 border-transparent hover:border-indigo-400 lg:mb-0 mb-2" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <li>
                            <a class="text-sm lg:p-4 py-3 px-0 block border-b-2 border-transparent hover:border-indigo-400 lg:mb-0 mb-2" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @else

                        <li>
                            <a class="text-sm lg:p-4 py-3 px-0 block border-b-2 border-transparent hover:border-indigo-400" href="{{@route('projects-list')}}">Projects</a>
                        </li>
                        <li>
                            <a class="text-sm lg:p-4 py-3 px-0 block border-b-2 border-transparent hover:border-indigo-400" href="{{@route('tasks-list')}}">Tasks</a>
                        </li>
                        <li>
                            <a class="text-sm lg:p-4 py-3 px-0 block border-b-2 border-transparent hover:border-indigo-400" href="{{@route('reports-list')}}">Reports</a>
                        </li>
                        <li>
                            <a class="text-sm lg:p-4 py-3 px-0 block border-b-2 border-transparent hover:border-indigo-400 lg:mb-0 mb-2" href="#">Support</a>
                        </li>

                        <li>
                            <a class="text-sm lg:p-4 py-3 px-0 block border-b-2 border-transparent hover:border-indigo-400 lg:mb-0 mb-2" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>

                        </li>
                    @endguest

                </ul>
            </nav>

            @auth

                <a href="#" class="lg:ml-4 flex items-center justify-start lg:mb-0 mb-4 pointer-cursor">
                    <div class="text-3xl font-bold text-gray-300 rounded-full w-10 h-10 border-2 border-transparent hover:border-indigo-900 bg-green-700 text-center" title="{{Auth::user()->name}}">
                        {{ Auth::user()->name[0] }}
                    </div>
                </a>
            @endauth


        </div>

    </header>
</div>

{{--<h1 class="my-12 text-2xl"><span class="oi text-indigo-500" data-glyph="bell"></span></h1>--}}

<div class="container mx-auto w-full relative px-16 pt-6">
    @yield('content')
</div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery -->
<!-- TODO Make a partial for this, please! -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous">
</script>

</body>
</html>
