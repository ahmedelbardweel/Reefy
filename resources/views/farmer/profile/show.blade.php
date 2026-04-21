<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0 text-gray-800 dark:text-gray-200">
            <i class="fas fa-id-card me-2 text-green-500"></i> {{ __('الملف الشخصي للمزارع') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Hero Profile Section -->
            <div class="relative mb-12">
                <!-- Cover Image -->
                <div class="h-64 sm:h-80 w-full rounded-[2.5rem] overflow-hidden shadow-2xl shadow-green-200/20 dark:shadow-none border-4 border-white dark:border-gray-800">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10"></div>
                    <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                         class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700" 
                         alt="Farmer Cover">
                </div>
                
                <!-- Profile Overlay -->
                <div class="absolute -bottom-10 right-10 left-10 z-20">
                    <div class="glass p-6 sm:p-8 rounded-[2rem] shadow-xl flex flex-col sm:flex-row items-center sm:items-end justify-between gap-6">
                        <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 text-center sm:text-right">
                            <!-- Avatar -->
                            <div class="h-32 w-32 rounded-3xl bg-white dark:bg-gray-800 p-2 shadow-2xl -mt-20 sm:-mt-24">
                                <div class="h-full w-full rounded-2xl bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-white text-5xl font-black shadow-inner">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                            <!-- Name & Bio -->
                            <div class="pb-1">
                                <h1 class="text-3xl font-black text-gray-900 dark:text-white flex items-center justify-center sm:justify-start">
                                    {{ $user->name }}
                                    <svg class="w-6 h-6 ms-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.633.326 1.223.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </h1>
                                <p class="text-gray-500 dark:text-gray-400 font-medium flex items-center justify-center sm:justify-start">
                                    <svg class="w-4 h-4 me-1.5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                    {{ $user->farmerProfile->city ?? __('غير محدد') }}، فلسطين
                                </p>
                            </div>
                        </div>
                        
                        <!-- Mini Stats -->
                        <div class="flex items-center gap-4 bg-white/40 dark:bg-white/5 p-2 rounded-2xl border border-white/50 dark:border-white/10 backdrop-blur-md">
                            <div class="text-center px-4 border-l border-white/20 last:border-0 grow">
                                <span class="block text-xs text-gray-400 uppercase tracking-tighter">{{ __('محصول') }}</span>
                                <span class="text-xl font-black text-gray-900 dark:text-white">{{ $user->crops->count() }}</span>
                            </div>
                            <div class="text-center px-4 grow">
                                <span class="block text-xs text-gray-400 uppercase tracking-tighter">{{ __('منذ') }}</span>
                                <span class="text-xl font-black text-gray-900 dark:text-white">{{ $user->created_at->format('Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- About Section -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700">
                        <h4 class="text-lg font-black text-gray-900 dark:text-white mb-6">{{ __('عن المزارع') }}</h4>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed italic border-r-4 border-green-500 pr-4">
                            {{ $user->farmerProfile->bio ?? __('لا توجد نبذة تعريفية منشورة حالياً لهذا المزارع الموثوق.') }}
                        </p>
                    </div>
                    
                    <div class="p-6 rounded-[2rem] bg-gradient-to-br from-green-600 to-emerald-700 text-white shadow-xl">
                        <h5 class="font-bold mb-2 flex items-center">
                            <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('معلومات التواصل') }}
                        </h5>
                        <p class="text-sm text-green-50 opacity-90">{{ __('بيانات التواصل متاحة فقط للمسؤولين والخبراء المعتمدين.') }}</p>
                    </div>
                </div>

                <!-- Crops Showcase -->
                <div class="lg:col-span-2">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-8 flex items-center">
                        <span class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center me-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                        </span>
                        {{ __('المحاصيل المزروعة') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @forelse($user->crops as $crop)
                        <div class="group relative bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:shadow-green-100 dark:hover:shadow-none transition-all duration-300">
                            <div class="flex items-start justify-between">
                                <div class="p-4 rounded-2xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 mb-4 group-hover:rotate-12 transition-transform">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21V11m0 0l-4 4m4-4l4 4m-4-11V3"/></svg>
                                </div>
                                <span class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $crop->type }}</span>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $crop->name }}</h4>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">{{ __('مساحة الأرض:') }} {{ $crop->area }} {{ __('فدان') }}</p>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-green-400 to-emerald-600 h-full w-2/3 rounded-full"></div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full bg-white dark:bg-gray-800 rounded-[2.5rem] p-16 text-center shadow-sm border border-gray-100 dark:border-gray-700">
                            <div class="h-20 w-20 bg-gray-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <h5 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('لا توجد محاصيل حالياً') }}</h5>
                            <p class="text-gray-500">{{ __('لم وقم هذا المزارع بإضافة أي محاصيل إلى ملفه الشخصي بعد.') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
