<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold mb-1 text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-chat-dots-fill text-green-600"></i> {{ __('Agricultural Consultations') }}
            </h2>
            <a href="{{ route('consultations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                <i class="bi bi-plus-lg ml-2"></i> {{ __('Request New Consultation') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            @if($consultations->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 p-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 mb-4">
                        <i class="bi bi-chat-left-text text-3xl"></i>
                    </div>
                    <h5 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('You have not requested any consultations yet') }}</h5>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">{{ __('You can contact our experts to get advice on your crops.') }}</p>
                    <a href="{{ route('consultations.create') }}" class="inline-flex items-center px-4 py-2 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 dark:bg-transparent dark:text-green-400 dark:border-green-500 dark:hover:bg-green-900/20 transition">
                        {{ __('Request your first consultation') }}
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($consultations as $consultation)
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition flex flex-col h-full">
                            <div class="p-5 flex-1">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $consultation->status == 'answered' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                        {{ $consultation->status == 'answered' ? __('Answered') : __('Waiting for Expert') }}
                                    </span>
                                    <span class="text-xs text-gray-400 font-medium">{{ $consultation->created_at->format('Y-m-d') }}</span>
                                </div>
                                <h5 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1">{{ $consultation->subject }}</h5>
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 mb-3">
                                    <span class="flex items-center gap-1"><i class="bi bi-tag-fill text-gray-400"></i> {{ $consultation->category }}</span>
                                    @if($consultation->crop)
                                        <span class="flex items-center gap-1"><i class="bi bi-flower1 text-gray-400"></i> {{ $consultation->crop->name }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed">{{ $consultation->question }}</p>
                            </div>
                            <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 rounded-b-xl">
                                <a href="{{ route('consultations.show', $consultation) }}" class="block w-full text-center text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 transition">
                                    {{ __('View Details') }} {{ $consultation->status == 'answered' ? __('and Answer') : '' }} <i class="bi bi-arrow-left mr-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
