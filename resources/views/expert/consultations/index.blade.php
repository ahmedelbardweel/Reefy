<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-mortarboard-fill text-green-600"></i> {{ __('Expert Dashboard') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Manage and respond to farmer consultation requests') }}</p>
            </div>
            <div class="hidden sm:flex items-center gap-3">
                <span class="px-3 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-[10px] font-black uppercase tracking-widest border border-green-100 dark:border-green-800">
                    {{ __('Verified Expert') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
        @if($consultations->isEmpty())
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-12 text-center shadow-sm">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 mb-6">
                    <i class="bi bi-check-lg text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Excellent Work!') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                    {{ __('You have answered all pending consultation requests. Your expertise is making a difference for the farming community!') }}
                </p>
                <div class="mt-8">
                    <a href="{{ route('expert.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold hover:bg-black dark:hover:bg-gray-100 transition shadow-lg">
                        <i class="bi bi-speedometer2"></i> {{ __('Back to Dashboard') }}
                    </a>
                </div>
            </div>
        @else
            <div class="flex items-center gap-3 mb-6">
                <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('Pending Requests') }} ({{ $consultations->count() }})</span>
                <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
            </div>

            <div class="space-y-4">
                @foreach($consultations as $consultation)
                    <div class="group bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-green-200 dark:hover:border-green-900 transition-all flex flex-col md:flex-row overflow-hidden">
                        <!-- Left Decoration -->
                        <div class="w-full md:w-1 bg-green-600 dark:bg-green-500 shrink-0"></div>
                        
                        <!-- Content -->
                        <div class="flex-1 p-6">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 text-[10px] font-bold border border-gray-200 dark:border-gray-600 uppercase">
                                    {{ __($consultation->category) }}
                                </span>
                                <div class="flex items-center gap-1.5 text-[11px] text-gray-500 dark:text-gray-400">
                                    <i class="bi bi-person-circle"></i>
                                    <span class="font-bold text-gray-700 dark:text-gray-300">{{ $consultation->user->name }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-[11px] text-gray-400">
                                    <i class="bi bi-clock"></i>
                                    <span>{{ $consultation->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2 group-hover:text-green-600 transition-colors">
                                {{ $consultation->subject }}
                            </h4>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-2 italic mb-4">
                                "{{ Str::limit($consultation->question, 180) }}"
                            </p>

                            @if($consultation->crop)
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-xs font-bold border border-green-100/50 dark:border-green-800/50">
                                    <i class="bi bi-sprout"></i>
                                    {{ __('Related Crop') }}: {{ $consultation->crop->name }}
                                </div>
                            @endif
                        </div>

                        <!-- Action -->
                        <div class="md:w-56 bg-gray-50 dark:bg-gray-700/30 p-6 flex flex-col justify-center items-center gap-3 border-t md:border-t-0 md:border-l border-gray-100 dark:border-gray-700">
                            <a href="{{ route('consultations.show', $consultation) }}" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white text-xs font-black uppercase text-center shadow-sm transition active:scale-[0.98]">
                                {{ __('Provide Advice') }}
                            </a>
                            <p class="text-[9px] text-gray-400 text-center uppercase tracking-tighter">{{ __('Farmer is waiting for your response') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
