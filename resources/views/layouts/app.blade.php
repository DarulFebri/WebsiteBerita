<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Berita App') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles (Tailwind CSS via CDN, No NPM) -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            /* Styling untuk validasi error */
            .is-invalid {
                border-color: #ef4444; /* red-500 */
            }
            .invalid-feedback {
                color: #ef4444; /* red-500 */
                font-size: 0.875rem; /* text-sm */
                margin-top: 0.25rem; /* mt-1 */
            }
        </style>

        <!-- Scripts -->
        {{-- Baris @vite berikut telah dihapus karena tidak menggunakan NPM --}}
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <!-- Alpine.js CDN for dropdown functionality -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-100 min-h-screen flex flex-col">
        {{-- Menambahkan x-data untuk Alpine.js agar dapat mengelola status 'open' menu seluler --}}
        <div class="min-h-screen bg-gray-100" x-data="{ open: false }">
            <nav class="bg-white border-b border-gray-100 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo Aplikasi -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('berita.index') }}">
                                    <h1 class="text-2xl font-bold text-gray-800">Berita App</h1>
                                </a>
                            </div>

                            <!-- Navigasi Utama -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <x-nav-link :href="route('berita.index')" :active="request()->routeIs('berita.index')">
                                    {{ __('Beranda Berita') }}
                                </x-nav-link>
                                @auth
                                    @if(Auth::user()->isAdmin())
                                        <x-nav-link :href="route('admin.news.index')" :active="request()->routeIs('admin.news.*')">
                                            {{ __('Manajemen Berita (Admin)') }}
                                        </x-nav-link>
                                    @endif
                                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                        {{ __('Dashboard User') }}
                                    </x-nav-link>
                                @endauth
                            </div>
                        </div>

                        <!-- Pengaturan Dropdown & Auth Links -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            @auth
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div>{{ Auth::user()->name }} ({{ Auth::user()->role }})</div>
                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profil') }}
                                        </x-dropdown-link>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @else
                                <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                                @endif
                            @endauth
                        </div>

                        <!-- Burger menu (untuk mobile) -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Navigasi Responsif (untuk mobile) -->
                <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <x-responsive-nav-link :href="route('berita.index')" :active="request()->routeIs('berita.index')">
                            {{ __('Beranda Berita') }}
                        </x-responsive-nav-link>
                        @auth
                            @if(Auth::user()->isAdmin())
                                <x-responsive-nav-link :href="route('admin.news.index')" :active="request()->routeIs('admin.news.*')">
                                    {{ __('Manajemen Berita (Admin)') }}
                                </x-responsive-nav-link>
                            @endif
                            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard User') }}
                                </x-responsive-nav-link>
                        @endauth
                    </div>

                    <!-- Pengaturan Responsif -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        @auth
                            <div class="px-4">
                                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <x-responsive-nav-link :href="route('profile.edit')">
                                    {{ __('Profil') }}
                                </x-responsive-nav-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-responsive-nav-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-responsive-nav-link>
                                </form>
                            </div>
                        @else
                            <div class="px-4 py-2 space-y-1">
                                <x-responsive-nav-link :href="route('login')">
                                    {{ __('Log in') }}
                                </x-responsive-nav-link>
                                @if (Route::has('register'))
                                    <x-responsive-nav-link :href="route('register')">
                                        {{ __('Register') }}
                                    </x-responsive-nav-link>
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div >
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
