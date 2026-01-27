<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold mb-0 text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-flower1 text-green-600"></i> {{ __('Manage Crops') }}
            </h2>
            <a href="{{ route('crops.create') }}" class="inline-flex items-center px-4 py-2 border border-green-600 shadow-sm text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                <i class="bi bi-plus-lg ml-1"></i> {{ __('Add Crop') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($crops as $crop)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200 dark:border-gray-700 flex flex-col h-full" x-data="{ currentImg: 0, totalImgs: {{ $crop->images->count() }} }">
                    <!-- Gallery/Image Carousel -->
                    <div class="relative h-48 bg-gray-100 dark:bg-gray-700 overflow-hidden group">
                        @if($crop->images->count() > 0)
                            @foreach($crop->images as $index => $image)
                                <div x-show="currentImg === {{ $index }}" class="absolute inset-0 transition-opacity duration-300">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover" alt="{{ $crop->name }}">
                                    <!-- Delete Image Button -->
                                    <button @click.prevent="if(confirm('{{ __('Are you sure you want to delete this image?') }}')) deleteImage({{ $image->id }})" 
                                            class="absolute top-2 right-2 p-2 bg-red-600/90 text-white rounded shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:bg-red-700 z-30 flex items-center justify-center"
                                            title="{{ __('Delete Image') }}">
                                        <i class="bi bi-trash-fill text-sm"></i>
                                    </button>
                                </div>
                            @endforeach

                            <!-- Navigation Arrows (Clearer & More Prominent) -->
                            <div class="absolute inset-0 flex items-center justify-between p-2 z-20 pointer-events-none" x-show="totalImgs > 1">
                                <button @click="currentImg = (currentImg === 0) ? totalImgs - 1 : currentImg - 1" 
                                        class="p-1 px-1.5 bg-black/40 text-white rounded-full hover:bg-black/70 pointer-events-auto transition opacity-40 group-hover:opacity-100 flex items-center justify-center border border-white/10 shadow-lg"
                                        title="{{ __('Previous') }}">
                                    <i class="bi bi-arrow-right-circle-fill text-2xl"></i>
                                </button>
                                <button @click="currentImg = (currentImg === totalImgs - 1) ? 0 : currentImg + 1" 
                                        class="p-1 px-1.5 bg-black/40 text-white rounded-full hover:bg-black/70 pointer-events-auto transition opacity-40 group-hover:opacity-100 flex items-center justify-center border border-white/10 shadow-lg"
                                        title="{{ __('Next') }}">
                                    <i class="bi bi-arrow-left-circle-fill text-2xl"></i>
                                </button>
                            </div>

                            <!-- Image Counter (Bottom Right Corner - Highly Visible) -->
                            <div class="absolute bottom-3 right-3 px-2 py-1 bg-black/70 backdrop-blur-md text-white text-[11px] font-black rounded border border-white/20 z-20 flex items-center gap-1 shadow-sm" x-show="totalImgs > 1" dir="ltr">
                                <i class="bi bi-camera-fill text-[10px]"></i>
                                <span><span x-text="currentImg + 1"></span> / <span x-text="totalImgs"></span></span>
                            </div>
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                <i class="bi bi-image-fill text-4xl opacity-30"></i>
                            </div>
                        @endif

                        <!-- Status Badge (Moved to maintain space) -->
                        <div class="absolute top-3 left-3 z-10">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded shadow-sm text-[11px] font-bold bg-white/95 dark:bg-gray-800/95 backdrop-blur text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600">
                                <span class="w-2 h-2 rounded-full {{ $crop->status_color == 'success' ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]' : ($crop->status_color == 'warning' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                                {{ __($crop->status_label) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4 flex-1 flex flex-col">
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-3">
                            <h5 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-1">{{ $crop->name }}</h5>
                            <span class="px-2 py-1 text-[10px] font-bold text-green-700 bg-green-50 border border-green-200 rounded dark:bg-green-900/30 dark:text-green-400 dark:border-green-800 shrink-0">
                                {{ __($crop->type) }}
                            </span>
                        </div>

                        <!-- Specs -->
                        <div class="space-y-2 mb-4 text-xs text-gray-600 dark:text-gray-400">
                            <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-1">
                                <span>{{ __('Planting Date') }}:</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $crop->planting_date ? $crop->planting_date->format('Y-m-d') : '---' }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-1">
                                <span>{{ __('Crop Status') }}:</span>
                                <span class="font-bold text-green-600 dark:text-green-400">
                                    @if($crop->status == 'harvested')
                                        {{ __('Harvested') }} <i class="bi bi-check-circle-fill ms-1"></i>
                                    @else
                                        {{ __($crop->growth_stage_label) }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-1">
                                <span>{{ __('Area') }}:</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $crop->area }} {{ __('Acres') }}</span>
                            </div>
                        </div>

                        <!-- Growth Bar -->
                        <div class="mb-4">
                            @php
                                $p = $crop->growth_percentage;
                                $barClass = 'bg-red-500'; 
                                if ($p == 100) $barClass = 'bg-yellow-500'; 
                                elseif ($p <= 20) $barClass = 'bg-lime-400';
                                elseif ($p <= 50) $barClass = 'bg-green-500'; 
                                elseif ($p <= 80) $barClass = 'bg-yellow-500';
                                else $barClass = 'bg-green-700';
                            @endphp
                            <div class="flex justify-between items-center mb-1 text-xs">
                                <span class="font-bold text-gray-500 dark:text-gray-400">{{ __('Growth Progress') }}:</span>
                                <span class="font-bold text-gray-700 dark:text-gray-300">{{ $p }}% ({{ __($crop->growth_stage_label) }})</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                <div class="h-2 rounded-full {{ $barClass }}" style="width: {{ $p }}%"></div>
                            </div>
                        </div>

                        <!-- Tasks Section -->
                        <div class="mb-4" x-data="{ showAllTasks: false }">
                            <h6 class="text-[10px] font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">{{ __('Upcoming Tasks') }}</h6>
                            <div class="space-y-1">
                                @forelse($crop->tasks()->where('status', 'pending')->orderBy('due_date')->get() as $index => $task)
                                    <div class="flex items-start gap-2 text-xs" x-show="showAllTasks || {{ $index }} < 3">
                                        <form action="{{ route('tasks.complete', $task) }}" method="POST" class="mt-0.5">
                                            @csrf @method('PUT')
                                            <input type="checkbox" onchange="this.form.submit()" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 w-3 h-3 cursor-pointer">
                                        </form>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800 dark:text-gray-200 truncate">{{ $task->title }}</p>
                                            <p class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, H:i') }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[10px] text-gray-400 italic">{{ __('No upcoming tasks') }}</p>
                                @endforelse
                            </div>
                            @if($crop->tasks()->where('status', 'pending')->count() > 3)
                                <button @click="showAllTasks = !showAllTasks" class="text-[10px] text-green-600 hover:text-green-700 font-medium mt-1 flex items-center gap-1">
                                    <span x-text="showAllTasks ? '{{ __('Show Less') }}' : '{{ __('Show More') }}'"></span>
                                    <i class="bi" :class="showAllTasks ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                </button>
                            @endif
                        </div>

                        <!-- Control Actions (Compact) -->
                        <div class="mt-auto grid grid-cols-4 gap-2 mb-2" x-data="{ showModal: null }">
                            <button @click="$dispatch('open-modal', 'irrigationModal{{ $crop->id }}')" class="flex flex-col items-center justify-center p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-none border border-blue-100 dark:bg-slate-900 dark:text-blue-400 dark:border-blue-900/50 transition">
                                <i class="bi bi-water text-lg"></i>
                                <span class="text-[9px] font-bold mt-1">{{ __('Irrigation') }}</span>
                            </button>
                            <button @click="$dispatch('open-modal', 'treatmentModal{{ $crop->id }}')" class="flex flex-col items-center justify-center p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-none border border-red-100 dark:bg-slate-900 dark:text-red-400 dark:border-red-900/50 transition">
                                <i class="bi bi-shield-plus text-lg"></i>
                                <span class="text-[9px] font-bold mt-1">{{ __('Treatment') }}</span>
                            </button>
                            <button @click="$dispatch('open-modal', 'harvestModal{{ $crop->id }}')" class="flex flex-col items-center justify-center p-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-none border border-green-100 dark:bg-slate-900 dark:text-green-400 dark:border-green-900/50 transition">
                                <i class="bi bi-archive text-lg"></i>
                                <span class="text-[9px] font-bold mt-1">{{ __('Harvest') }}</span>
                            </button>
                            <button @click="$dispatch('open-modal', 'growthModal{{ $crop->id }}')" class="flex flex-col items-center justify-center p-2 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-none border border-yellow-100 dark:bg-slate-900 dark:text-yellow-400 dark:border-yellow-900/50 transition">
                                <i class="bi bi-graph-up-arrow text-lg"></i>
                                <span class="text-[9px] font-bold mt-1">{{ __('Growth') }}</span>
                            </button>
                        </div>
                        
                        <!-- Add Task Button -->
                        <button @click="$dispatch('open-modal', 'addTaskModal{{ $crop->id }}')" class="w-full py-1.5 mb-3 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 rounded border border-gray-200 dark:border-gray-600 transition flex items-center justify-center gap-2 text-xs font-bold">
                            <i class="bi bi-plus-circle"></i> {{ __('Add Task') }}
                        </button>

                        <!-- Edit Controls -->
                        <div class="flex gap-2 text-sm pt-2 border-t border-gray-100 dark:border-gray-700">
                             <a href="{{ route('crops.edit', $crop) }}" class="flex-1 py-1.5 text-center bg-gray-50 hover:bg-gray-100 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 rounded border border-gray-200 dark:border-gray-600 transition text-xs font-bold">
                                {{ __('Edit') }}
                            </a>
                            <form action="{{ route('crops.destroy', $crop) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this crop?') }}');" class="flex-none">
                                @csrf @method('DELETE')
                                <button type="submit" class="h-full px-4 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-700/20 dark:hover:bg-red-700/40 dark:text-red-400 rounded border border-red-100 dark:border-red-800 transition flex items-center justify-center" title="{{ __('Delete') }}">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                <!-- Modals (Using x-modal or standard Blade components, but we'll adapt Breeze defaults or inline Alpine) -->
                <!-- We will use standard Alpine x-data/x-show for modals to be consistent -->
                
                <!-- Irrigation Modal -->
                <x-modal name="irrigationModal{{ $crop->id }}" :show="false" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                             <i class="bi bi-water text-blue-500"></i> {{ __('Register Irrigation Operation:') }} {{ $crop->name }}
                        </h2>
                        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="water">
                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="title" value="{{ __('Irrigation execution') }}">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-input-label for="water_amount_{{ $crop->id }}" value="{{ __('Water Amount (L)') }}" />
                                    <x-text-input id="water_amount_{{ $crop->id }}" name="water_amount" type="number" class="mt-1 block w-full" placeholder="{{ __('e.g., 50') }}" required />
                                </div>
                                <div>
                                    <x-input-label for="duration_{{ $crop->id }}" value="{{ __('Duration (min)') }}" />
                                    <x-text-input id="duration_{{ $crop->id }}" name="duration_minutes" type="number" class="mt-1 block w-full" placeholder="{{ __('e.g., 30') }}" required />
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
                                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                                <x-primary-button class="bg-blue-600 hover:bg-blue-700">{{ __('Save Irrigation') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </x-modal>

                <!-- Treatment Modal -->
                <x-modal name="treatmentModal{{ $crop->id }}" :show="false" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                             <i class="bi bi-shield-plus text-red-500"></i> {{ __('Register Treatment:') }} {{ $crop->name }}
                        </h2>
                        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="fertilizer">
                            <input type="hidden" name="status" value="completed">
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
                                        <option value="لتر/فدان">{{ __('L/Acre') }}</option>
                                        <option value="كجم/فدان">{{ __('kg/Acre') }}</option>
                                        <option value="مل/لتر">{{ __('ml/L') }}</option>
                                    </select>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <x-input-label value="{{ __('Date & Time') }}" />
                                    <x-text-input name="due_date" type="datetime-local" class="mt-1 block w-full" value="{{ now()->format('Y-m-d\TH:i') }}" required />
                                </div>
                            </div>
                            
                            <div class="flex justify-end gap-2">
                                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                                <x-primary-button class="bg-red-600 hover:bg-red-700">{{ __('Save Treatment') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </x-modal>

                <!-- Harvest Modal -->
                 <x-modal name="harvestModal{{ $crop->id }}" :show="false" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                             <i class="bi bi-archive text-green-600"></i> {{ __('Register Harvest:') }} {{ $crop->name }}
                        </h2>
                        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="harvest">
                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="title" value="{{ __('Harvest execution') }}">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <x-input-label value="{{ __('Quantity') }}" />
                                    <x-text-input name="harvest_quantity" type="number" step="0.1" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label value="{{ __('Unit') }}" />
                                    <select name="harvest_unit" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-none shadow-sm">
                                        <option value="كجم">{{ __('kg') }}</option>
                                        <option value="طن">{{ __('ton') }}</option>
                                        <option value="صندوق">{{ __('box') }}</option>
                                    </select>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <x-input-label value="{{ __('Date & Time') }}" />
                                    <x-text-input name="due_date" type="datetime-local" class="mt-1 block w-full" value="{{ now()->format('Y-m-d\TH:i') }}" required />
                                </div>
                            </div>
                            
                            <div class="flex justify-end gap-2">
                                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                                <x-primary-button class="bg-green-600 hover:bg-green-700">{{ __('Save Harvest') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </x-modal>

                <!-- Growth Modal -->
                <x-modal name="growthModal{{ $crop->id }}" :show="false" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                             <i class="bi bi-graph-up-arrow text-yellow-500"></i> {{ __('Update Growth:') }} {{ $crop->name }}
                        </h2>
                        <form action="{{ route('crops.updateGrowth', $crop) }}" method="POST">
                            @csrf
                            <p class="text-sm text-gray-500 mb-4">{{ __('Select the current stage of the crop:') }}</p>
                            
                            <div class="space-y-2 mb-6">
                                <label class="flex items-center gap-3 p-3 border rounded-none cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ $crop->growth_percentage <= 10 ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' : '' }}">
                                    <input type="radio" name="growth_percentage" value="10" {{ $crop->growth_percentage <= 10 ? 'checked' : '' }} class="text-green-600 focus:ring-green-500">
                                    <div>
                                        <div class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('Seedling appearance') }}</div>
                                        <div class="text-xs text-gray-500">{{ __('Start of the plant emerging from the ground') }}</div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 border rounded-none cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ $crop->growth_percentage > 10 && $crop->growth_percentage <= 30 ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' : '' }}">
                                    <input type="radio" name="growth_percentage" value="30" {{ $crop->growth_percentage > 10 && $crop->growth_percentage <= 30 ? 'checked' : '' }} class="text-green-600 focus:ring-green-500">
                                    <div>
                                        <div class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('Leaf growth') }}</div>
                                        <div class="text-xs text-gray-500">{{ __('Significant increase in size') }}</div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 border rounded-none cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ $crop->growth_percentage > 30 && $crop->growth_percentage <= 50 ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' : '' }}">
                                    <input type="radio" name="growth_percentage" value="50" {{ $crop->growth_percentage > 30 && $crop->growth_percentage <= 50 ? 'checked' : '' }} class="text-green-600 focus:ring-green-500">
                                    <div>
                                        <div class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('Full vegetative growth') }}</div>
                                        <div class="text-xs text-gray-500">{{ __('Tall plant and strong stem') }}</div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 border rounded-none cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ $crop->growth_percentage > 50 && $crop->growth_percentage <= 75 ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' : '' }}">
                                    <input type="radio" name="growth_percentage" value="75" {{ $crop->growth_percentage > 50 && $crop->growth_percentage <= 75 ? 'checked' : '' }} class="text-green-600 focus:ring-green-500">
                                    <div>
                                        <div class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('Flowering') }}</div>
                                        <div class="text-xs text-gray-500">{{ __('Start of fruit appearance') }}</div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 border rounded-none cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ $crop->growth_percentage > 75 ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' : '' }}">
                                    <input type="radio" name="growth_percentage" value="100" {{ $crop->growth_percentage == 100 ? 'checked' : '' }} class="text-green-600 focus:ring-green-500">
                                    <div>
                                        <div class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('Maturity & Harvest') }}</div>
                                        <div class="text-xs text-gray-500">{{ __('Ready for final harvest') }}</div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="flex justify-end gap-2">
                                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                                <x-primary-button class="bg-yellow-600 hover:bg-yellow-700">{{ __('Update Status') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </x-modal>

                <!-- Add Task Modal -->
                <x-modal name="addTaskModal{{ $crop->id }}" :show="false" focusable>
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                             <i class="bi bi-plus-circle text-gray-600"></i> {{ __('Add New Task:') }} {{ $crop->name }}
                        </h2>
                        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="other">
                            <input type="hidden" name="status" value="pending">
                            
                            <div class="space-y-4 mb-4">
                                <div>
                                    <x-input-label for="title_{{ $crop->id }}" value="{{ __('Task Title') }}" />
                                    <x-text-input id="title_{{ $crop->id }}" name="title" type="text" class="mt-1 block w-full" placeholder="{{ __('e.g., Soil testing, pruning...') }}" required />
                                </div>
                                
                                <div>
                                    <x-input-label value="{{ __('Date & Time') }}" />
                                    <x-text-input name="due_date" type="datetime-local" class="mt-1 block w-full" value="{{ now()->format('Y-m-d\TH:i') }}" required />
                                </div>
                                
                                <div>
                                    <x-input-label value="{{ __('Notes') }}" />
                                    <textarea name="notes" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-none shadow-sm" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="flex justify-end gap-2">
                                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                                <x-primary-button class="bg-gray-800 hover:bg-gray-900">{{ __('Save Task') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </x-modal>
            </div>
            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center py-12">
                    <div class="inline-flex p-4 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <i class="bi bi-sprout text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('No crops currently') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('Start by adding the first crop to your farm and track its growth.') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('crops.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="bi bi-plus-lg ml-2"></i> {{ __('Add Crop') }}
                        </a>
                    </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $crops->links() }}
        </div>
    </div>
    <script>
        function deleteImage(imageId) {
            fetch(`/api/crops/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('{{ __('Error deleting image') }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __('An error occurred while connecting to the server') }}');
            });
        }
    </script>
</x-app-layout>
