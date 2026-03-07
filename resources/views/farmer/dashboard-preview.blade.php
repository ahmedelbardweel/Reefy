<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold mb-1 text-gray-900 dark:text-white tracking-tight">
                    {{ __('Smart Dashboard') }} <span class="text-sm font-normal text-red-500 ml-2 px-2 py-1 rounded bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700">Preview Mode</span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Overview of systems and production') }}: Designer Preview</p>
            </div>
            <div class="hidden lg:flex px-3 py-2 border border-gray-200 dark:border-gray-700 items-center gap-2 bg-white dark:bg-gray-800">
                <i class="bi bi-clock-history text-green-600"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ now()->translatedFormat('l, d M Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Expert Guidance -->
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <i class="bi bi-megaphone-fill text-sm text-green-700"></i>
                <h6 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Expert Guidance') }}</h6>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-4 shadow-sm transition-all bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 border-r-4 border-r-blue-500 flex flex-col h-full">
                    <div class="text-xs font-bold mb-1 text-green-700">Optimal Harvest Time</div>
                    <p class="text-xs mb-2 leading-relaxed text-gray-600 dark:text-gray-400 flex-grow">Current indicators suggest harvesting the greenhouse tomatoes within 48 hours to maximize yield quality.</p>
                    <div class="flex items-center gap-1 mt-auto pt-2 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-[10px] text-gray-500">{{ __('Dr.') }} Ahmed Salem</span>
                        <i class="bi bi-patch-check-fill text-blue-500 text-[10px]"></i>
                    </div>
                </div>
                <div class="p-4 shadow-sm transition-all bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 border-r-4 border-r-blue-500 flex flex-col h-full">
                    <div class="text-xs font-bold mb-1 text-green-700">Irrigation Setup Notice</div>
                    <p class="text-xs mb-2 leading-relaxed text-gray-600 dark:text-gray-400 flex-grow">Due to expected high temperatures tomorrow, ensure Field B irrigation runs 20% longer during the morning phase.</p>
                    <div class="flex items-center gap-1 mt-auto pt-2 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-[10px] text-gray-500">{{ __('Dr.') }} Sarah Ali</span>
                        <i class="bi bi-patch-check-fill text-blue-500 text-[10px]"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Pillars -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 text-start">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-bold text-xs text-gray-500">{{ __('Total Crops') }}</span>
                    <i class="bi bi-sprout opacity-50 text-green-500"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-gray-900 dark:text-white">12</h3>
                <div class="text-[10px] text-green-600 font-bold">{{ __('Active Now') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-bold text-xs text-gray-500">{{ __('Urgent Tasks') }}</span>
                    <i class="bi bi-calendar-check text-red-500 opacity-50"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-red-500">3</h3>
                <div class="text-[10px] text-red-500 font-bold">{{ __('Require Action') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-bold text-xs text-gray-500">{{ __('Water Usage') }}</span>
                    <i class="bi bi-droplets text-blue-500 opacity-50"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-blue-500">2,450</h3>
                <div class="text-[10px] text-gray-500">{{ __('Liters / Weekly') }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-bold text-xs text-gray-500">{{ __('Harvest Activity') }}</span>
                    <i class="bi bi-box-seam text-yellow-500 opacity-50"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-yellow-500">8</h3>
                <div class="text-[10px] text-gray-500">{{ __('Last 30 Days') }}</div>
            </div>
        </div>

        <!-- Analytical Core -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-4 flex justify-between items-center border-b border-gray-100 dark:border-gray-700">
                    <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Resource Flux Analysis') }}</h5>
                    <div class="px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs font-bold">{{ __('Weekly Stats') }}</div>
                </div>
                <div class="p-4 overflow-x-auto">
                    <table class="w-full text-xs text-gray-600 dark:text-gray-400">
                        <thead>
                            <tr class="border-b border-gray-50 dark:border-gray-700">
                                <th class="text-start py-2 font-bold">{{ __('Day') }}</th>
                                <th class="text-center py-2 font-bold">{{ __('Water (L)') }}</th>
                                <th class="text-center py-2 font-bold">{{ __('Fertilizers (g)') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                $water = [300, 400, 320, 500, 420, 350, 450];
                                $fert = [50, 60, 40, 70, 50, 45, 65];
                            @endphp
                            @foreach($days as $index => $day)
                                <tr class="border-b border-gray-50 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="py-3 font-medium text-gray-900 dark:text-gray-200">{{ $day }}</td>
                                    <td class="text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="font-bold text-blue-600">{{ $water[$index] }}</span>
                                            <div class="w-20 bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-blue-500 h-full" style="width: {{ ($water[$index]/500)*100 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="font-bold text-green-600">{{ $fert[$index] }}</span>
                                            <div class="w-20 bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                                <div class="bg-green-500 h-full" style="width: {{ ($fert[$index]/70)*100 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-4 text-start border-b border-gray-100 dark:border-gray-700">
                    <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Crop Lifecycle Distribution') }}</h5>
                </div>
                <div class="p-6 space-y-6">
                    @php
                        $stats = [
                            ['label' => __('Mature Production'), 'color' => 'bg-green-800', 'percent' => 45],
                            ['label' => __('Flowering Stage'), 'color' => 'bg-green-400', 'percent' => 30],
                            ['label' => __('Vegetative Growth'), 'color' => 'bg-lime-300', 'percent' => 25]
                        ];
                    @endphp
                    @foreach($stats as $stat)
                        <div>
                            <div class="flex justify-between items-center mb-1 text-xs">
                                <span class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider text-[10px]">{{ $stat['label'] }}</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $stat['percent'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 h-3 border border-gray-200 dark:border-gray-600">
                                <div class="{{ $stat['color'] }} h-full" style="width: {{ $stat['percent'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden h-full">
                <div class="p-4 text-start border-b border-gray-100 dark:border-gray-700">
                    <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Live Operation Log') }}</h5>
                </div>
                <div class="p-4">
                    <div class="relative">
                        @php
                            $timeline = [
                                ['icon' => 'bi-droplets', 'color' => 'text-blue-500', 'title' => __('Start automated irrigation system'), 'time' => __('5 minutes ago')],
                                ['icon' => 'bi-shield-check', 'color' => 'text-red-500', 'title' => __('Completion of fertilization process - Field A'), 'time' => __('2 hours ago')],
                                ['icon' => 'bi-graph-up', 'color' => 'text-yellow-500', 'title' => __('Growth percentage update: Greenhouse Tomatoes'), 'time' => __('5 hours ago')],
                                ['icon' => 'bi-check-circle', 'color' => 'text-green-700', 'title' => __('Registration of a new harvest quantity'), 'time' => __('Yesterday')]
                            ];
                        @endphp
                        @foreach($timeline as $item)
                            <div class="flex gap-3 mb-4 last:mb-0">
                                <div class="flex-shrink-0 flex flex-col items-center">
                                    <div class="w-10 h-10 flex items-center justify-center border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                        <i class="bi {{ $item['icon'] }} {{ $item['color'] }}"></i>
                                    </div>
                                    <div class="flex-grow border-r border-dashed border-gray-200 dark:border-gray-600 my-2 last:hidden"></div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-sm text-gray-900 dark:text-white truncate">{{ $item['title'] }}</div>
                                    <div class="text-[10px] text-gray-500">{{ $item['time'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm h-full">
                <div class="p-6 flex flex-col h-full">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h5 class="font-bold text-base text-gray-900 dark:text-white">{{ __('Climate & Environment') }}</h5>
                            <p class="text-xs text-gray-500">{{ __('Real-time farm condition analysis') }}</p>
                        </div>
                        <div class="text-left">
                            <span class="px-3 py-1 text-sm font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600">Gaza</span>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row items-center gap-6 flex-grow">
                        <div class="w-full md:w-5/12 border-l-0 md:border-l border-gray-200 dark:border-gray-700 pl-4 text-start">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl font-bold text-gray-900 dark:text-white">23°</div>
                                <div>
                                    <div class="font-bold text-gray-800 dark:text-gray-200">{{ __('Partly Sunny') }}</div>
                                    <div class="text-xs text-gray-500">{{ __('Wind') }}: 10 {{ __('km/h') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-7/12">
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="p-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                    <i class="bi bi-water text-blue-500 mb-1 block"></i>
                                    <span class="font-bold block text-sm text-gray-900 dark:text-white">45%</span>
                                    <small class="text-[10px] text-gray-500">{{ __('Humidity') }}</small>
                                </div>
                                <div class="p-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                    <i class="bi bi-thermometer-sun text-yellow-500 mb-1 block"></i>
                                    <span class="font-bold block text-sm text-gray-900 dark:text-white">11</span>
                                    <small class="text-[10px] text-gray-500">UV</small>
                                </div>
                                <div class="p-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                    <i class="bi bi-clock text-gray-500 mb-1 block"></i>
                                    <span class="font-bold block text-sm text-gray-900 dark:text-white">10 سم</span>
                                    <small class="text-[10px] text-gray-500">{{ __('Evaporation') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
