<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                    {{ __('Expert Command Center') }}
                </h1>
                <p class="text-xs text-gray-400 font-medium mt-1">
                    {{ __('Welcome back, Dr. :name. Here is your activity brief for today.', ['name' => Auth::user()->name]) }}
                </p>
            </div>
            <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700 px-4 py-2">
                <i class="bi bi-calendar3 text-green-600"></i>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300">{{ now()->translatedFormat('l, d M Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- KPI Pillars -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10 text-start">
            <!-- Pending Consultations -->
            <div class="bg-white dark:bg-gray-800 border-r-4 border-yellow-500 shadow-sm p-6 group hover:translate-y-[-2px] transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">{{ __('Pending Requests') }}</span>
                    <i class="bi bi-hourglass-split text-yellow-500 text-xl"></i>
                </div>
                <h3 class="font-black text-4xl mb-1 text-gray-900 dark:text-white">{{ $pendingCount }}</h3>
                <div class="text-[10px] text-yellow-600 font-bold uppercase tracking-widest">{{ __('Requires Action') }}</div>
            </div>

            <!-- Answered -->
            <div class="bg-white dark:bg-gray-800 border-r-4 border-green-600 shadow-sm p-6 group hover:translate-y-[-2px] transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">{{ __('Total Advice Given') }}</span>
                    <i class="bi bi-patch-check-fill text-green-600 text-xl"></i>
                </div>
                <h3 class="font-black text-4xl mb-1 text-gray-900 dark:text-white">{{ $answeredCount }}</h3>
                <div class="text-[10px] text-green-600 font-bold uppercase tracking-widest">{{ __('Community Impact') }}</div>
            </div>

            <!-- Rating -->
            <div class="bg-white dark:bg-gray-800 border-r-4 border-blue-600 shadow-sm p-6 group hover:translate-y-[-2px] transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">{{ __('Expert Rating') }}</span>
                    <i class="bi bi-star-fill text-blue-600 text-xl"></i>
                </div>
                <h3 class="font-black text-4xl mb-1 text-gray-900 dark:text-white">4.9</h3>
                <div class="text-[10px] text-blue-600 font-bold uppercase tracking-widest">{{ __('By 85+ Farmers') }}</div>
            </div>

            <!-- Beneficiaries -->
            <div class="bg-white dark:bg-gray-800 border-r-4 border-gray-400 shadow-sm p-6 group hover:translate-y-[-2px] transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">{{ __('Active Beneficiaries') }}</span>
                    <i class="bi bi-people-fill text-gray-400 text-xl"></i>
                </div>
                <h3 class="font-black text-4xl mb-1 text-gray-900 dark:text-white">124</h3>
                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ __('This Month') }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Consultations List -->
            <div class="space-y-6">
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-4">
                    <div>
                        <h5 class="font-black text-xs uppercase tracking-[0.3em] text-gray-900 dark:text-white">{{ __('Urgent Inquiries') }}</h5>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">{{ __('Latest Farmer requests awaiting your specialty') }}</p>
                    </div>
                    <a href="{{ route('expert.consultations.index') }}" class="text-[10px] font-black uppercase tracking-widest text-green-600 hover:text-green-700 transition">
                        {{ __('View All Records') }} <i class="bi bi-arrow-left"></i>
                    </a>
                </div>

                <div class="space-y-4">
    @forelse($recentConsultations as $consultation)
        @if($consultation->expert_id === auth()->id()) {{-- فقط الاستشارات الخاصة بالخبير الحالي --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm p-6 relative group overflow-hidden">
                <div class="absolute top-0 right-0 w-1 h-full bg-yellow-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                <div class="flex justify-between items-start mb-4">
                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-widest bg-gray-50 dark:bg-gray-700 text-gray-400 border border-gray-100 dark:border-gray-600">{{ $consultation->category }}</span>
                    <span class="text-[10px] text-gray-400 font-bold">{{ $consultation->created_at->diffForHumans() }}</span>
                </div>

                <h4 class="text-md font-bold text-gray-900 dark:text-white mb-2 leading-tight">{{ $consultation->subject }}</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 italic mb-6">"{{ Str::limit($consultation->question, 120) }}"</p>

                <div class="flex justify-between items-center pt-4 border-t border-gray-50 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-green-50 dark:bg-green-900/20 text-green-600 flex items-center justify-center text-[10px]">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ __('Farmer') }}</span>
                    </div>
                    <a href="{{ route('consultations.show', $consultation) }}" class="flex items-center gap-2 px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-[10px] font-black uppercase tracking-widest hover:bg-green-600 dark:hover:bg-green-600 dark:hover:text-white transition group/btn">
                        {{ __('Provide Advice') }}
                        <i class="bi bi-send-fill transition-transform group-hover/btn:-translate-x-1"></i>
                    </a>
                </div>
            </div>
        @endif
    @empty
        <div class="bg-gray-50 dark:bg-gray-800/50 border-2 border-dashed border-gray-200 dark:border-gray-700 p-12 text-center">
            <div class="w-16 h-16 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 flex items-center justify-center mx-auto mb-6">
                <i class="bi bi-check2-all text-green-600 text-3xl"></i>
            </div>
            <h6 class="font-black text-xs uppercase tracking-widest text-gray-900 dark:text-white">{{ __('Clear Horizon') }}</h6>
            <p class="text-xs text-gray-400 mt-2">{{ __('All pending consultations have been addressed.') }}</p>
        </div>
    @endforelse
</div>
            <!-- Expert Tips Management -->
            <div class="space-y-6">
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-4">
                    <div>
                        <h5 class="font-black text-xs uppercase tracking-[0.3em] text-gray-900 dark:text-white">{{ __('Global Insights') }}</h5>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold">{{ __('Manage advice broadcasted to all users') }}</p>
                    </div>
                    <a href="#addTipModal" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-[10px] font-black uppercase tracking-widest shadow-lg transition inline-block">
                        <i class="bi bi-plus-lg"></i> {{ __('Post New Tip') }}
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($myTips as $tip)
                        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition relative group">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-8 h-8 bg-green-50 dark:bg-green-900/20 text-green-600 flex items-center justify-center text-sm">
                                    <i class="bi bi-lightbulb-fill"></i>
                                </div>

                                <x-dropdown align="right" width="40">
                                    <x-slot name="trigger">
                                        <button class="text-gray-400 hover:text-gray-900 dark:hover:text-white p-1">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <a href="#editTipModal_{{ $tip->id }}" class="w-full text-start px-4 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2 text-gray-700 dark:text-gray-200">
                                            <i class="bi bi-pencil text-blue-500"></i> {{ __('Edit Insight') }}
                                        </a>
                                        <form action="{{ route('expert.tips.destroy', $tip) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-start px-4 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2 text-red-500">
                                                <i class="bi bi-trash"></i> {{ __('Remove') }}
                                            </button>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>

                            <h6 class="font-bold text-gray-900 dark:text-white mb-2 leading-tight uppercase tracking-tighter">{{ $tip->title }}</h6>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed line-clamp-3 mb-4 italic">"{{ $tip->content }}"</p>

                            <div class="text-[9px] font-black uppercase tracking-widest text-gray-400 border-t border-gray-50 dark:border-gray-700 pt-3 flex items-center justify-between">
                                <span>{{ __('Posted') }}</span>
                                <span>{{ $tip->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <!-- Edit Insight Modal -->
                        <div id="editTipModal_{{ $tip->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 hidden target:flex">
                            <div class="bg-white dark:bg-gray-800 w-full max-w-lg border border-gray-100 dark:border-gray-700 shadow-2xl relative">
                                <div class="absolute -top-1 left-0 right-0 h-1 bg-blue-600"></div>
                                <div class="p-8">
                                    <div class="flex justify-between items-center mb-8">
                                        <h5 class="font-black text-xs uppercase tracking-[0.3em] text-gray-900 dark:text-white">{{ __('Edit Professional Insight') }}</h5>
                                        <a href="#" class="text-gray-400 hover:text-red-500 transition"><i class="bi bi-x-lg"></i></a>
                                    </div>
                                    <form action="{{ route('expert.tips.update', $tip) }}" method="POST" class="space-y-6">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-2">
                                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">{{ __('Subject Title') }}</label>
                                            <input type="text" name="title" class="w-full bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-600 focus:border-blue-600 focus:ring-0 text-gray-900 dark:text-white font-bold p-4 text-sm" value="{{ $tip->title }}" required>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">{{ __('Insight Content') }}</label>
                                            <textarea name="content" rows="5" class="w-full bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-600 focus:border-blue-600 focus:ring-0 text-gray-900 dark:text-white p-4 text-sm leading-relaxed" required>{{ $tip->content }}</textarea>
                                        </div>
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 font-black text-[10px] uppercase tracking-[0.2em] transition shadow-xl">
                                            {{ __('Verify & Save Changes') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 bg-gray-50 dark:bg-gray-800/50 border-2 border-dashed border-gray-200 dark:border-gray-700 p-8 text-center">
                            <i class="bi bi-lightbulb text-gray-300 text-3xl mb-4 block"></i>
                            <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest">{{ __('No published insights yet.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Add Tip Modal -->
        <div id="addTipModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 hidden target:flex">
            <div class="bg-white dark:bg-gray-800 w-full max-w-lg border border-gray-100 dark:border-gray-700 shadow-2xl relative">
                <div class="absolute -top-1 left-0 right-0 h-1 bg-green-600"></div>
                <div class="p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h5 class="font-black text-xs uppercase tracking-[0.3em] text-gray-900 dark:text-white">{{ __('Post Scientific Insight') }}</h5>
                        <a href="#" class="text-gray-400 hover:text-red-500 transition"><i class="bi bi-x-lg"></i></a>
                    </div>
                    <form action="{{ route('expert.tips.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">{{ __('Broadcast Title') }}</label>
                            <input type="text" name="title" class="w-full bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-gray-900 dark:text-white font-bold p-4 text-sm placeholder-gray-300 dark:placeholder-gray-500" placeholder="{{ __('e.g., Optimizing irrigation intervals during summer') }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">{{ __('Broadcast Content') }}</label>
                            <textarea name="content" rows="6" class="w-full bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-gray-900 dark:text-white p-4 text-sm leading-relaxed placeholder-gray-300 dark:placeholder-gray-500" placeholder="{{ __('Provide factual, scientifically-backed agricultural advice...') }}" required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 font-black text-[10px] uppercase tracking-[0.2em] transition shadow-xl">
                            <i class="bi bi-megaphone-fill me-2"></i> {{ __('Authorize & Broadcast') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
