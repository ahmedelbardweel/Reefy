<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Crop') }}: {{ $crop->name }}
            </h2>
            <a href="{{ route('crops.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400">
                <i class="bi bi-arrow-right ml-1"></i> {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Edit Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <form action="{{ route('crops.update', $crop) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <h3 class="text-base font-bold text-gray-700 dark:text-gray-300 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">{{ __('Basic Information') }}</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="name" :value="__('Crop Name')" />
                                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $crop->name) }}" required />
                                    </div>
                                    <div>
                                        <x-input-label for="type" :value="__('Type')" />
                                        <x-text-input id="type" name="type" type="text" class="mt-1 block w-full" value="{{ old('type', $crop->type) }}" required />
                                    </div>
                                    <div>
                                        <x-input-label for="area" :value="__('Area (Acres)')" />
                                        <x-text-input id="area" name="area" type="number" step="0.1" class="mt-1 block w-full" value="{{ old('area', $crop->area) }}" required />
                                    </div>
                                    <div>
                                        <x-input-label for="planting_date" :value="__('Planting Date')" />
                                        <x-text-input id="planting_date" name="planting_date" type="date" class="mt-1 block w-full" value="{{ $crop->planting_date ? $crop->planting_date->format('Y-m-d') : '' }}" required />
                                    </div>
                                    <div>
                                        <x-input-label for="expected_harvest_date" :value="__('Expected Harvest Date')" />
                                        <x-text-input id="expected_harvest_date" name="expected_harvest_date" type="date" class="mt-1 block w-full" value="{{ $crop->expected_harvest_date ? $crop->expected_harvest_date->format('Y-m-d') : '' }}" required />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                     <div>
                                        <x-input-label for="soil_type" :value="__('Soil Type')" />
                                        <x-text-input id="soil_type" name="soil_type" type="text" class="mt-1 block w-full" value="{{ old('soil_type', $crop->soil_type) }}" />
                                    </div>
                                    <div>
                                        <x-input-label for="irrigation_method" :value="__('Irrigation Method')" />
                                        <x-text-input id="irrigation_method" name="irrigation_method" type="text" class="mt-1 block w-full" value="{{ old('irrigation_method', $crop->irrigation_method) }}" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="notes" :value="__('Notes')" />
                                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">{{ old('notes', $crop->notes) }}</textarea>
                                </div>

                                <div>
                                    <x-input-label :value="__('Add New Photos')" />
                                    <input type="file" name="images[]" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                </div>

                                <div class="pt-4">
                                    <x-primary-button class="w-full justify-center bg-green-600 hover:bg-green-700">{{ __('Save Changes') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="space-y-6">
                    <!-- Growth Status -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 p-6 text-center">
                         <h3 class="text-sm font-bold text-gray-500 mb-4">{{ __('Manual Growth Update') }}</h3>
                         <div class="text-3xl font-bold text-green-600 mb-2">{{ $crop->growth_percentage }}%</div>
                         <p class="text-xs text-gray-400 mb-6">{{ __($crop->growth_stage_label) }}</p>
                         
                         <form action="{{ route('crops.updateGrowth', $crop) }}" method="POST">
                            @csrf
                            <input type="range" name="growth_percentage" min="0" max="100" value="{{ $crop->growth_percentage }}" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-600">
                             <div class="flex justify-between text-[10px] text-gray-400 mt-1 mb-4">
                                <span>0%</span>
                                <span>50%</span>
                                <span>100%</span>
                            </div>
                            <x-secondary-button type="submit" class="w-full justify-center">{{ __('Update Progress') }}</x-secondary-button>
                        </form>
                    </div>

                    <!-- Add Quick Task -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">{{ __('Add Quick Task') }}</h3>
                        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <x-text-input name="title" :placeholder="__('Task Title')" class="w-full text-sm" required />
                            </div>
                             <div>
                                <select name="type" class="w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm">
                                    <option value="water">{{ __('Irrigation') }}</option>
                                    <option value="fertilizer">{{ __('Fertilization') }}</option>
                                    <option value="pest">{{ __('Pest Control') }}</option>
                                    <option value="harvest">{{ __('Harvest') }}</option>
                                </select>
                            </div>
                             <div>
                                <x-text-input name="due_date" type="datetime-local" class="w-full text-sm" required />
                            </div>
                            <x-primary-button class="w-full justify-center bg-gray-800 hover:bg-gray-900">{{ __('Add') }}</x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
