<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center" style="direction: rtl;">
            <div>
                <h1 class="h4 fw-bold mb-1" style="color: var(--reefy-primary); letter-spacing: -0.01em;">
                    {{ __('لوحة القيادة الذكية') }}
                </h1>
                <p class="text-muted small mb-0">نظرة عامة على الأنظمة والإنتاج لمزرعة: {{ Auth::user()->name }}</p>
            </div>
            <div class="d-none d-lg-flex bg-white px-3 py-2 border rounded-3 align-items-center gap-2" style="border-color: #e2eee8 !important; border-radius: 10px !important;">
                <i class="bi bi-clock-history text-success"></i>
                <span class="small fw-bold text-dark">{{ now()->translatedFormat('l, d M Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-4 px-1" style="background-color: #fcfdfc;">
        <!-- KPI Pillars - Ultra Calm & Sharp -->
        <div class="row g-3 mb-4 text-end" style="direction: rtl;">
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: #f1f8f5; border-color: #e2eee8 !important; border-radius: 10px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted fw-bold" style="font-size: 0.75rem;">إجمالي المحاصيل</span>
                        <i class="bi bi-sprout text-success opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--reefy-primary);">{{ $activeCropsCount }}</h3>
                    <div class="very-small text-success fw-bold">نشطة حالياً</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: #fcfdfc; border-color: #e2eee8 !important; border-radius: 10px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted fw-bold" style="font-size: 0.75rem;">المهام العاجلة</span>
                        <i class="bi bi-calendar-check text-danger opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #ef4444;">{{ $pendingTasksCount }}</h3>
                    <div class="very-small text-danger fw-bold">تتطلب إجراء</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: #fcfdfc; border-color: #e2eee8 !important; border-radius: 10px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted fw-bold" style="font-size: 0.75rem;">استهلاك المياه</span>
                        <i class="bi bi-droplets text-info opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #00aeef;">{{ number_format($weeklyWater) }}</h3>
                    <div class="very-small text-muted">لتر / أسبوعي</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: #fcfdfc; border-color: #e2eee8 !important; border-radius: 10px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted fw-bold" style="font-size: 0.75rem;">نشاط الحصاد</span>
                        <i class="bi bi-box-seam text-warning opacity-50"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #f59e0b;">{{ $recentHarvestCount }}</h3>
                    <div class="very-small text-muted">آخر 30 يوم</div>
                </div>
            </div>
        </div>

        <!-- Analytical Core -->
        <div class="row g-4 mb-4">
            <!-- Resource Dynamics (Powerful Analytics) -->
            <div class="col-lg-8">
                <div class="card border shadow-none h-100" style="border-radius: 12px; border-color: #e2eee8 !important;">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center" style="direction: rtl;">
                        <h5 class="fw-bold text-dark mb-0 fs-6">تحليلات تدفق الموارد (مياه/أسمدة)</h5>
                        <div class="badge px-3 py-2 fw-bold text-muted border-0" style="background: #f8fafc; font-size: 0.7rem; border-radius: 8px;">مخطط زمني</div>
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
                <div class="card border shadow-none h-100" style="border-radius: 12px; border-color: #e2eee8 !important;">
                    <div class="card-header bg-white border-0 pt-4 px-4 text-end" style="direction: rtl;">
                        <h5 class="fw-bold text-dark mb-0 fs-6">توزيع دورة حياة المحصول</h5>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                        <div style="height: 220px; width: 100%;" class="mb-4">
                            <canvas id="lifecycleChart"></canvas>
                        </div>
                        <div class="w-100" style="direction: rtl;">
                            @php
                                $stats = [
                                    ['label' => 'إنتاج ناضج', 'color' => '#1b4332', 'percent' => '45%'],
                                    ['label' => 'مرحلة التزهير', 'color' => '#40916c', 'percent' => '30%'],
                                    ['label' => 'نمو خضري', 'color' => '#95d5b2', 'percent' => '25%']
                                ];
                            @endphp
                            @foreach($stats as $stat)
                                <div class="d-flex justify-content-between align-items-center mb-2 px-2 py-1 rounded-2" style="background: #fcfdfc; border: 1px solid #f1f5f9;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 8px; height: 8px; border-radius: 2px; background-color: {{ $stat['color'] }};"></div>
                                        <span class="very-small text-muted">{{ $stat['label'] }}</span>
                                    </div>
                                    <span class="very-small fw-bold text-dark">{{ $stat['percent'] }}</span>
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
                <div class="card border shadow-none overflow-hidden h-100" style="border-radius: 12px; border-color: #e2eee8 !important;">
                    <div class="card-header bg-white border-0 pt-4 px-4 text-end" style="direction: rtl;">
                        <h5 class="fw-bold text-dark mb-0 fs-6">سجل العمليات المباشر</h5>
                    </div>
                    <div class="card-body px-4 pb-4" style="direction: rtl;">
                        <div class="timeline">
                            @php
                                $timeline = [
                                    ['icon' => 'bi-droplets', 'color' => '#00aeef', 'title' => 'بدء نظام الري الآلي', 'time' => 'منذ 5 دقائق'],
                                    ['icon' => 'bi-shield-check', 'color' => '#ef4444', 'title' => 'اكتمال عملية التسميد - حقل أ', 'time' => 'منذ ساعتين'],
                                    ['icon' => 'bi-graph-up', 'color' => '#f59e0b', 'title' => 'تحديث نسبة النمو: طماطم بيوت', 'time' => 'منذ 5 ساعات'],
                                    ['icon' => 'bi-check-circle', 'color' => '#1b4332', 'title' => 'تسجيل كمية حصاد جديدة', 'time' => 'أمس']
                                ];
                            @endphp
                            @foreach($timeline as $item)
                                <div class="timeline-item d-flex gap-3 mb-4 last-child-mb-0">
                                    <div class="flex-shrink-0 d-flex flex-column align-items-center">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center border" 
                                             style="width: 38px; height: 38px; background: white; border-color: #e2eee8 !important; border-radius: 8px !important;">
                                            <i class="bi {{ $item['icon'] }}" style="color: {{ $item['color'] }};"></i>
                                        </div>
                                        <div class="flex-grow-1 border-end my-2" style="border-color: #e2eee8 !important; border-style: dashed !important;"></div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $item['title'] }}</div>
                                        <div class="very-small text-muted">{{ $item['time'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Climate Summary - Ultra Light -->
            <div class="col-lg-8">
                <div class="card border shadow-none h-100" style="border-radius: 12px; border-color: #e2eee8 !important; background-color: #f1f8f5;">
                    <div class="card-body p-4 d-flex flex-column" style="direction: rtl;">
                        <div class="d-flex justify-content-between mb-4">
                            <div>
                                <h5 class="fw-bold mb-1" style="color: var(--reefy-primary);">البيئة والمناخ</h5>
                                <p class="very-small text-muted mb-0">تحليل فوري لظروف المزرعة الخارجية والداخلية</p>
                            </div>
                            <div class="text-start">
                                <span class="badge rounded-pill px-3 py-2 bg-white text-dark border shadow-none" style="border-color: #e2eee8 !important; border-radius: 8px !important;">{{ $weatherData['city'] ?? 'غزة' }}</span>
                            </div>
                        </div>

                        <div class="row g-3 align-items-center flex-grow-1">
                            <div class="col-md-5 border-start-md" style="border-color: #e2eee8 !important;">
                                <div class="d-flex align-items-center gap-4">
                                    <div class="display-3 fw-bold mb-0" style="color: var(--reefy-primary);">{{ $weatherData['temp'] ?? '23' }}°</div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $weatherData['condition'] ?? 'مشمس جزئياً' }}</div>
                                        <div class="very-small text-muted">الرياح: {{ $weatherData['wind_speed'] ?? '10' }} كم/س</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row g-2 text-center">
                                    <div class="col-4">
                                        <div class="p-3 bg-white border rounded-3" style="border-color: #e2eee8 !important; border-radius: 10px !important;">
                                            <i class="bi bi-water text-info mb-1 d-block"></i>
                                            <span class="fw-bold d-block small">45%</span>
                                            <small class="very-small text-muted">رطوبة</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-3 bg-white border rounded-3" style="border-color: #e2eee8 !important; border-radius: 10px !important;">
                                            <i class="bi bi-thermometer-sun text-warning mb-1 d-block"></i>
                                            <span class="fw-bold d-block small">11</span>
                                            <small class="very-small text-muted">UV</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-3 bg-white border rounded-3" style="border-color: #e2eee8 !important; border-radius: 10px !important;">
                                            <i class="bi bi-clock text-secondary mb-1 d-block"></i>
                                            <span class="fw-bold d-block small">10 سم</span>
                                            <small class="very-small text-muted">تبخر</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-white border rounded-3 d-flex align-items-center gap-3" style="border-color: #e2eee8 !important; border-radius: 10px !important; border-style: dashed !important;">
                            <div class="bg-success bg-opacity-10 p-2 rounded-2">
                                <i class="bi bi-lightbulb text-success"></i>
                            </div>
                            <div>
                                <span class="very-small fw-bold text-dark d-block">نصيحة اليوم:</span>
                                <p class="very-small text-muted mb-0">الرطوبة مرتفعة، ينصح بتأخير التسميد السائل حتى ساعات الصباح الباكر لضمان امتصاص أفضل.</p>
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
                            grid: { color: '#f1f5f9' },
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
                        backgroundColor: ['#1b4332', '#40916c', '#95d5b2'],
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
        @media (min-width: 768px) {
            .border-start-md { border-right: 1px solid transparent; border-left: 1px solid transparent; }
            [dir="rtl"] .border-start-md { border-left: 1px solid #e2eee8 !important; }
        }
    </style>
</x-app-layout>
