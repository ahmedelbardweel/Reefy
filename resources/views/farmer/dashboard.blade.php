<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center" style="direction: rtl;">
            <div>
                <h1 class="h4 fw-bold mb-1" style="color: var(--heading-color); letter-spacing: -0.01em;">
                    {{ __('لوحة القيادة الذكية') }}
                </h1>
                <p class="small mb-0" style="color: var(--text-secondary);">نظرة عامة على الأنظمة والإنتاج لمزرعة: {{ Auth::user()->name }}</p>
            </div>
            <div class="d-none d-lg-flex px-3 py-2 border align-items-center gap-2" 
                 style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 0px !important;">
                <i class="bi bi-clock-history" style="color: var(--reefy-success);"></i>
                <span class="small fw-bold" style="color: var(--text-primary);">{{ now()->translatedFormat('l, d M Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-4 px-1">
        <!-- Expert Guidance - Sharp & Simple -->
        @if($expertTips->isNotEmpty())
        <div class="container-fluid mb-4 px-3" style="direction: rtl;">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-megaphone-fill small" style="color: var(--reefy-primary);"></i>
                <h6 class="fw-bold mb-0 small" style="color: var(--heading-color);">توجيهات الخبراء</h6>
            </div>
            
            <div class="d-flex flex-wrap gap-3">
                @foreach($expertTips as $tip)
                    <div class="p-3 shadow-sm transition-all" style="background-color: var(--bg-secondary); border: 1px solid var(--border-color); border-right: 4px solid #00aeef !important; border-radius: 0 !important; min-width: 250px; max-width: 350px; width: fit-content;">
                        <div class="very-small fw-bold mb-1" style="color: var(--reefy-primary);">{{ $tip->title }}</div>
                        <p class="very-small mb-2 ls-tight" style="color: var(--text-secondary); line-height: 1.4;">{{ $tip->content }}</p>
                        <div class="d-flex align-items-center gap-1 mt-2 pt-2 border-top" style="border-color: var(--border-color) !important;">
                            <span class="very-small" style="color: var(--text-secondary);">د. {{ $tip->user->name }}</span>
                            <i class="bi bi-patch-check-fill text-info" style="font-size: 0.65rem;"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- KPI Pillars - Ultra Calm & Sharp -->
        <div class="row g-3 mb-4 text-end" style="direction: rtl;">
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 0px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="fw-bold" style="color: var(--text-secondary); font-size: 0.75rem;">إجمالي المحاصيل</span>
                        <i class="bi bi-sprout opacity-50" style="color: var(--reefy-success);"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--heading-color);">{{ $activeCropsCount }}</h3>
                    <div class="very-small text-success fw-bold">نشطة حالياً</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 10px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="fw-bold" style="color: var(--text-secondary); font-size: 0.75rem;">المهام العاجلة</span>
                        <i class="bi bi-calendar-check text-danger opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #ef4444;">{{ $pendingTasksCount }}</h3>
                    <div class="very-small text-danger fw-bold">تتطلب إجراء</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 10px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="fw-bold" style="color: var(--text-secondary); font-size: 0.75rem;">استهلاك المياه</span>
                        <i class="bi bi-droplets text-info opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #00aeef;">{{ number_format($weeklyWater) }}</h3>
                    <div class="very-small" style="color: var(--text-secondary);">لتر / أسبوعي</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 10px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="fw-bold" style="color: var(--text-secondary); font-size: 0.75rem;">نشاط الحصاد</span>
                        <i class="bi bi-box-seam text-warning opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #f59e0b;">{{ $recentHarvestCount }}</h3>
                    <div class="very-small" style="color: var(--text-secondary);">آخر 30 يوم</div>
                </div>
            </div>
        </div>

        <!-- Analytical Core -->
        <div class="row g-4 mb-4">
            <!-- Resource Dynamics (Powerful Analytics) -->
            <div class="col-lg-8">
                <div class="card border shadow-none h-100" style="background-color: var(--card-bg); border-radius: 0px; border-color: var(--border-color) !important;">
                    <div class="card-header border-0 pt-4 px-4 d-flex justify-content-between align-items-center" style="background-color: var(--card-bg); direction: rtl;">
                        <h5 class="fw-bold mb-0 fs-6" style="color: var(--heading-color);">تحليلات تدفق الموارد (مياه/أسمدة)</h5>
                        <div class="badge px-3 py-2 fw-bold border-0" style="background: var(--bg-primary); color: var(--text-secondary); font-size: 0.7rem; border-radius: 0px;">مخطط زمني</div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div style="height: 320px; width: 100%;">
                            <canvas id="resourceFluxChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Life Cycle Snapshot -->
            <div class="col-lg-4">
                <div class="card border shadow-none h-100" style="background-color: var(--card-bg); border-radius: 12px; border-color: var(--border-color) !important;">
                    <div class="card-header border-0 pt-4 px-4 text-end" style="background-color: var(--card-bg); direction: rtl;">
                        <h5 class="fw-bold mb-0 fs-6" style="color: var(--heading-color);">توزيع دورة حياة المحصول</h5>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                        <div style="height: 220px; width: 100%;" class="mb-4">
                            <canvas id="lifecycleChart"></canvas>
                        </div>
                        <div class="w-100" style="direction: rtl;">
                            @php
                                $stats = [
                                    ['label' => 'إنتاج ناضج', 'color' => '#3f6212', 'percent' => '45%'],
                                    ['label' => 'مرحلة التزهير', 'color' => '#a3e635', 'percent' => '30%'],
                                    ['label' => 'نمو خضري', 'color' => '#bef264', 'percent' => '25%']
                                ];
                            @endphp
                            @foreach($stats as $stat)
                                <div class="d-flex justify-content-between align-items-center mb-2 px-2 py-1 rounded-2" style="background: var(--bg-primary); border: 1px solid var(--border-color);">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 8px; height: 8px; border-radius: 2px; background-color: {{ $stat['color'] }};"></div>
                                        <span class="very-small" style="color: var(--text-secondary);">{{ $stat['label'] }}</span>
                                    </div>
                                    <span class="very-small fw-bold" style="color: var(--text-primary);">{{ $stat['percent'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Systems Timeline (Live Experience) -->
            <div class="col-lg-4">
                <div class="card border shadow-none overflow-hidden h-100" style="background-color: var(--card-bg); border-radius: 12px; border-color: var(--border-color) !important;">
                    <div class="card-header border-0 pt-4 px-4 text-end" style="background-color: var(--card-bg); direction: rtl;">
                        <h5 class="fw-bold mb-0 fs-6" style="color: var(--heading-color);">سجل العمليات المباشر</h5>
                    </div>
                    <div class="card-body px-4 pb-4" style="direction: rtl;">
                        <div class="timeline">
                            @php
                                $timeline = [
                                    ['icon' => 'bi-droplets', 'color' => '#00aeef', 'title' => 'بدء نظام الري الآلي', 'time' => 'منذ 5 دقائق'],
                                    ['icon' => 'bi-shield-check', 'color' => '#ef4444', 'title' => 'اكتمال عملية التسميد - حقل أ', 'time' => 'منذ ساعتين'],
                                    ['icon' => 'bi-graph-up', 'color' => '#f59e0b', 'title' => 'تحديث نسبة النمو: طماطم بيوت', 'time' => 'منذ 5 ساعات'],
                                    ['icon' => 'bi-check-circle', 'color' => '#3f6212', 'title' => 'تسجيل كمية حصاد جديدة', 'time' => 'أمس']
                                ];
                            @endphp
                            @foreach($timeline as $item)
                                <div class="timeline-item d-flex gap-3 mb-4 last-child-mb-0">
                                    <div class="flex-shrink-0 d-flex flex-column align-items-center">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center border" 
                                             style="width: 38px; height: 38px; background: var(--bg-primary); border-color: var(--border-color) !important; border-radius: 8px !important;">
                                            <i class="bi {{ $item['icon'] }}" style="color: {{ $item['color'] }};"></i>
                                        </div>
                                        <div class="flex-grow-1 border-end my-2" style="border-color: var(--border-color) !important; border-style: dashed !important;"></div>
                                    </div>
                                    <div>
                                        <div class="fw-bold small" style="color: var(--text-primary);">{{ $item['title'] }}</div>
                                        <div class="very-small" style="color: var(--text-secondary);">{{ $item['time'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Climate Summary - Ultra Light -->
            <div class="col-lg-8">
                <div class="card border shadow-none h-100" style="border-radius: 12px; border-color: var(--border-color) !important; background-color: var(--bg-secondary);">
                    <div class="card-body p-4 d-flex flex-column" style="direction: rtl;">
                        <div class="d-flex justify-content-between mb-4">
                            <div>
                                <h5 class="fw-bold mb-1" style="color: var(--heading-color);">البيئة والمناخ</h5>
                                <p class="very-small mb-0" style="color: var(--text-secondary);">تحليل فوري لظروف المزرعة الخارجية والداخلية</p>
                            </div>
                            <div class="text-start">
                                <span class="badge rounded-pill px-3 py-2 text-dark border shadow-none" style="background-color: var(--bg-primary); color: var(--text-primary) !important; border-color: var(--border-color) !important; border-radius: 8px !important;">{{ $weatherData['city'] ?? 'غزة' }}</span>
                            </div>
                        </div>

                        <div class="row g-3 align-items-center flex-grow-1">
                            <div class="col-md-5 border-start-md" style="border-color: var(--border-color) !important;">
                                <div class="d-flex align-items-center gap-4">
                                    <div class="display-3 fw-bold mb-0" style="color: var(--heading-color);">{{ $weatherData['temp'] ?? '23' }}°</div>
                                    <div>
                                        <div class="fw-bold" style="color: var(--text-primary);">{{ $weatherData['condition'] ?? 'مشمس جزئياً' }}</div>
                                        <div class="very-small" style="color: var(--text-secondary);">الرياح: {{ $weatherData['wind_speed'] ?? '10' }} كم/س</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row g-2 text-center">
                                    <div class="col-4">
                                        <div class="p-3 border rounded-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 10px !important;">
                                            <i class="bi bi-water text-info mb-1 d-block"></i>
                                            <span class="fw-bold d-block small" style="color: var(--text-primary);">45%</span>
                                            <small class="very-small" style="color: var(--text-secondary);">رطوبة</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-3 border rounded-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 10px !important;">
                                            <i class="bi bi-thermometer-sun text-warning mb-1 d-block"></i>
                                            <span class="fw-bold d-block small" style="color: var(--text-primary);">11</span>
                                            <small class="very-small" style="color: var(--text-secondary);">UV</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-3 border rounded-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 10px !important;">
                                            <i class="bi bi-clock text-secondary mb-1 d-block"></i>
                                            <span class="fw-bold d-block small" style="color: var(--text-primary);">10 سم</span>
                                            <small class="very-small" style="color: var(--text-secondary);">تبخر</small>
                                        </div>
                                    </div>
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
                            label: 'استهلاك المياه (لتر)',
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
                            label: 'الأسمدة (جم)',
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
                    labels: ['إنتاج', 'تزهير', 'نمو خضري'],
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

    <style>
        .very-small { font-size: 0.65rem; }
        .timeline-item:last-child .border-end { display: none; }
        .last-child-mb-0:last-child { margin-bottom: 0 !important; }
        .last-child-border-0:last-child { border-bottom: 0 !important; }
        @media (min-width: 768px) {
            .border-start-md { border-right: 1px solid transparent; border-left: 1px solid transparent; }
            [dir="rtl"] .border-start-md { border-left: 1px solid var(--border-color) !important; }
        }
    </style>
</x-app-layout>
