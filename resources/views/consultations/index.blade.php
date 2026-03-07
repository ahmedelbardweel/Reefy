<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-chat-right-dots-fill text-green-600"></i> {{ __('Agricultural Consultations') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Get professional advice from our verified agricultural experts') }}</p>
            </div>
            <a href="{{ route('consultations.create') }}" class="group flex items-center gap-3 px-6 py-3 bg-green-600 hover:bg-green-700 text-white shadow-lg transition active:scale-[0.98]">
                <span class="text-sm font-black uppercase tracking-widest">{{ __('Request New Consultation') }}</span>
                <i class="bi bi-plus-lg transition-transform group-hover:rotate-90"></i>
            </a>
        </div>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        @if($consultations->isEmpty())
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-16 text-center shadow-sm">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 mb-8">
                    <i class="bi bi-patch-question text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ __('Need expert advice?') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed mb-8">
                    {{ __('Our team of agricultural specialists is ready to help you with any issues your crops might be facing.') }}
                </p>
                <div class="flex justify-center">
                    <a href="{{ route('consultations.create') }}" class="px-8 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-black uppercase tracking-widest hover:bg-black dark:hover:bg-gray-100 transition shadow-xl">
                        {{ __('Request your first consultation') }}
                    </a>
                </div>
            </div>
        @else
            <div class="mb-8 flex items-center gap-4">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] whitespace-nowrap">{{ __('My Consultations') }} ({{ $consultations->count() }})</span>
                <div class="h-px w-full bg-gray-100 dark:bg-gray-800"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($consultations as $consultation)
                    <div class="group bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all flex flex-col h-full relative overflow-hidden">
                        <!-- Top Progress Bar (Visual only) -->
                        <div class="absolute top-0 left-0 right-0 h-1 {{ $consultation->status == 'answered' ? 'bg-green-600' : 'bg-yellow-500' }}"></div>

                        <div class="p-8 flex-1">
                            <div class="flex justify-between items-start mb-6">
                                <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-widest {{ $consultation->status == 'answered' ? 'bg-green-600 text-white' : 'bg-yellow-500 text-white' }}">
                                    {{ $consultation->status == 'answered' ? __('Answered') : __('Pending') }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-bold">{{ $consultation->created_at->format('M d, Y') }}</span>
                            </div>

                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-3 group-hover:text-green-600 transition-colors line-clamp-2 leading-tight">
                                {{ $consultation->subject }}
                            </h4>

                            <div class="flex flex-wrap items-center gap-3 mb-6">
                                <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase border border-gray-100 dark:border-gray-700 px-2 py-0.5 bg-gray-50 dark:bg-gray-700/30">
                                    <i class="bi bi-tag-fill text-green-600"></i>
                                    {{ $consultation->category }}
                                </div>
                                @if($consultation->crop)
                                    <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase border border-gray-100 dark:border-gray-700 px-2 py-0.5 bg-gray-50 dark:bg-gray-700/30">
                                        <i class="bi bi-flower1 text-green-600"></i>
                                        {{ $consultation->crop->name }}
                                    </div>
                                @endif
                            </div>

                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 leading-relaxed italic">
                                "{{ Str::limit($consultation->question, 150) }}"
                            </p>
                        </div>

                        <div class="p-6 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-50 dark:border-gray-700">
                            <a href="{{ route('consultations.show', $consultation) }}" class="flex items-center justify-center gap-2 w-full py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-black uppercase tracking-widest border border-gray-200 dark:border-gray-600 hover:bg-gray-900 hover:text-white dark:hover:bg-white dark:hover:text-gray-900 transition shadow-sm">
                                <span>{{ $consultation->status == 'answered' ? __('Check Answer') : __('View Inquiry') }}</span>
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
