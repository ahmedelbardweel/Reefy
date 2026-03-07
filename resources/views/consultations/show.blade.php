<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ auth()->user()->role === 'expert' ? route('expert.consultations.index') : route('consultations.index') }}" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-gray-800 text-gray-500 hover:text-green-600 border border-gray-100 dark:border-gray-700 shadow-sm transition">
                <i class="bi bi-arrow-right fs-5"></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">
                    {{ __('Consultation Details') }}
                </h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 text-[9px] font-black uppercase tracking-widest {{ $consultation->status == 'answered' ? 'bg-green-600 text-white' : 'bg-yellow-500 text-white' }}">
                        {{ $consultation->status == 'answered' ? __('Answered') : __('Pending') }}
                    </span>
                    <span class="text-[10px] text-gray-400 font-medium">Ref: #{{ str_pad($consultation->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 px-4 max-w-4xl mx-auto">
        <div class="space-y-8">
            
            <!-- Farmer Section -->
            <div class="relative group">
                <div class="absolute -right-4 top-0 bottom-0 w-1 bg-green-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center text-gray-400 border border-gray-100 dark:border-gray-600">
                                    <i class="bi bi-person-fill text-xl"></i>
                                </div>
                                <div>
                                    <h5 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-1">{{ __('Farmer Inquiry') }}</h5>
                                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $consultation->user->name }}</h4>
                                </div>
                            </div>
                            <span class="text-[11px] font-medium text-gray-400 bg-gray-50 dark:bg-gray-700/30 px-2 py-1 border border-gray-100 dark:border-gray-600 uppercase">{{ $consultation->created_at->diffForHumans() }}</span>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">{{ $consultation->subject }}</h3>
                        
                        <div class="relative">
                            <i class="bi bi-quote absolute -top-4 -right-4 text-4xl text-gray-100 dark:text-gray-700/50 -z-10"></i>
                            <div class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed italic border-r-4 border-green-100 dark:border-green-900/30 pr-6 py-2">
                                {{ $consultation->question }}
                            </div>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-4 pt-6 border-t border-gray-50 dark:border-gray-700">
                             <div class="flex items-center gap-2 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tighter">
                                <i class="bi bi-tag-fill text-green-600"></i>
                                <span>{{ $consultation->category }}</span>
                             </div>
                            @if($consultation->crop)
                                <div class="flex items-center gap-2 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tighter">
                                    <i class="bi bi-flower1 text-green-600"></i>
                                    <span>{{ $consultation->crop->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Response Section -->
            @if($consultation->status == 'answered')
                <div class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 border border-gray-800 dark:border-gray-100 shadow-xl overflow-hidden relative">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <i class="bi bi-mortarboard-fill text-6xl"></i>
                    </div>
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-green-600 flex items-center justify-center text-white border-2 border-white dark:border-gray-900 shadow-lg {{ app()->getLocale() == 'ar' ? 'rounded-full' : '' }}">
                                <i class="bi bi-patch-check-fill text-xl"></i>
                            </div>
                            <div>
                                <h5 class="text-xs font-black uppercase tracking-widest text-green-500 mb-1">{{ __('The Expert Advice') }}</h5>
                                <h4 class="font-bold">{{ $consultation->expert->name }}</h4>
                            </div>
                        </div>
                        
                        <div class="text-lg leading-relaxed font-medium">
                            {{ $consultation->response }}
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/10 dark:border-gray-100 flex justify-between items-center text-[10px] font-black uppercase tracking-widest opacity-60">
                            <span>{{ __('Consultation Completed') }}</span>
                            <span>{{ $consultation->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            @else
                @if(auth()->user()->role === 'expert')
                    <div class="bg-white dark:bg-gray-800 border-2 border-green-600 shadow-2xl p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 flex items-center justify-center text-green-600 bg-green-50 dark:bg-green-900/20">
                                <i class="bi bi-pen-fill"></i>
                            </div>
                            <h5 class="font-black text-xs uppercase tracking-[0.2em] text-gray-900 dark:text-white">{{ __('Provide Professional Advice') }}</h5>
                        </div>

                        <form action="{{ route('consultations.answer', $consultation) }}" method="POST">
                            @csrf
                            <textarea name="response" 
                                class="w-full bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-gray-900 dark:text-white p-5 text-sm leading-relaxed mb-6 placeholder-gray-400 italic" 
                                rows="8" 
                                placeholder="{{ __('Write your expert response here. Be precise and helpful...') }}" 
                                required></textarea>
                            
                            <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-700 text-white text-sm font-black uppercase tracking-widest shadow-lg transition active:scale-[0.99] flex items-center justify-center gap-3">
                                <i class="bi bi-send-check"></i>
                                {{ __('Finalize Advice & Send to Farmer') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-200 dark:border-yellow-800/30 p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 mb-6">
                            <i class="bi bi-hourglass-split text-3xl animate-pulse"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Waiting for Expert Review') }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">
                            {{ __('Your request has been forwarded to our specialists. You will receive a notification as soon as an advice is provided.') }}
                        </p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
