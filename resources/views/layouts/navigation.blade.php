@php
    $navId = 'mobile-menu-toggle';
@endphp
<nav class="fixed w-full top-0 z-50 shadow-sm bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <input type="checkbox" id="{{ $navId }}" class="peer hidden">
    
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-16">
            <!-- Logo Section -->
            <div class="flex-1 flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-green-700 dark:text-green-400 font-bold text-xl">
                    {{ __('Reefy') }}
                </a>
            </div>

            <!-- Navigation Links (Absolute Center) -->
            <div class="hidden sm:flex items-center sm:gap-10">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('community.index')" :active="request()->routeIs('community.*')">
                        {{ __('Community') }}
                    </x-nav-link>

                    @auth
                        @if(auth()->user()->role === 'farmer')
                            <x-nav-link :href="route('crops.index')" :active="request()->routeIs('crops.*')">
                                {{ __('Crops') }}
                            </x-nav-link>

                            <!-- Systems Dropdown -->
                            <div class="relative flex items-center">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <div class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 cursor-pointer" tabindex="0">
                                            <div>{{ __('Smart Systems') }}</div>
                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('farmer.systems.irrigation')">
                                            <i class="bi bi-water text-blue-500 mr-2"></i> {{ __('Irrigation System') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('farmer.systems.treatment')">
                                            <i class="bi bi-shield-plus text-red-500 mr-2"></i> {{ __('Treatment Center') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('farmer.systems.harvesting')">
                                            <i class="bi bi-box-seam text-yellow-500 mr-2"></i> {{ __('Harvest Tracking') }}
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif

                        @php
                            $consultRoute = auth()->user()->role === 'expert' ? 'expert.consultations.index' : 'consultations.index';
                        @endphp
                        <x-nav-link :href="route($consultRoute)" :active="request()->routeIs('consultations.*') || request()->routeIs('expert.consultations.*')">
                            {{ __('Consultations') }}
                        </x-nav-link>
                    @endauth
                </div>

            <!-- Settings Section -->
            <div class="flex-1 hidden sm:flex items-center justify-end sm:ms-6">
                @auth
                    <!-- Language Switcher -->
                    @if(app()->getLocale() == 'ar')
                        <a href="{{ route('lang.switch', 'en') }}" class="h-10 px-3 flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-all" title="English">
                             <span class="text-[10px] font-black uppercase leading-none">EN</span>
                             <i class="bi bi-globe2 text-[18px]"></i>
                        </a>
                    @else
                        <a href="{{ route('lang.switch', 'ar') }}" class="h-10 px-3 flex items-center gap-1.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-all" title="العربية">
                            <span class="text-[10px] font-black uppercase leading-none">AR</span>
                            <i class="bi bi-globe2 text-[18px]"></i>
                        </a>
                    @endif

                    <!-- Dark Mode Toggle (Server side) -->
                    <a href="{{ route('theme.toggle') }}"
                            class="w-10 h-10 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full focus:outline-none transition-all">
                        @if(session('theme') === 'dark')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        @endif
                    </a>

                    <!-- Notifications -->
                    <a href="{{ route('notifications.index') }}" class="relative w-10 h-10 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </a>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <div class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 cursor-pointer" tabindex="0">
                                <div class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center text-[13px] font-black border border-gray-200 dark:border-gray-600 shadow-sm transition-all group-hover:bg-gray-200 dark:group-hover:bg-gray-600">
                                    {{ Auth::user()->initials }}
                                </div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Theme Toggle -->
                             <x-dropdown-link :href="route('theme.toggle')">
                                <i class="bi bi-moon-stars mr-2"></i> {{ __('Change Theme') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-start px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                    <i class="bi bi-box-arrow-left text-red-500 mr-2"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="ms-4 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">{{ __('Join Us') }}</a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <label for="{{ $navId }}" class="inline-flex items-center justify-center p-2 text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out cursor-pointer">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path class="menu-open-icon inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path class="menu-close-icon hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </label>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div class="hidden peer-checked:block sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('community.index')" :active="request()->routeIs('community.*')">
                {{ __('Community') }}
            </x-responsive-nav-link>

            @auth
                @if(auth()->user()->role === 'farmer')
                    <x-responsive-nav-link :href="route('crops.index')" :active="request()->routeIs('crops.*')">
                        {{ __('Crops') }}
                    </x-responsive-nav-link>

                    <div class="px-4 pt-2 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Smart Systems') }}</div>
                    </div>

                    <x-responsive-nav-link :href="route('farmer.systems.irrigation')">
                        <i class="bi bi-water text-blue-500 mr-2"></i> {{ __('Irrigation System') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('farmer.systems.treatment')">
                        <i class="bi bi-shield-plus text-red-500 mr-2"></i> {{ __('Treatment Center') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('farmer.systems.harvesting')">
                        <i class="bi bi-box-seam text-yellow-500 mr-2"></i> {{ __('Harvest Tracking') }}
                    </x-responsive-nav-link>
                @endif

                @php
                    $consultRoute = auth()->user()->role === 'expert' ? 'expert.consultations.index' : 'consultations.index';
                @endphp
                <x-responsive-nav-link :href="route($consultRoute)" :active="request()->routeIs('consultations.*') || request()->routeIs('expert.consultations.*')">
                    {{ __('Consultations') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('notifications.index')">
                    {{ __('Notifications') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <!-- Mobile Language Toggle -->
                <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" 
                   class="w-full text-start flex items-center px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-translate text-lg"></i>
                        <span>{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
                    </div>
                </a>

                <a href="{{ route('theme.toggle') }}" 
                        class="w-full text-start flex items-center px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                    <div class="flex items-center gap-2">
                        @if(session('theme') === 'dark')
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        @endif
                        <span>{{ __('Change Theme') }}</span>
                    </div>
                </a>

                <div class="px-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center text-sm font-black border border-gray-200 dark:border-gray-600">
                        {{ Auth::user()->initials }}
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-start flex items-center px-4 py-2 text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                            <i class="bi bi-box-arrow-left mr-2"></i> {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>

<style>
    #{{ $navId }}:checked ~ .max-w-7xl .menu-open-icon { display: none; }
    #{{ $navId }}:checked ~ .max-w-7xl .menu-close-icon { display: inline-flex; }
</style>

