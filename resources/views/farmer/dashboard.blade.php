<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold mb-1 text-gray-900 dark:text-white tracking-tight">
                    {{ __('Smart Dashboard') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Overview of systems and production') }}: {{ Auth::user()->name }}</p>
            </div>
            <div class="hidden lg:flex px-3 py-2 border border-gray-200 dark:border-gray-700 items-center gap-2 bg-white dark:bg-gray-800">
                <i class="bi bi-clock-history text-green-600"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ now()->translatedFormat('l, d M Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Expert Guidance -->
        @if($expertTips->isNotEmpty())
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <i class="bi bi-megaphone-fill text-sm text-green-700"></i>
                <h6 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Expert Guidance') }}</h6>
            </div>
            
            <div class="flex flex-wrap gap-4">
                @foreach($expertTips as $tip)
                    <div class="p-4 shadow-sm transition-all bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 border-r-4 border-r-blue-500 min-w-[250px] max-w-sm">
                        <div class="text-xs font-bold mb-1 text-green-700">{{ $tip->title }}</div>
                        <p class="text-xs mb-2 leading-relaxed text-gray-600 dark:text-gray-400">{{ $tip->content }}</p>
                        <div class="flex items-center gap-1 mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-[10px] text-gray-500">{{ __('Dr.') }} {{ $tip->user->name }}</span>
                            <i class="bi bi-patch-check-fill text-blue-500 text-[10px]"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- KPI Pillars -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 text-start">
            <!-- Active Crops -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-bold text-xs text-gray-500">{{ __('Total Crops') }}</span>
                    <i class="bi bi-sprout opacity-50 text-green-500"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-gray-900 dark:text-white">{{ $activeCropsCount }}</h3>
                <div class="text-[10px] text-green-600 font-bold">{{ __('Active Now') }}</div>
            </div>

            <!-- Urgent Tasks -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-bold text-xs text-gray-500">{{ __('Urgent Tasks') }}</span>
                    <i class="bi bi-calendar-check text-red-500 opacity-50"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-red-500">{{ $pendingTasksCount }}</h3>
                <div class="text-[10px] text-red-500 font-bold">{{ __('Require Action') }}</div>
            </div>

            <!-- Water Usage -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-bold text-xs text-gray-500">{{ __('Water Usage') }}</span>
                    <i class="bi bi-droplets text-blue-500 opacity-50"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-blue-500">{{ number_format($weeklyWater) }}</h3>
                <div class="text-[10px] text-gray-500">{{ __('Liters / Weekly') }}</div>
            </div>

            <!-- Harvest Activity -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-bold text-xs text-gray-500">{{ __('Harvest Activity') }}</span>
                    <i class="bi bi-box-seam text-yellow-500 opacity-50"></i>
                </div>
                <h3 class="font-bold text-2xl mb-1 text-yellow-500">{{ $recentHarvestCount }}</h3>
                <div class="text-[10px] text-gray-500">{{ __('Last 30 Days') }}</div>
            </div>
        </div>

        <!-- Analytical Core -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Resource Dynamics -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-4 flex justify-between items-center border-b border-gray-100 dark:border-gray-700">
                    <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Resource Flux Analysis') }}</h5>
                    <div class="px-3 py-1 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-xs font-bold">{{ __('Timeline Chart') }}</div>
                </div>
                <div class="p-4">
                    <div class="h-80 w-full">
                        <canvas id="resourceFluxChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Life Cycle Snapshot -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-4 text-start border-b border-gray-100 dark:border-gray-700">
                    <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Crop Lifecycle Distribution') }}</h5>
                </div>
                <div class="p-4 flex flex-col items-center justify-center">
                    <div class="h-56 w-full mb-4">
                        <canvas id="lifecycleChart"></canvas>
                    </div>
                    <div class="w-full space-y-2">
                        @php
                            $stats = [
                                ['label' => __('Mature Production'), 'color' => '#3f6212', 'percent' => '45%'],
                                ['label' => __('Flowering Stage'), 'color' => '#a3e635', 'percent' => '30%'],
                                ['label' => __('Vegetative Growth'), 'color' => '#bef264', 'percent' => '25%']
                            ];
                        @endphp
                        @foreach($stats as $stat)
                            <div class="flex justify-between items-center px-2 py-1 bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2" style="background-color: {{ $stat['color'] }};"></div>
                                    <span class="text-xs text-gray-500 dark:text-gray-300">{{ $stat['label'] }}</span>
                                </div>
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $stat['percent'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Systems Timeline -->
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
                                <div>
                                    <div class="font-bold text-sm text-gray-900 dark:text-white">{{ $item['title'] }}</div>
                                    <div class="text-[10px] text-gray-500">{{ $item['time'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Climate Summary -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm h-full">
                <div class="p-6 flex flex-col h-full">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h5 class="font-bold text-base text-gray-900 dark:text-white">{{ __('Climate & Environment') }}</h5>
                            <p class="text-xs text-gray-500">{{ __('Real-time farm condition analysis') }}</p>
                        </div>
                        <div class="text-left">
                            <span class="px-3 py-1 text-sm font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600">{{ $weatherData['city'] ?? __('Gaza') }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center gap-6 flex-grow">
                        <div class="w-full md:w-5/12 border-l-0 md:border-l border-gray-200 dark:border-gray-700 pl-4">
                            <div class="flex items-center gap-4">
                                <div class="text-5xl font-bold text-gray-900 dark:text-white">{{ $weatherData['temp'] ?? '23' }}°</div>
                                <div>
                                    <div class="font-bold text-gray-800 dark:text-gray-200">{{ $weatherData['condition'] ?? __('Partly Sunny') }}</div>
                                    <div class="text-xs text-gray-500">{{ __('Wind') }}: {{ $weatherData['wind_speed'] ?? '10' }} {{ __('km/h') }}</div>
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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Cairo', 'Outfit', sans-serif";
            Chart.defaults.color = '#64748b';

            // Resource Flux Area Chart (Weekly Powerful Analytics)
            const fluxCtx = document.getElementById('resourceFluxChart').getContext('2d');
            
            const waterGradient = fluxCtx.createLinearGradient(0, 0, 0, 400);
            waterGradient.addColorStop(0, 'rgba(0, 174, 239, 0.1)');
            waterGradient.addColorStop(1, 'rgba(0, 174, 239, 0)');

            const fertGradient = fluxCtx.createLinearGradient(0, 0, 0, 400);
            fertGradient.addColorStop(0, 'rgba(64, 145, 108, 0.1)');
            fertGradient.addColorStop(1, 'rgba(64, 145, 108, 0)');

            new Chart(fluxCtx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: '{{ __('Water Consumption (L)') }}',
                            data: @json($weeklyWaterData),
                            borderColor: '#00aeef',
                            borderWidth: 2,
                            backgroundColor: waterGradient,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 2,
                            pointHoverRadius: 6,
                        },
                        {
                            label: '{{ __('Fertilizers (g)') }}',
                            data: @json($weeklyFertData),
                            borderColor: '#40916c',
                            borderWidth: 2,
                            backgroundColor: fertGradient,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 2,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            display: true, 
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 10 } }
                        } 
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(128, 128, 128, 0.1)' },
                            ticks: { font: { size: 10 } }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });

            // Lifecycle Snapshot
            const lifeCtx = document.getElementById('lifecycleChart').getContext('2d');
            new Chart(lifeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['{{ __('Production') }}', '{{ __('Flowering') }}', '{{ __('Vegetative Growth') }}'],
                    datasets: [{
                        data: [45, 30, 25],
                        backgroundColor: ['#3f6212', '#a3e635', '#bef264'],
                        borderWidth: 0,
                        cutout: '82%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
