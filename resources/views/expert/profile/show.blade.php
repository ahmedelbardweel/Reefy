<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0 text-gray-800 dark:text-gray-200">
            <i class="fas fa-user-graduate me-2 text-blue-500"></i> {{ __('الملف الشخصي للخبير') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Hero Expert Section -->
            <div class="relative mb-12">
                <!-- Cover Image -->
                <div class="h-64 sm:h-80 w-full rounded-[2.5rem] overflow-hidden shadow-2xl shadow-blue-200/20 dark:shadow-none border-4 border-white dark:border-gray-800">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10"></div>
                    <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                         class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700" 
                         alt="Expert Cover">
                </div>
                
                <!-- Profile Overlay -->
                <div class="absolute -bottom-10 right-10 left-10 z-20">
                    <div class="glass p-6 sm:p-8 rounded-[2rem] shadow-xl flex flex-col sm:flex-row items-center sm:items-end justify-between gap-6">
                        <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 text-center sm:text-right">
                            <!-- Avatar -->
                            <div class="h-32 w-32 rounded-3xl bg-white dark:bg-gray-800 p-2 shadow-2xl -mt-20 sm:-mt-24">
                                <div class="h-full w-full rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-5xl font-black shadow-inner">
                                    {{ $user->initials }}
                                </div>
                            </div>
                            <!-- Name & Status -->
                            <div class="pb-1">
                                <h1 class="text-3xl font-black text-gray-900 dark:text-white flex items-center justify-center sm:justify-start">
                                    {{ $user->name }}
                                    <svg class="w-6 h-6 ms-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.9L10 1.554l7.834 3.346a1.2 1.2 0 01.832 1.14V17c0 .663-.537 1.2-1.2 1.2h-15a1.2 1.2 0 01-1.2-1.2V6.04a1.2 1.2 0 01.832-1.14zM10 3.061L3.6 5.775V16.2h12.8V5.775L10 3.061zM10 7a2 2 0 100 4 2 2 0 000-4z" clip-rule="evenodd"></path></svg>
                                </h1>
                                <p class="text-gray-500 dark:text-gray-400 font-medium flex items-center justify-center sm:justify-start">
                                    <svg class="w-4 h-4 me-1.5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.35 1.055L4.144 13.62a1 1 0 00.442 1.21l5 2.5a1 1 0 00.828 0l5-2.5a1 1 0 00.442-1.21l-1.456-4.514a.999.999 0 01.35-1.055L17.394 6.92a1 1 0 000-1.84l-7-3z" /></svg>
                                    {{ __('خبير زراعي معتمد') }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Mini Stats -->
                        <div class="flex items-center gap-4 bg-white/40 dark:bg-white/5 p-2 rounded-2xl border border-white/50 dark:border-white/10 backdrop-blur-md">
                            <div class="text-center px-4 border-l border-white/20 last:border-0 grow">
                                <span class="block text-xs text-gray-400 uppercase tracking-tighter">{{ __('نصائح') }}</span>
                                <span class="text-xl font-black text-gray-900 dark:text-white">{{ $user->expertTips->count() }}</span>
                            </div>
                            <div class="text-center px-4 grow">
                                <span class="block text-xs text-gray-400 uppercase tracking-tighter">{{ __('تقييم') }}</span>
                                <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">4.9</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Expert Bio Section -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700">
                        <h4 class="text-lg font-black text-gray-900 dark:text-white mb-6">{{ __('عن الخبير') }}</h4>
                        <div class="prose dark:prose-invert prose-sm">
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ $user->expertProfile->bio ?? __('خبير زراعي متخصص في تقديم الاستشارات الفنية والحلول المبتكرة للمزارعين لتعزيز الإنتاجية والاستدامة.') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="p-6 rounded-[2rem] bg-gradient-to-br from-indigo-600 to-blue-700 text-white shadow-xl">
                        <h5 class="font-bold mb-2 flex items-center">
                            <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            {{ __('طلب استشارة خاصة') }}
                        </h5>
                        <p class="text-sm text-indigo-50 opacity-90">{{ __('يمكنك التواصل مع الخبير مباشرة للحصول على حلول مخصصة لمزرعتك.') }}</p>
                    </div>
                </div>

                <!-- Expert Tips Showcase -->
                <div class="lg:col-span-2">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-8 flex items-center">
                        <span class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center me-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464a1 1 0 10-1.414-1.414l.707-.707a1 1 0 001.414 1.414l-.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1a1 1 0 112 0v1a1 1 0 11-2 0zM13 16v-1a1 1 0 112 0v1a1 1 0 11-2 0zM14.243 14.243a1 1 0 10-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM6.464 14.243a1 1 0 10-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707z"/></svg>
                        </span>
                        {{ __('أهم النصائح والتعليمات') }}
                    </h3>

                    <div class="space-y-6">
                        @forelse($user->expertTips as $tip)
                        <div class="group bg-white dark:bg-gray-800 rounded-[2rem] p-8 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <span class="bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 px-3 py-1 rounded-full text-xs font-bold">{{ __('نصيحة فنية') }}</span>
                                <span class="text-xs text-gray-400 italic">{{ $tip->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $tip->title }}</h4>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-6">{{ Str::limit($tip->content, 200) }}</p>
                            <div class="flex justify-end">
                                <button class="text-blue-600 font-bold text-sm flex items-center group-hover:underline">
                                    {{ __('قراءة المزيد') }}
                                    <svg class="w-4 h-4 ms-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-16 text-center shadow-sm border border-gray-100 dark:border-gray-700">
                            <h5 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('لا توجد نصائح حالياً') }}</h5>
                            <p class="text-gray-500">{{ __('لم وقم هذا الخبير بإضافة أي نصائح تعليمية حتى الآن.') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
