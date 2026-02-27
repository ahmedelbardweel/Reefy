<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reefy - Smart Agriculture</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <nav x-data="{ open: false }" class="absolute w-full z-10 top-0 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <div class="flex items-center">
                    <a href="#" class="flex items-center gap-2 text-white font-bold text-3xl drop-shadow-md">
                        {{ __('Reefy') }}
                    </a>
                </div>
                <div class="hidden md:flex space-x-reverse space-x-8">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-bold transition shadow-lg">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-green-200 font-semibold px-4 py-2 drop-shadow-md">{{ __('Login') }}</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2 bg-white text-green-700 hover:bg-gray-100 font-bold transition shadow-lg">{{ __('Join Us') }}</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative h-screen flex items-center justify-center bg-gray-900 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=95" alt="Agriculture" class="w-full h-full object-cover opacity-50">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-black/30"></div>
        </div>
        
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 drop-shadow-lg tracking-tight leading-tight">
                {{ __('Future of Agriculture') }} <br> <span class="text-green-400">{{ __('In your hands') }}</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 mb-10 leading-relaxed max-w-3xl mx-auto drop-shadow-md">
                {{ __('Reefy platform is your smart partner to manage your farm effectively, connect with experts, and grow your crops to new horizons.') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold text-lg transition shadow-xl transform hover:scale-105">{{ __('Start your journey now') }}</a>
                <a href="#features" class="px-8 py-4 bg-transparent border-2 border-white text-white hover:bg-white hover:text-green-900 font-bold text-lg transition shadow-lg backdrop-blur-sm">{{ __('Discover Features') }}</a>
            </div>
        </div>
    </div>
</body>
</html>