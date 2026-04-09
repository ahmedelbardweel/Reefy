<x-app-layout>
    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen font-sans" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm overflow-hidden mb-8 border border-gray-100 dark:border-gray-700">
                <div class="h-48 bg-gradient-to-l from-emerald-600 to-green-400 relative">
                    @if(Auth::user()->cover_image)
                        <img src="{{ asset(Auth::user()->cover_image) }}" class="w-full h-full object-cover opacity-60">
                    @endif

                    <a href="{{ route('profile.edit') }}" class="absolute top-4 left-4 bg-white/20 hover:bg-white/40 backdrop-blur-md text-white px-5 py-2 rounded-2xl text-sm font-bold transition-all flex items-center gap-2">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('Edit Profile') }}
                    </a>
                </div>

             <div class="px-8 pb-8">
                <div class="flex flex-col md:flex-row items-end -mt-12 gap-6">
                        <div class="relative">
                          <div class="w-32 h-32 rounded-3xl bg-emerald-500 border-4 border-white dark:border-gray-800 shadow-xl overflow-hidden flex items-center justify-center">
                     @if(Auth::user()->avatar)
                         <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                      <span class="text-white text-4xl font-black">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                      </span>
                   @endif
               </div>
          </div>

                        <div class="flex-1 pb-2 text-right">
                            <h1 class="text-3xl font-black text-gray-900 dark:text-white">{{ Auth::user()->name }}</h1>
                            <div class="flex flex-wrap items-center gap-3 mt-2">
                                <span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 px-4 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                    @if(Auth::user()->role == 'farmer')
                                        <i class="bi bi-tree-fill"></i> {{ __('Farmer') }}
                                    @else
                                        <i class="bi bi-patch-check-fill"></i> {{ __('Expert') }}
                                    @endif
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 text-sm flex items-center gap-1">
                                    <i class="bi bi-envelope-at"></i>
                                    {{ Auth::user()->email }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-right">

                <div class="lg:col-span-2 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm group hover:shadow-md transition-all">
                            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl text-emerald-600 flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                <i class="bi bi-flower1 text-2xl"></i>
                            </div>
                            <p class="text-gray-400 dark:text-gray-500 text-xs font-bold mb-1">{{ __('Total Crops') }}</p>
                            <p class="text-2xl font-black text-gray-900 dark:text-white">{{ Auth::user()->crops()->count() }}</p>
                        </div>

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm group hover:shadow-md transition-all">
                            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/20 rounded-2xl text-amber-600 flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                <i class="bi bi-calendar2-check text-2xl"></i>
                            </div>
                            <p class="text-gray-400 dark:text-gray-500 text-xs font-bold mb-1">{{ __('Pending Tasks') }}</p>
                            <p class="text-2xl font-black text-gray-900 dark:text-white">
                                {{ Auth::user()->crops->sum(fn($c) => $c->tasks()->where('status','pending')->count()) }}
                            </p>
                        </div>

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm group hover:shadow-md transition-all">
                            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl text-blue-600 flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                <i class="bi bi-chat-text text-2xl"></i>
                            </div>
                            <p class="text-gray-400 dark:text-gray-500 text-xs font-bold mb-1">{{ __('Consultations') }}</p>
                            <p class="text-2xl font-black text-gray-900 dark:text-white">{{ Auth::user()->consultations()->count() }}</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="p-6 border-b border-gray-50 dark:border-gray-700">
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg">{{ __('Personal Details') }}</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Full Name') }}</label>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Email Address') }}</label>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Joined At') }}</label>
                                <p class="text-gray-900 dark:text-white font-semibold">{{ Auth::user()->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase">{{ __('Account Status') }}</label>
                                <p class="text-emerald-500 font-bold flex items-center gap-1">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                    {{ __('Active') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 text-center">
                    <div class="bg-gradient-to-br from-emerald-600 to-green-700 rounded-3xl p-8 text-white shadow-xl shadow-emerald-200 dark:shadow-none relative overflow-hidden group">
                        <i class="bi bi-shield-lock text-6xl mb-4 block opacity-20 group-hover:scale-110 transition-transform"></i>
                        <h4 class="text-xl font-black mb-2">{{ __('Security Priority') }}</h4>
                        <p class="text-emerald-100 text-sm mb-6 leading-relaxed">{{ __('Security Description') }}</p>
                        <a href="{{ route('profile.edit') }}#update-password" class="block w-full py-3 bg-white text-emerald-700 font-black rounded-2xl hover:bg-emerald-50 transition-all shadow-lg active:scale-95">
                            {{ __('Update Password') }}
                        </a>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm border-b-4 border-b-emerald-500">
                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">
                            "{{ __('Reefy Quote') }}"
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
