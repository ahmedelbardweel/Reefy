<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold mb-0 text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-flower1 text-green-600"></i> {{ __('Manage Crops') }}
            </h2>
            <a href="{{ route('crops.create') }}" class="inline-flex items-center px-4 py-2 border border-green-600 shadow-sm text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700 focus:outline-none transition">
                <i class="bi bi-plus-lg ml-1"></i> {{ __('Add Crop') }}
            </a>
        </div>
    </x-slot>

    {{-- CSS-only modal styles --}}
    <style>
        .css-modal { display: none; position: fixed; inset: 0; z-index: 50; overflow-y: auto; padding: 1rem; }
        .css-modal:target { display: flex; align-items: center; justify-content: center; }
        .css-modal__overlay { position: fixed; inset: 0; background-color: rgba(100,116,139,0.75); }
        .css-modal__dialog { position: relative; z-index: 10; background: white; width: 100%; max-width: 42rem; margin: auto; }
        .dark .css-modal__dialog { background: #1f2937; }

        /* Hide crop images beyond the first without JS */
        .crop-img-wrap > *:not(:first-child) { display: none; }
    </style>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-9xl mx-auto">
        <div class="flex flex-wrap justify-start gap-6">
            @forelse($crops as $crop)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200 dark:border-gray-700 flex flex-col shrink-0" style="width: 320px; max-width: 320px;">

             {{-- Image Section --}}
            <div class="relative h-40 bg-gray-100 dark:bg-gray-700 overflow-hidden">

             @if($crop->images->count() > 0)

    <div class="flex overflow-x-auto snap-x snap-mandatory h-full scroll-smooth">
         @foreach($crop->images as $index => $image)
            <div id="crop{{$crop->id}}img{{$index}}"  class="min-w-full h-full snap-center relative">
                 <img src="{{ $image->image_url }}" class="w-full h-full object-cover" alt="{{ $crop->name }}">
            </div>
        @endforeach
     </div>
@else
<div class="absolute inset-0 flex items-center justify-center text-gray-400">
<i class="bi bi-image-fill text-3xl opacity-30"></i>
</div>
@endif

                        {{-- Status Badge --}}
                        <div class="absolute top-2 left-2 z-10">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded shadow-sm text-[9px] font-bold bg-white/95 dark:bg-gray-800/95 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600 uppercase">
                                <span class="w-1.5 h-1.5 rounded-full {{ $crop->status_color == 'success' ? 'bg-green-500' : ($crop->status_color == 'warning' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                {{ __($crop->status_label) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-3 flex-1 flex flex-col">
                        {{-- Header --}}
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h5 class="text-base font-bold text-gray-900 dark:text-white line-clamp-1 leading-tight">{{ $crop->name }}</h5>
                                <div class="text-[10px] text-gray-500 font-medium">{{ __($crop->type) }}</div>
                            </div>
                            <span class="px-1.5 py-0.5 text-[9px] font-black uppercase text-green-700 bg-green-50 border border-green-100 rounded dark:bg-green-900/30 dark:text-green-400 dark:border-green-800 shrink-0">
                                {{ __($crop->growth_stage_label) }}
                            </span>
                        </div>

                        {{-- Technical Specs Grid (Compact) --}}
                        <div class="grid grid-cols-2 gap-x-3 gap-y-1.5 mb-3 bg-gray-50 dark:bg-gray-900/50 p-2 border border-gray-100 dark:border-gray-700/50">
                            <div class="flex flex-col">
                                <span class="text-[8px] text-gray-400 uppercase font-bold">{{ __('Area') }}</span>
                                <span class="text-[10px] font-bold text-gray-700 dark:text-gray-300">{{ $crop->area }} {{ __('Acres') }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[8px] text-gray-400 uppercase font-bold">{{ __('Soil Type') }}</span>
                                <span class="text-[10px] font-bold text-gray-700 dark:text-gray-300">{{ __($crop->soil_type ?? 'Not Set') }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[8px] text-gray-400 uppercase font-bold">{{ __('Irrigation') }}</span>
                                <span class="text-[10px] font-bold text-gray-700 dark:text-gray-300">{{ __($crop->irrigation_method ?? 'Not Set') }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[8px] text-gray-400 uppercase font-bold">{{ __('Seed Source') }}</span>
                                <span class="text-[10px] font-bold text-gray-700 dark:text-gray-300 truncate">{{ $crop->seed_source ?? '---' }}</span>
                            </div>
                            @if($crop->yield_estimate)
                            <div class="flex flex-col col-span-2 mt-1 pt-1 border-t border-gray-200/50 dark:border-gray-700/50">
                                <span class="text-[8px] text-gray-400 uppercase font-bold">{{ __('Expected Yield') }}</span>
                                <span class="text-[10px] font-bold text-green-600 dark:text-green-400">{{ $crop->yield_estimate }} {{ __('Tons') }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Timeline --}}
                        <div class="flex items-center gap-2 mb-3 px-1 text-[9px] font-medium text-gray-500">
                             <div class="flex items-center gap-1">
                                <i class="bi bi-calendar-event text-blue-500"></i>
                                <span class="font-bold text-gray-700 dark:text-gray-300">{{ $crop->planting_date ? $crop->planting_date->format('M d') : '---' }}</span>
                             </div>
                             <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                             <div class="flex items-center gap-1">
                                <span class="font-bold {{ \Carbon\Carbon::parse($crop->expected_harvest_date)->isPast() ? 'text-red-500' : 'text-orange-500' }}">
                                    {{ $crop->expected_harvest_date ? \Carbon\Carbon::parse($crop->expected_harvest_date)->format('M d') : '---' }}
                                </span>
                                <i class="bi bi-flag-fill {{ \Carbon\Carbon::parse($crop->expected_harvest_date)->isPast() ? 'text-red-500' : 'text-orange-500' }}"></i>
                             </div>
                        </div>

                        {{-- Growth Bar (Thinner) --}}
                        <div class="mb-3">
                            <div class="flex justify-between items-center mb-0.5 text-[9px]">
                                <span class="font-bold text-gray-400 uppercase">{{ __('Growth') }}</span>
                                <span class="font-black text-gray-700 dark:text-gray-300">{{ $crop->growth_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1 dark:bg-gray-700">
                                <div class="h-1 rounded-full {{ $crop->growth_percentage >= 100 ? 'bg-yellow-500' : 'bg-green-500' }}" style="width: {{ $crop->growth_percentage }}%"></div>
                            </div>
                        </div>

                        {{-- Notes Snippet --}}
                        @if($crop->notes)
                        <div class="mb-3 p-2 bg-yellow-50/50 dark:bg-yellow-900/10 border-l-2 border-yellow-400">
                            <p class="text-[9px] text-gray-600 dark:text-gray-400 italic line-clamp-2 leading-tight">
                                "{{ $crop->notes }}"
                            </p>
                        </div>
                        @endif

                        {{-- Tasks Section (Compact) --}}
                        <div class="mb-3 space-y-1.5 pt-2 border-t border-gray-100 dark:border-gray-700">
                            @php $pendingTasks = $crop->tasks()->where('status', 'pending')->orderBy('due_date')->take(4)->get(); @endphp
                            @forelse($pendingTasks as $task)
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <form action="{{ route('tasks.complete', $task) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="w-3.5 h-3.5 border border-gray-300 rounded-none bg-white flex items-center justify-center hover:border-green-500 transition">
                                                <i class="bi bi-check text-[10px] text-white hover:text-green-500"></i>
                                            </button>
                                        </form>
                                        <p class="text-[10px] font-bold text-gray-700 dark:text-gray-200 truncate">{{ $task->title }}</p>
                                    </div>
                                    <span class="text-[8px] font-black text-gray-400 whitespace-nowrap">{{ \Carbon\Carbon::parse($task->due_date)->format('d/m') }}</span>
                                </div>
                            @empty
                                <div class="text-[9px] text-gray-400 italic flex items-center gap-1">
                                    <i class="bi bi-info-circle"></i> {{ __('No pending tasks') }}
                                </div>
                            @endforelse
                        </div>

                        {{-- Action Grid (Tight) --}}
                        <div class="mt-auto pt-2 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex flex-row gap-1.5 mb-2">
                                <a href="#irrigationModal{{ $crop->id }}" class="flex-1 flex flex-col items-center justify-center py-1.5 bg-blue-50/50 hover:bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 transition" title="{{ __('Irrigation') }}">
                                    <i class="bi bi-water text-sm"></i>
                                    <span class="text-[7px] font-black uppercase mt-0.5">{{ __('Irrigation') }}</span>
                                </a>
                                <a href="#treatmentModal{{ $crop->id }}" class="flex-1 flex flex-col items-center justify-center py-1.5 bg-red-50/50 hover:bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-400 transition" title="{{ __('Treatment') }}">
                                    <i class="bi bi-shield-plus text-sm"></i>
                                    <span class="text-[7px] font-black uppercase mt-0.5">{{ __('Treatment') }}</span>
                                </a>
                                <a href="#harvestModal{{ $crop->id }}" class="flex-1 flex flex-col items-center justify-center py-1.5 bg-green-50/50 hover:bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400 transition" title="{{ __('Harvest') }}">
                                    <i class="bi bi-archive text-sm"></i>
                                    <span class="text-[7px] font-black uppercase mt-0.5">{{ __('Harvest') }}</span>
                                </a>
                                <a href="#growthModal{{ $crop->id }}" class="flex-1 flex flex-col items-center justify-center py-1.5 bg-yellow-50/50 hover:bg-yellow-100 text-yellow-600 dark:bg-yellow-900/20 dark:text-yellow-400 transition" title="{{ __('Growth') }}">
                                    <i class="bi bi-graph-up-arrow text-sm"></i>
                                    <span class="text-[7px] font-black uppercase mt-0.5">{{ __('Growth') }}</span>
                                </a>
                            </div>

                            @if($errors->any() && old('crop_id') == $crop->id)
                                <div class="mb-2 p-2 bg-red-50 border border-red-200 text-red-600 text-[10px] rounded">
                                    {{ __('Please check the task details and try again.') }}
                                </div>
                            @endif

              {{-- Footer Actions --}}
<div class="flex items-center gap-2">

    {{-- Add Task Button (يأخذ المساحة) --}}
    <a href="#addTaskModal{{ $crop->id }}"
       class="flex-1 flex items-center justify-center gap-1 px-3 py-1.5
       bg-green-600 hover:bg-green-700 text-white
       text-xs font-semibold rounded transition">
        <i class="bi bi-plus-lg"></i>
        Add Task
    </a>

    {{-- Edit Button --}}
    <a href="{{ route('crops.edit', $crop) }}"
       class="flex items-center justify-center w-8 h-8
       bg-gray-100 hover:bg-gray-200
       text-gray-700 dark:bg-gray-700 dark:text-gray-200
       rounded transition">
        <i class="bi bi-pencil"></i>
    </a>

    {{-- Delete Button --}}
    <form action="{{ route('crops.destroy', $crop) }}" method="POST">
        @csrf
        @method('DELETE')

        <button type="submit"
            class="flex items-center justify-center w-8 h-8
            bg-red-50 text-red-600
            dark:bg-red-900/30 dark:text-red-400
            border border-red-100 dark:border-red-900/50
            hover:bg-red-100 rounded transition">
            <i class="bi bi-trash"></i>
        </button>
    </form>

</div>
                        </div>
                    </div>

                    {{-- ======== CSS-only Modals (:target based) ======== --}}

                    {{-- Irrigation Modal --}}
                    <div id="irrigationModal{{ $crop->id }}" class="css-modal @if($errors->any() && old('crop_id') == $crop->id && old('type') == 'water') !flex items-center justify-center @endif">
                        <div class="css-modal__overlay"></div>
                        <div class="css-modal__dialog p-6 rounded-none shadow-xl max-w-lg w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                    <i class="bi bi-water text-blue-500"></i> {{ __('Register Irrigation Operation:') }} {{ $crop->name }}
                                </h2>
                                <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</a>
                            </div>
                            <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="water">
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="crop_id" value="{{ $crop->id }}">
                                <input type="hidden" name="title" value="{{ __('Irrigation execution') }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-input-label value="{{ __('Water Amount (L)') }}" />
                                        <x-text-input name="water_amount" type="number" class="mt-1 block w-full" placeholder="{{ __('e.g., 50') }}" required />
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('Duration (min)') }}" />
                                        <x-text-input name="duration_minutes" type="number" class="mt-1 block w-full" placeholder="{{ __('e.g., 30') }}" required />
                                    </div>
                                    <div class="col-span-1 md:col-span-2">
                                        <x-input-label value="{{ __('Date & Time') }}" />
                                        <x-text-input name="due_date" type="datetime-local" class="mt-1 block w-full" value="{{ now()->format('Y-m-d\TH:i') }}" required />
                                    </div>
                                    <div class="col-span-1 md:col-span-2">
                                        <x-input-label value="{{ __('Notes') }}" />
                                        <textarea name="system_notes" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-none shadow-sm" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <a href="#" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-none font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">{{ __('Cancel') }}</a>
                                    <x-primary-button class="bg-blue-600 hover:bg-blue-700">{{ __('Save Irrigation') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Treatment Modal --}}
                    <div id="treatmentModal{{ $crop->id }}" class="css-modal @if($errors->any() && old('crop_id') == $crop->id && old('type') == 'fertilizer') !flex items-center justify-center @endif">
                        <div class="css-modal__overlay"></div>
                        <div class="css-modal__dialog p-6 rounded-none shadow-xl max-w-lg w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                    <i class="bi bi-shield-plus text-red-500"></i> {{ __('Register Treatment:') }} {{ $crop->name }}
                                </h2>
                                <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</a>
                            </div>
                            <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="fertilizer">
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="crop_id" value="{{ $crop->id }}">
                                <input type="hidden" name="title" value="{{ __('Treatment execution') }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="col-span-1 md:col-span-2">
                                        <x-input-label value="{{ __('Material Name') }}" />
                                        <x-text-input name="material_name" type="text" class="mt-1 block w-full" required />
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('Dosage') }}" />
                                        <x-text-input name="dosage" type="number" step="0.1" class="mt-1 block w-full" required />
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('Unit') }}" />
                                        <select name="dosage_unit" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-none shadow-sm">
                                            <option value="{{ __('L/Acre') }}">{{ __('L/Acre') }}</option>
                                            <option value="{{ __('kg/Acre') }}">{{ __('kg/Acre') }}</option>
                                            <option value="{{ __('ml/L') }}">{{ __('ml/L') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1 md:col-span-2">
                                        <x-input-label value="{{ __('Date & Time') }}" />
                                        <x-text-input name="due_date" type="datetime-local" class="mt-1 block w-full" value="{{ now()->format('Y-m-d\TH:i') }}" required />
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <a href="#" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-none font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">{{ __('Cancel') }}</a>
                                    <x-primary-button class="bg-red-600 hover:bg-red-700">{{ __('Save Treatment') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Harvest Modal --}}
                    <div id="harvestModal{{ $crop->id }}" class="css-modal @if($errors->any() && old('crop_id') == $crop->id && old('type') == 'harvest') !flex items-center justify-center @endif">
                        <div class="css-modal__overlay"></div>
                        <div class="css-modal__dialog p-6 rounded-none shadow-xl max-w-lg w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                    <i class="bi bi-archive text-green-600"></i> {{ __('Register Harvest:') }} {{ $crop->name }}
                                </h2>
                                <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</a>
                            </div>
                            <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="harvest">
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="crop_id" value="{{ $crop->id }}">
                                <input type="hidden" name="title" value="{{ __('Harvest execution') }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-input-label value="{{ __('Quantity') }}" />
                                        <x-text-input name="harvest_quantity" type="number" step="0.1" class="mt-1 block w-full" required />
                                    </div>
                                    <div>
                                        <x-input-label value="{{ __('Unit') }}" />
                                        <select name="harvest_unit" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-none shadow-sm">
                                            <option value="{{ __('kg') }}">{{ __('kg') }}</option>
                                            <option value="{{ __('ton') }}">{{ __('ton') }}</option>
                                            <option value="{{ __('box') }}">{{ __('box') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-span-1 md:col-span-2">
                                        <x-input-label value="{{ __('Date & Time') }}" />
                                        <x-text-input name="due_date" type="datetime-local" class="mt-1 block w-full" value="{{ now()->format('Y-m-d\TH:i') }}" required />
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <a href="#" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-none font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">{{ __('Cancel') }}</a>
                                    <x-primary-button class="bg-green-600 hover:bg-green-700">{{ __('Save Harvest') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Growth Modal --}}
                    <div id="growthModal{{ $crop->id }}" class="css-modal @if($errors->any() && old('crop_id') == $crop->id && !old('type')) !flex items-center justify-center @endif">
                        <div class="css-modal__overlay"></div>
                        <div class="css-modal__dialog p-6 rounded-none shadow-xl max-w-lg w-full">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                    <i class="bi bi-graph-up-arrow text-yellow-500"></i> {{ __('Update Growth:') }} {{ $crop->name }}
                                </h2>
                                <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</a>
                            </div>
                            <form action="{{ route('crops.updateGrowth', $crop) }}" method="POST">
                                @csrf
                                <p class="text-sm text-gray-500 mb-4">{{ __('Select the current stage of the crop:') }}</p>
                                <div class="space-y-2 mb-6">
                                    @foreach([
                                        ['val'=>10, 'title'=>'Seedling appearance', 'desc'=>'Start of the plant emerging from the ground'],
                                        ['val'=>30, 'title'=>'Leaf growth', 'desc'=>'Significant increase in size'],
                                        ['val'=>50, 'title'=>'Full vegetative growth', 'desc'=>'Tall plant and strong stem'],
                                        ['val'=>75, 'title'=>'Flowering', 'desc'=>'Start of fruit appearance'],
                                        ['val'=>100, 'title'=>'Maturity & Harvest', 'desc'=>'Ready for final harvest'],
                                    ] as $stage)
                                        @php
                                            $isChecked = $crop->growth_percentage == $stage['val']
                                                || ($stage['val'] == 10 && $crop->growth_percentage <= 10)
                                                || ($stage['val'] == 30 && $crop->growth_percentage > 10 && $crop->growth_percentage <= 30)
                                                || ($stage['val'] == 50 && $crop->growth_percentage > 30 && $crop->growth_percentage <= 50)
                                                || ($stage['val'] == 75 && $crop->growth_percentage > 50 && $crop->growth_percentage <= 75)
                                                || ($stage['val'] == 100 && $crop->growth_percentage > 75);
                                        @endphp
                                        <label class="flex items-center gap-3 p-3 border rounded-none cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ $isChecked ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' : 'border-gray-200 dark:border-gray-700' }}">
                                            <input type="radio" name="growth_percentage" value="{{ $stage['val'] }}" {{ $isChecked ? 'checked' : '' }} class="text-green-600 focus:ring-green-500">
                                            <div>
                                                <div class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __($stage['title']) }}</div>
                                                <div class="text-xs text-gray-500">{{ __($stage['desc']) }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="flex justify-end gap-2">
                                    <a href="#" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-none font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">{{ __('Cancel') }}</a>
                                    <x-primary-button class="bg-yellow-600 hover:bg-yellow-700">{{ __('Update Status') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>

{{-- Add Task Modal (Pure CSS Dynamic) --}}
<div id="addTaskModal{{ $crop->id }}" class="css-modal @if($errors->any() && old('crop_id') == $crop->id) !flex items-center justify-center @endif">
    <div class="css-modal__overlay"></div>
    <div class="css-modal__dialog p-6 rounded-none shadow-xl max-w-lg w-full">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <i class="bi bi-plus-circle text-green-600"></i> {{ __('Add Task') }}: {{ $crop->name }}
            </h2>
            <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</a>
        </div>

        @if($errors->any() && old('crop_id') == $crop->id)
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs text-right">
                <p class="font-bold">{{ __('Please fix the following errors:') }}</p>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="pending">
            <input type="hidden" name="crop_id" value="{{ $crop->id }}">

            {{-- Task Title --}}
            <div class="mb-5">
                <x-input-label value="{{ __('Task Title') }}" />
                <x-text-input name="title" type="text" class="mt-1 block w-full" placeholder="{{ __('e.g., Soil testing, pruning...') }}" value="{{ old('title') }}" required />
            </div>

            {{-- Dynamic Fields via Radio Toggles --}}
            <div class="task-type-system">
                <style>
                    /* Toggle Fields */
                    .dynamic-field-group { display: none; }
                    .type-radio-{{ $crop->id }}-water:checked ~ .fields-water-{{ $crop->id }} { display: block; }
                    .type-radio-{{ $crop->id }}-fertilizer:checked ~ .fields-treatment-{{ $crop->id }} { display: block; }
                    .type-radio-{{ $crop->id }}-pest:checked ~ .fields-treatment-{{ $crop->id }} { display: block; } /* Share treatment fields */
                    .type-radio-{{ $crop->id }}-harvest:checked ~ .fields-harvest-{{ $crop->id }} { display: block; }
                    
                    /* Active Link Styling */
                    .type-radio-{{ $crop->id }}-water:checked ~ .type-grid-{{ $crop->id }} .label-water { background: #2563eb; color: white; border-color: #2563eb; }
                    .type-radio-{{ $crop->id }}-fertilizer:checked ~ .type-grid-{{ $crop->id }} .label-fertilizer { background: #dc2626; color: white; border-color: #dc2626; }
                    .type-radio-{{ $crop->id }}-pest:checked ~ .type-grid-{{ $crop->id }} .label-pest { background: #9333ea; color: white; border-color: #9333ea; }
                    .type-radio-{{ $crop->id }}-harvest:checked ~ .type-grid-{{ $crop->id }} .label-harvest { background: #16a34a; color: white; border-color: #16a34a; }
                    .type-radio-{{ $crop->id }}-other:checked ~ .type-grid-{{ $crop->id }} .label-other { background: #4b5563; color: white; border-color: #4b5563; }
                </style>

                {{-- Hidden Radios acting as State --}}
                <input type="radio" name="type" value="water" id="radio_{{ $crop->id }}_water" class="hidden type-radio-{{ $crop->id }}-water" {{ old('type') == 'water' ? 'checked' : '' }}>
                <input type="radio" name="type" value="fertilizer" id="radio_{{ $crop->id }}_fertilizer" class="hidden type-radio-{{ $crop->id }}-fertilizer" {{ old('type') == 'fertilizer' ? 'checked' : '' }}>
                <input type="radio" name="type" value="pest" id="radio_{{ $crop->id }}_pest" class="hidden type-radio-{{ $crop->id }}-pest" {{ old('type') == 'pest' ? 'checked' : '' }}>
                <input type="radio" name="type" value="harvest" id="radio_{{ $crop->id }}_harvest" class="hidden type-radio-{{ $crop->id }}-harvest" {{ old('type') == 'harvest' ? 'checked' : '' }}>
                <input type="radio" name="type" value="other" id="radio_{{ $crop->id }}_other" class="hidden type-radio-{{ $crop->id }}-other" {{ old('type') == 'other' || !old('type') ? 'checked' : '' }}>

                {{-- Type Selection Grid --}}
                <div class="mb-6">
                    <x-input-label value="{{ __('Task Type') }}" class="mb-2" />
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2 type-grid-{{ $crop->id }}">
                        <label for="radio_{{ $crop->id }}_water" class="label-water cursor-pointer border py-2 px-1 text-center text-[9px] font-bold transition hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 flex flex-col items-center justify-center gap-1">
                            <i class="bi bi-water text-xs"></i> {{ __('Irrigation') }}
                        </label>
                        <label for="radio_{{ $crop->id }}_fertilizer" class="label-fertilizer cursor-pointer border py-2 px-1 text-center text-[9px] font-bold transition hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 flex flex-col items-center justify-center gap-1">
                            <i class="bi bi-shield-plus text-xs"></i> {{ __('Treatment') }}
                        </label>
                        <label for="radio_{{ $crop->id }}_pest" class="label-pest cursor-pointer border py-2 px-1 text-center text-[9px] font-bold transition hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 flex flex-col items-center justify-center gap-1">
                            <i class="bi bi-bug text-xs"></i> {{ __('Pest Control') }}
                        </label>
                        <label for="radio_{{ $crop->id }}_harvest" class="label-harvest cursor-pointer border py-2 px-1 text-center text-[9px] font-bold transition hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 flex flex-col items-center justify-center gap-1">
                            <i class="bi bi-archive text-xs"></i> {{ __('Harvest') }}
                        </label>
                        <label for="radio_{{ $crop->id }}_other" class="label-other cursor-pointer border py-2 px-1 text-center text-[9px] font-bold transition hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700 flex flex-col items-center justify-center gap-1">
                            <i class="bi bi-gear text-xs"></i> {{ __('Other') }}
                        </label>
                    </div>
                </div>

                {{-- Irrigation Fields --}}
                <div class="dynamic-field-group fields-water-{{ $crop->id }} border-l-4 border-blue-500 bg-blue-50/30 dark:bg-blue-900/10 p-4 mb-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="{{ __('Water Amount (L)') }}" />
                            <x-text-input name="water_amount" type="number" class="mt-1 block w-full" value="{{ old('water_amount') }}" />
                        </div>
                        <div>
                            <x-input-label value="{{ __('Duration (min)') }}" />
                            <x-text-input name="duration_minutes" type="number" class="mt-1 block w-full" value="{{ old('duration_minutes') }}" />
                        </div>
                    </div>
                </div>

                {{-- Treatment & Pest Fields (Shared) --}}
                <div class="dynamic-field-group fields-treatment-{{ $crop->id }} border-l-4 border-red-500 bg-red-50/30 dark:bg-red-900/10 p-4 mb-4">
                    <div class="mb-4">
                        <x-input-label value="{{ __('Material Name') }}" />
                        <x-text-input name="material_name" type="text" class="mt-1 block w-full" value="{{ old('material_name') }}" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="{{ __('Dosage') }}" />
                            <x-text-input name="dosage" type="number" step="0.1" class="mt-1 block w-full" value="{{ old('dosage') }}" />
                        </div>
                        <div>
                            <x-input-label value="{{ __('Unit') }}" />
                            <select name="dosage_unit" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-none shadow-sm h-[42px]">
                                <option value="L/Acre" {{ old('dosage_unit') == 'L/Acre' ? 'selected' : '' }}>{{ __('L/Acre') }}</option>
                                <option value="kg/Acre" {{ old('dosage_unit') == 'kg/Acre' ? 'selected' : '' }}>{{ __('kg/Acre') }}</option>
                                <option value="ml/L" {{ old('dosage_unit') == 'ml/L' ? 'selected' : '' }}>{{ __('ml/L') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Harvest Fields --}}
                <div class="dynamic-field-group fields-harvest-{{ $crop->id }} border-l-4 border-green-500 bg-green-50/30 dark:bg-green-900/10 p-4 mb-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="{{ __('Quantity') }}" />
                            <x-text-input name="harvest_quantity" type="number" step="0.1" class="mt-1 block w-full" value="{{ old('harvest_quantity') }}" />
                        </div>
                        <div>
                            <x-input-label value="{{ __('Unit') }}" />
                            <select name="harvest_unit" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-none shadow-sm h-[42px]">
                                <option value="kg" {{ old('harvest_unit') == 'kg' ? 'selected' : '' }}>{{ __('kg') }}</option>
                                <option value="ton" {{ old('harvest_unit') == 'ton' ? 'selected' : '' }}>{{ __('ton') }}</option>
                                <option value="box" {{ old('harvest_unit') == 'box' ? 'selected' : '' }}>{{ __('box') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- General Fields (Always visible) --}}
            <div class="space-y-4 mb-4 mt-6">
                <div>
                    <x-input-label value="{{ __('Date & Time') }}" />
                    <x-text-input name="due_date" type="datetime-local" class="mt-1 block w-full" value="{{ old('due_date') ?: now()->format('Y-m-d\TH:i') }}" required />
                </div>

                <div>
                    <x-input-label value="{{ __('Additional Notes') }}" />
                    <textarea name="notes" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-none shadow-sm" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-2 border-t pt-4">
                <a href="#" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-none font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">{{ __('Cancel') }}</a>
                <x-primary-button class="bg-green-600 hover:bg-green-700 text-white border-0">{{ __('Save Task') }}</x-primary-button>
            </div>
        </form>
    </div>
</div>

                </div>
            @empty
                <div class="w-full flex flex-col items-center justify-center text-center py-24">

    <div class="inline-flex p-4 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
        <i class="bi bi-sprout text-4xl text-gray-400"></i>
    </div>

    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
        {{ __('No crops currently') }}
    </h3>

    <p class="mt-1 text-sm text-gray-500">
        {{ __('Start by adding the first crop to your farm and track its growth.') }}
    </p>

    <div class="mt-6">
        <a href="{{ route('crops.create') }}"
           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
            <i class="bi bi-plus-lg ml-2"></i> {{ __('Add Crop') }}
        </a>
    </div>

</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $crops->links() }}
        </div>
    </div>
</x-app-layout>
