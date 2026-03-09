<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('consultations.index') }}" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-gray-800 text-gray-500 hover:text-green-600 border border-gray-100 dark:border-gray-700 shadow-sm transition">
                <i class="bi bi-arrow-right fs-5"></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">
                    {{ __('Request New Consultation') }}
                </h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ __('Speak with our certified agricultural experts') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 px-4 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-2xl overflow-hidden">
            <!-- Form Header Decoration -->
            <div class="h-2 bg-gradient-to-l from-green-600 to-green-400"></div>

            <div class="p-8 sm:p-12">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/20 text-green-600 flex items-center justify-center border border-green-100 dark:border-green-800">
                        <i class="bi bi-pencil-square text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('Inquiry Details') }}</h3>
                        <p class="text-xs text-gray-400 font-medium">{{ __('Please provide as much detail as possible for a better diagnosis.') }}</p>
                    </div>
                </div>

                <form action="{{ route('consultations.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Subject Field -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 block">{{ __('Consultation Subject') }}</label>
                        <input type="text" name="subject"
                            class="w-full bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-gray-900 dark:text-white font-bold p-4 text-sm transition-colors placeholder-gray-300 dark:placeholder-gray-500 italic"
                            placeholder="{{ __('e.g., Wilting observed in tomato leaves since 3 days...') }}"
                            required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Category Field -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 block">{{ __('Classification') }}</label>
                            <div class="relative">
                                <select name="category"
                                    class="w-full bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-gray-900 dark:text-white font-bold p-4 text-sm appearance-none cursor-pointer"
                                    required>
                                    <option value="" disabled selected>{{ __('Select a category...') }}</option>
                                    <option value="Irrigation">💧 {{ __('Irrigation') }}</option>
                                    <option value="Pests & Diseases">🐛 {{ __('Pests & Diseases') }}</option>
                                    <option value="Fertilization">🧪 {{ __('Fertilization') }}</option>
                                    <option value="Soil">🌱 {{ __('Soil') }}</option>
                                    <option value="Other">📄 {{ __('Other') }}</option>
                                </select>
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Related Crop Field -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 block">{{ __('Affected Crop (Optional)') }}</label>
                            <div class="relative">
                                <select name="crop_id"
                                    class="w-full bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-gray-900 dark:text-white font-bold p-4 text-sm appearance-none cursor-pointer">
                                    <option value="">🚫 {{ __('No specific crop') }}</option>
                                    @foreach($crops as $crop)
                                        <option value="{{ $crop->id }}">💠 {{ $crop->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                                      <!-- Related Crop Field -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 block">{{ __('Choose the expert (Optional)') }}</label>
                            <div class="relative">
                                <select name="expert_id"
                                    class="w-full bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-gray-900 dark:text-white font-bold p-4 text-sm appearance-none cursor-pointer">
                                    <option value="">🚫 {{ __('No specific expert') }}</option>
                                    @foreach($experts as $expert)
                                        <option value="{{ $expert->id }}">💠 {{ $expert->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Question Detail Field -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 block">{{ __('Your detailed question') }}</label>
                        <textarea name="question"
                            class="w-full bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-600 focus:border-green-600 focus:ring-0 text-gray-900 dark:text-white p-5 text-sm leading-relaxed placeholder-gray-300 dark:placeholder-gray-500 italic min-h-[200px]"
                            placeholder="{{ __('Please describe the symptoms, recent environmental changes, pesticides used, or any other relevant information...') }}"
                            required></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button type="submit" class="flex-1 py-4 bg-green-600 hover:bg-green-700 text-white text-xs font-black uppercase tracking-[0.2em] shadow-xl transition active:scale-[0.98] flex items-center justify-center gap-3">
                            <i class="bi bi-send-fill text-xs"></i>
                            {{ __('Transmit Request to Expert') }}
                        </button>
                        <a href="{{ route('consultations.index') }}" class="sm:px-10 py-4 border border-gray-100 dark:border-gray-700 text-gray-400 hover:text-red-500 hover:border-red-500/30 text-xs font-black uppercase tracking-[0.2em] text-center transition">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Professional Tip Bar -->
        <div class="mt-8 p-6 bg-gray-900 dark:border dark:border-gray-800 text-white flex items-center gap-4 transition-transform hover:scale-[1.01]">
            <div class="w-10 h-10 bg-green-600 flex items-center justify-center shrink-0">
                <i class="bi bi-lightbulb-fill text-xl"></i>
            </div>
            <div class="text-xs">
                <span class="font-black text-green-500 uppercase tracking-widest block mb-1">{{ __('Expert Tip') }}</span>
                <span class="opacity-70">{{ __('Including photos of the affected leaves or stems allows experts to provide a 40% more accurate diagnosis.') }}</span>
            </div>
        </div>
    </div>
</x-app-layout>
