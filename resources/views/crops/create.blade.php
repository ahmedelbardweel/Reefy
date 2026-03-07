<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Crop') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-medium text-green-700 dark:text-green-400 flex items-center gap-2">
                            <i class="bi bi-seedling"></i> {{ __('Crop Details') }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Enter basic crop details to start smart tracking.') }}</p>
                    </div>

                    <form action="{{ route('crops.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Crop Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :placeholder="__('e.g., North Field Wheat')" />
                            </div>
                             <div>
                                <x-input-label for="type" :value="__('Plant Type')" />
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                                    <option value="" selected>{{ __('Select Type (Optional)...') }}</option>
                                    <option value="Wheat">{{ __('Wheat') }}</option>
                                    <option value="Corn">{{ __('Corn') }}</option>
                                    <option value="Rice">{{ __('Rice') }}</option>
                                    <option value="Tomato">{{ __('Tomato') }}</option>
                                    <option value="Potato">{{ __('Potato') }}</option>
                                    <option value="Cotton">{{ __('Cotton') }}</option>
                                    <option value="Other">{{ __('Other') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Image Upload (Simplified, no JS preview) -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                            <x-input-label :value="__('Crop Photos')" class="mb-2" />
                            <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-gray-600 dark:file:text-gray-200">
                            <p class="text-xs text-gray-500 mt-2">{{ __('Select multiple images. Supported formats: JPG, PNG.') }}</p>
                        </div>

                        <!-- Farm Details -->
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                            <h4 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Soil & Irrigation Details') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="area" :value="__('Area (Acres)')" />
                                    <x-text-input id="area" name="area" type="number" step="0.1" class="mt-1 block w-full" placeholder="2.5" />
                                </div>
                                <div>
                                    <x-input-label for="soil_type" :value="__('Soil Type')" />
                                    <select id="soil_type" name="soil_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                                        <option value="" selected>{{ __('Not Specified') }}</option>
                                        <option value="Clay">{{ __('Clay') }}</option>
                                        <option value="Sandy">{{ __('Sandy') }}</option>
                                        <option value="Loamy">{{ __('Loamy') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="irrigation_method" :value="__('Irrigation Method')" />
                                    <select id="irrigation_method" name="irrigation_method" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                                        <option value="" selected>{{ __('Not Specified') }}</option>
                                        <option value="Flood">{{ __('Flood') }}</option>
                                        <option value="Drip">{{ __('Drip') }}</option>
                                        <option value="Sprinkler">{{ __('Sprinkler') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Planting Details -->
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                            <h4 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Planting & Yield') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <x-input-label for="planting_date" :value="__('Planting Date')" />
                                    <x-text-input id="planting_date" name="planting_date" type="date" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="expected_harvest_date" :value="__('Expected Harvest Date')" />
                                    <x-text-input id="expected_harvest_date" name="expected_harvest_date" type="date" class="mt-1 block w-full text-green-700 dark:text-green-500 font-bold" />
                                </div>
                                <div>
                                    <x-input-label for="seed_source" :value="__('Seed Source')" />
                                    <x-text-input id="seed_source" name="seed_source" type="text" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="yield_estimate" :value="__('Expected Yield (Tons)')" />
                                    <x-text-input id="yield_estimate" name="yield_estimate" type="number" step="0.1" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                            <x-input-label for="notes" :value="__('Additional Notes')" />
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm"></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('crops.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">{{ __('Cancel') }}</a>
                            <x-primary-button class="bg-green-600 hover:bg-green-700">
                                {{ __('Save Crop') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
