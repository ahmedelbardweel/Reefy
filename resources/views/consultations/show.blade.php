<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Consultation Details') }}
        </h2>
    </x-slot>

    <div class="py-8 px-4">
        <div class="max-w-3xl mx-auto space-y-6">
            
            <!-- Question Card -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $consultation->status == 'answered' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                            {{ $consultation->status == 'answered' ? __('Answered') : __('Waiting for Expert') }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $consultation->created_at->diffForHumans() }}</span>
                    </div>

                    <h3 class="text-xl font-bold text-green-700 dark:text-green-400 mb-4">{{ $consultation->subject }}</h3>
                    
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg mb-4 text-gray-800 dark:text-gray-200 text-sm leading-relaxed whitespace-pre-wrap">{{ $consultation->question }}</div>

                    <div class="flex gap-4 text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-3">
                         <span class="flex items-center gap-1"><i class="bi bi-tag-fill text-gray-400"></i> {{ $consultation->category }}</span>
                        @if($consultation->crop)
                            <span class="flex items-center gap-1"><i class="bi bi-flower1 text-gray-400"></i> {{ $consultation->crop->name }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Response Section -->
            @if($consultation->status == 'answered')
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-l-4 border-gray-100 dark:border-gray-700 border-l-green-500 overflow-hidden relative">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white shadow-md">
                                <i class="bi bi-person-badge text-lg"></i>
                            </div>
                            <div>
                                <h6 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Expert Response: :name', ['name' => $consultation->expert->name]) }}</h6>
                                <span class="text-xs text-gray-400">{{ $consultation->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        
                        <div class="bg-green-50/50 dark:bg-green-900/10 p-4 rounded-lg border border-green-100 dark:border-green-800/30 text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-wrap">
                            {{ $consultation->response }}
                        </div>
                    </div>
                </div>
            @else
                @if(auth()->user()->role === 'expert')
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                        <h5 class="font-bold text-lg text-gray-900 dark:text-white mb-4">{{ __('Provide Expert Advice') }}</h5>
                        <form action="{{ route('consultations.answer', $consultation) }}" method="POST">
                            @csrf
                            <textarea name="response" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 mb-4 text-sm" rows="6" placeholder="{{ __('Write your response here with integrity and knowledge...') }}" required></textarea>
                            <x-primary-button class="w-full justify-center bg-green-600 hover:bg-green-700">
                                {{ __('Send Response to Farmer') }}
                            </x-primary-button>
                        </form>
                    </div>
                @else
                    <div class="bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-100 dark:border-yellow-900/30 rounded-xl p-6 text-center">
                        <div class="inline-flex p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 mb-3">
                            <i class="bi bi-hourglass-split text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-400">{{ __('Your request is under review by our experts. You will be notified once answered.') }}</p>
                    </div>
                @endif
            @endif

            <div class="text-center pt-2">
                <a href="{{ auth()->user()->role === 'expert' ? route('expert.consultations.index') : route('consultations.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition flex items-center justify-center gap-1">
                    <i class="bi bi-arrow-right"></i> {{ __('Back to List') }}
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
