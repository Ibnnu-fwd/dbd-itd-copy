<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tab Icon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo mini.png') }}" type="image/x-icon">

    <!-- Fonts -->
    {{-- <link href="https://fonts.cdnfonts.com/css/lexend-deca" rel="stylesheet"> --}}
    {{-- <link href="https://fonts.cdnfonts.com/css/poppins" rel="stylesheet"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Alert -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Flowbite -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Marker Cluster -->
    <link rel="stylesheet" href="{{ asset('assets/css/MarkerCluster.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/MarkerCluster.Default.css') }}">

    <!-- Fullscreen -->
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css'
        rel='stylesheet' />

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased h-screen">
    <div class="max-w-5xl mx-auto mt-4">
        <header>
            <nav class="border-gray-200 px-2 lg:px-6 py-2.5 lg:mx-0 mx-2">
                <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('assets/images/logo.png') }}" class="mr-3 h-8 sm:h-12" alt="Logo" />
                    </a>
                    <div class="flex items-center lg:order-2">
                        @if (auth()->check())
                            <div class="flex md:order-2">
                                <button id="dropdownAvatarNameButton" data-dropdown-toggle="dropdownAvatarName"
                                    class="flex items-center text-xs 2xl:text-sm font-medium text-gray-900 hover:text-purple-600 dark:hover:text-purple-500 md:mr-0"
                                    type="button">
                                    <span class="sr-only">Open user menu</span>
                                    {{-- <img class="w-8 h-8 mr-2 rounded-full"
                                        src="{{ auth()->user()->profile_pictu
                                            ? asset('storage/profile-picture/' . auth()->user()->profile_picture)
                                            : asset('assets/images/noimage.jpg') }}"
                                        alt="user photo"> --}}
                                    {{ auth()->user()->name }}
                                    <svg class="w-4 h-4 mx-1.5" aria-hidden="true" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="dropdownAvatarName"
                                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600"
                                    style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(1336.67px, 114.167px, 0px);"
                                    data-popper-placement="bottom">
                                    <div class="px-4 py-3 text-xs 2xl:text-sm text-gray-900 dark:text-white">
                                        <div class="font-medium ">
                                            {{ auth()->user()->role }}
                                        </div>
                                        <div class="truncate">
                                            {{ auth()->user()->email }}
                                        </div>
                                    </div>
                                    <div class="py-2">
                                            <a href="{{route('admin.dashboard')}}" class="flex w-full px-4 py-2 text-xs 2xl:text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <i class="fas fa-tachometer-alt mr-2"></i>
                                                <span>Dashboard</span>
                                            </a>
                                            @csrf
                                            <button type="submit"
                                                class="flex w-full px-4 py-2 text-xs 2xl:text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <i class="fas fa-sign-out-alt mr-2"></i>
                                                <span>Keluar</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <button data-collapse-toggle="navbar-cta" type="button"
                                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                    aria-controls="navbar-cta" aria-expanded="false">
                                    <span class="sr-only">Open main menu</span>
                                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <x-link-button route="{{ route('login') }}"
                                class="hidden lg:inline-block text-xs 2xl:text-sm bg-primary">
                                Login
                            </x-link-button>
                        @endif
                        <button data-collapse-toggle="mobile-menu-2" type="button"
                            class="inline-flex items-center p-2 ml-1 text-xs 2xl:text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                            aria-controls="mobile-menu-2" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1"
                        id="mobile-menu-2">
                        <ul class="flex flex-col mt-4 lg:flex-row lg:space-x-8 lg:mt-0">
                            <li>
                                <a href="/"
                                    class="block py-2 pr-4 pl-3 {{ request()->routeIs('user.index') ? 'text-primary font-medium' : 'text-gray-700' }} rounded bg-primary-700 lg:bg-transparent lg:text-primary-700 lg:p-0 text-xs 2xl:text-sm">Beranda</a>
                            </li>
                            <li>
                                <a href="{{ route('user.vector') }}"
                                    class="block py-2 pr-4 pl-3 {{ request()->routeIs('user.vector') ? 'text-primary font-medium' : 'text-gray-700' }} border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0  text-xs 2xl:text-sm">Vector</a>
                            </li>
                            <li>
                                <a href="{{ route('user.larvae') }}"
                                    class="block py-2 pr-4 pl-3 {{ request()->routeIs('user.larvae') ? 'text-primary font-medium' : 'text-gray-700' }} border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0  text-xs 2xl:text-sm">Larvae</a>
                            </li>
                            <li>
                                <a href="{{ route('user.ksh') }}"
                                    class="block py-2 pr-4 pl-3 {{ request()->routeIs('user.ksh') ? 'text-primary font-medium' : 'text-gray-700' }} border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0  text-xs 2xl:text-sm">KSH</a>
                            </li>
                            <li class="lg:hidden">
                                @if (auth()->check())
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 text-xs 2xl:text-sm">Logout</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 text-xs 2xl:text-sm">Login</a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        {{ $slot }}

        <footer class="sm:flex sm:items-center sm:justify-center p-4 sm:p-6 xl:p-8 ">
            <p class="mb-4 text-xs 2xl:text-sm font-light text-center text-gray-500 dark:text-gray-400 sm:mb-0">
                &copy; 2023 <a href="/" class="hover:underline" target="_blank">Sivemo.com</a>.
                All
                rights reserved.
            </p>
            {{-- <div class="flex justify-center items-center space-x-1">
                <a href="#" data-tooltip-target="tooltip-facebook"
                    class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Facebook</span>
                </a>
                <div id="tooltip-facebook" role="tooltip"
                    class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                    Like us on Facebook
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <a href="#" data-tooltip-target="tooltip-twitter"
                    class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                    </svg>
                    <span class="sr-only">Twitter</span>
                </a>
                <div id="tooltip-twitter" role="tooltip"
                    class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                    Follow us on Twitter
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <a href="#" data-tooltip-target="tooltip-github"
                    class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Github</span>
                </a>
                <div id="tooltip-github" role="tooltip"
                    class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                    Star us on GitHub
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <a href="#" data-tooltip-target="tooltip-dribbble"
                    class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.26c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.705zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Dribbble</span>
                </a>
                <div id="tooltip-dribbble" role="tooltip"
                    class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                    Follow us on Dribbble
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div> --}}
        </footer>
    </div>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <!-- Flowbite -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>

    <!-- Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Marker Cluster -->
    <script src="{{ asset('assets/js/leaflet.markercluster.js') }}"></script>

    <!-- Fullscreen -->
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Chart Js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js"></script>

    <script>
        Chart.defaults.font.family = 'Lexend Deca, Outfit, Plus Jakarta Sans, sans-serif';
        Chart.defaults.scale.grid.display = false;
        Chart.defaults.scale.ticks.beginAtZero = true;
        Chart.defaults.scale.ticks.precision = 0;

        $('select').select2();
    </script>

    @stack('js-internal')
</body>

</html>
