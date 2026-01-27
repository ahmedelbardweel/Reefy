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
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :placeholder="__('e.g., North Field Wheat')" list="crop_name_suggestions" />
                                <datalist id="crop_name_suggestions">
                                    <!-- سيتم تعبئتها بواسطة JavaScript -->
                                </datalist>
                                <!-- Quick Select Chips for Name -->
                                <div class="mt-2">
                                    <p class="text-[10px] text-gray-500 mb-1">{{ __('Quick Select') }}:</p>
                                    <div id="name_chips" class="flex flex-wrap gap-2"></div>
                                </div>
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
                                <!-- Quick Select Chips for Type -->
                                <div class="mt-2">
                                    <p class="text-[10px] text-gray-500 mb-1">{{ __('Quick Select') }}:</p>
                                    <div id="type_chips" class="flex flex-wrap gap-2"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                            <x-input-label :value="__('Crop Photos')" class="mb-2" />
                            <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-gray-600 dark:file:text-gray-200" onchange="previewImages(this)">
                            <div id="imagePreview" class="flex flex-wrap gap-2 mt-4"></div>
                            <p class="text-xs text-gray-500 mt-2">{{ __('Select multiple images. Supported formats: JPG, PNG.') }}</p>
                        </div>
                        
                        <script>
                            function previewImages(input) {
                                const preview = document.getElementById('imagePreview');
                                preview.innerHTML = '';
                                if (input.files) {
                                    Array.from(input.files).forEach(file => {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const img = document.createElement('img');
                                            img.src = e.target.result;
                                            img.className = 'w-20 h-20 object-cover rounded-lg border border-gray-200';
                                            preview.appendChild(img);
                                        }
                                        reader.readAsDataURL(file);
                                    });
                                }
                            }
                        </script>

                        <!-- Farm Details -->
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                            <h4 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-4">{{ __('Soil & Irrigation Details') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="area" :value="__('Area (Acres)')" />
                                    <x-text-input id="area" name="area" type="number" step="0.1" class="mt-1 block w-full" placeholder="2.5" />
                                    <div class="mt-2">
                                        <p class="text-[10px] text-gray-500 mb-1">{{ __('Quick Select') }}:</p>
                                        <div id="area_chips" class="flex flex-wrap gap-2"></div>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="soil_type" :value="__('Soil Type')" />
                                    <select id="soil_type" name="soil_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                                        <option value="" selected>{{ __('Not Specified') }}</option>
                                        <option value="Clay">{{ __('Clay') }}</option>
                                        <option value="Sandy">{{ __('Sandy') }}</option>
                                        <option value="Loamy">{{ __('Loamy') }}</option>
                                    </select>
                                    <div class="mt-2">
                                        <p class="text-[10px] text-gray-500 mb-1">{{ __('Quick Select') }}:</p>
                                        <div id="soil_chips" class="flex flex-wrap gap-2"></div>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="irrigation_method" :value="__('Irrigation Method')" />
                                    <select id="irrigation_method" name="irrigation_method" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                                        <option value="" selected>{{ __('Not Specified') }}</option>
                                        <option value="Flood">{{ __('Flood') }}</option>
                                        <option value="Drip">{{ __('Drip') }}</option>
                                        <option value="Sprinkler">{{ __('Sprinkler') }}</option>
                                    </select>
                                    <div class="mt-2">
                                        <p class="text-[10px] text-gray-500 mb-1">{{ __('Quick Select') }}:</p>
                                        <div id="irrigation_chips" class="flex flex-wrap gap-2"></div>
                                    </div>
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
                                    <div class="mt-2">
                                        <p class="text-[10px] text-gray-500 mb-1">{{ __('Quick Select') }}:</p>
                                        <div id="planting_chips" class="flex flex-wrap gap-2"></div>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="expected_harvest_date" :value="__('Expected Harvest Date')" />
                                    <x-text-input id="expected_harvest_date" name="expected_harvest_date" type="date" class="mt-1 block w-full text-green-700 dark:text-green-500 font-bold" />
                                    <div class="mt-2">
                                        <p class="text-[10px] text-gray-500 mb-1">{{ __('Quick Select') }}:</p>
                                        <div id="harvest_chips" class="flex flex-wrap gap-2"></div>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="seed_source" :value="__('Seed Source')" />
                                    <x-text-input id="seed_source" name="seed_source" type="text" class="mt-1 block w-full" />
                                    <div class="mt-2">
                                        <p class="text-[10px] text-gray-500 mb-1">{{ __('Quick Select') }}:</p>
                                        <div id="seed_chips" class="flex flex-wrap gap-2"></div>
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="yield_estimate" :value="__('Expected Yield (Tons)')" />
                                    <x-text-input id="yield_estimate" name="yield_estimate" type="number" step="0.1" class="mt-1 block w-full" />
                                    <div class="mt-2">
                                        <p class="text-[10px] text-gray-500 mb-1">{{ __('Quick Select') }}:</p>
                                        <div id="yield_chips" class="flex flex-wrap gap-2"></div>
                                    </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Loaded, fetching suggestions...');
            // جلب الاقتراحات من الـ Web Route لضمان المصادقة (Auth)
            fetch("{{ route('crops.suggestions.data') }}")
            .then(response => response.json())
            .then(data => {
                console.log('Suggestions received:', data);
                if (data.success) {
                    const nameContainer = document.getElementById('name_chips');
                    const typeContainer = document.getElementById('type_chips');
                    const areaContainer = document.getElementById('area_chips');
                    const soilContainer = document.getElementById('soil_chips');
                    const irrContainer = document.getElementById('irrigation_chips');
                    const plantingContainer = document.getElementById('planting_chips');
                    const harvestContainer = document.getElementById('harvest_chips');
                    const seedContainer = document.getElementById('seed_chips');
                    const yieldContainer = document.getElementById('yield_chips');
                    const datalist = document.getElementById('crop_name_suggestions');
                    
                    // تنظيف الحاويات أولاً
                    [nameContainer, typeContainer, areaContainer, soilContainer, irrContainer, plantingContainer, harvestContainer, seedContainer, yieldContainer].forEach(c => c.innerHTML = '');
                    
                    // إضافة أزرار الاختيار السريع للاسماء
                    if (data.data.names) {
                        data.data.names.forEach(name => {
                            createChip(name, document.getElementById('name'), nameContainer);
                            const option = document.createElement('option');
                            option.value = name;
                            datalist.appendChild(option);
                        });
                    }

                    // إضافة أزرار الاختيار السريع للأنواع
                    if (data.data.types) {
                        data.data.types.forEach(type => {
                            createChip(type, document.getElementById('type'), typeContainer, true);
                        });
                    }

                    // اختصاصات التربة والري والمساحة
                    const commonAreas = ['1', '2', '5', '10'];
                    const commonSoils = {'Clay': '{{ __('Clay') }}', 'Sandy': '{{ __('Sandy') }}', 'Loamy': '{{ __('Loamy') }}'};
                    const commonIrr = {'Flood': '{{ __('Flood') }}', 'Drip': '{{ __('Drip') }}', 'Sprinkler': '{{ __('Sprinkler') }}'};

                    commonAreas.forEach(a => createChip(a, document.getElementById('area'), areaContainer));
                    Object.entries(commonSoils).forEach(([val, label]) => createChip(label, document.getElementById('soil_type'), soilContainer, true, val));
                    Object.entries(commonIrr).forEach(([val, label]) => createChip(label, document.getElementById('irrigation_method'), irrContainer, true, val));

                    // التواريخ
                    const today = new Date().toISOString().split('T')[0];
                    createChip('{{ __('Today') }}', document.getElementById('planting_date'), plantingContainer, false, today);
                    
                    const harvestOptions = [
                        { label: '{{ __('After 3 Months') }}', months: 3 },
                        { label: '{{ __('After 6 Months') }}', months: 6 },
                        { label: '{{ __('After 1 Year') }}', months: 12 }
                    ];

                    harvestOptions.forEach(opt => {
                        const date = new Date();
                        date.setMonth(date.getMonth() + opt.months);
                        createChip(opt.label, document.getElementById('expected_harvest_date'), harvestContainer, false, date.toISOString().split('T')[0]);
                    });

                    // مصدر البذور والإنتاج
                    const commonSeeds = ['{{ __('Local') }}', '{{ __('Imported') }}', '{{ __('Governmental') }}'];
                    const commonYields = ['1', '5', '10', '20'];
                    commonSeeds.forEach(s => createChip(s, document.getElementById('seed_source'), seedContainer));
                    commonYields.forEach(y => createChip(y, document.getElementById('yield_estimate'), yieldContainer));
                }
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
                const commonNames = ['{{ __('Wheat') }}', '{{ __('Tomato') }}', '{{ __('Field 1') }}'];
                commonNames.forEach(n => createChip(n, document.getElementById('name'), document.getElementById('name_chips')));
            });

            function createChip(label, targetInput, container, isSelect = false, actualValue = null) {
                if (!container) return;
                const button = document.createElement('button');
                button.type = 'button';
                button.innerText = label;
                button.className = 'px-3 py-1 text-[11px] bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-full border border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-800/40 transition transition-all duration-200 shadow-sm whitespace-nowrap';
                
                button.onclick = () => {
                    const finalVal = actualValue || label;
                    if (isSelect) {
                        const options = Array.from(targetInput.options);
                        const match = options.find(opt => 
                            opt.text.trim() === label.trim() || 
                            opt.value.trim() === finalVal.trim()
                        );
                        if (match) targetInput.value = match.value;
                        else {
                            const newOpt = new Option(label, finalVal);
                            targetInput.add(newOpt);
                            targetInput.value = finalVal;
                        }
                    } else {
                        targetInput.value = finalVal;
                    }
                    button.classList.add('ring-2', 'ring-green-500');
                    setTimeout(() => button.classList.remove('ring-2', 'ring-green-500'), 500);
                };
                container.appendChild(button);
            }
        });
    </script>
</x-app-layout>
