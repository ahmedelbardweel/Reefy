<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 text-dark mb-0 font-weight-bold">
                <i class="bi bi-flower1 me-2 text-success"></i>إدارة المحاصيل
            </h2>
            <a href="{{ route('crops.create') }}" class="btn rounded-pill px-4 py-2 border-2 fw-bold transition-all hover-up" 
               style="background-color: #f1f8f5; color: var(--reefy-primary); border: 2.5px solid #e2eee8; font-size: 0.9rem;">
                <i class="bi bi-plus-lg me-1"></i> إضافة محصول
            </a>
        </div>
    </x-slot>

    <div class="row g-3 g-lg-4">
        @forelse($crops as $crop)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 8px;">
                    <!-- Gallery/Image Carousel -->
                    <div id="carouselCrop{{ $crop->id }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner rounded-top">
                            @if($crop->images->count() > 0)
                                @foreach($crop->images as $key => $image)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" style="height: 200px; object-fit: cover;" alt="{{ $crop->name }}">
                                    </div>
                                @endforeach
                            @else
                                <div class="carousel-item active">
                                    <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 200px;">
                                        <i class="bi bi-image fs-1 opacity-50"></i>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($crop->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCrop{{ $crop->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon header-carousel-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">السابق</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselCrop{{ $crop->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon header-carousel-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">التالي</span>
                            </button>
                        @endif
                        
                        <!-- Status Badge Overlay (Glassmorphic) -->
                        <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                            <span class="badge shadow-sm px-3 py-2 rounded-3 d-inline-flex align-items-center gap-2" 
                                  style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); color: #2d3436; font-weight: 800; border: 1px solid rgba(255,255,255,0.5) !important; font-size: 0.75rem; width: fit-content;">
                                <i class="bi bi-circle-fill ripple-status" style="font-size: 0.5rem; color: var(--bs-{{ $crop->status_color == 'success' ? 'success' : ($crop->status_color == 'warning' ? 'warning' : 'danger') }});"></i>
                                {{ $crop->status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-3 pt-2 d-flex flex-column h-100">
                        <!-- Card Header: Title & Category Label -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-end" style="direction: rtl;">
                                <h5 class="fw-bold mb-0 card-title h6" style="letter-spacing: -0.01em; color: var(--reefy-primary);">{{ $crop->name }}</h5>
                            </div>
                            <span class="badge rounded-pill px-3 py-1 fw-bold" style="background-color: #f1f8f5; color: var(--reefy-primary); font-size: 0.7rem; border: 1.5px solid #e2eee8; letter-spacing: 0.01em;">
                                {{ $crop->type }}
                            </span>
                        </div>

                        <!-- Crop Specifications (Right Aligned - Softened) -->
                        <div class="mb-3 border-top pt-2" style="direction: rtl;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted very-small">تاريخ الزراعة:</span>
                                <span class="text-secondary very-small fw-normal">{{ $crop->planting_date ? $crop->planting_date->format('Y-m-d') : '---' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted very-small">حالة المحصول:</span>
                                <span class="very-small fw-bold" style="color: var(--reefy-success);">
                                    @if($crop->status == 'harvested')
                                        تم الحصاد <i class="bi bi-check-circle-fill ms-1"></i>
                                    @else
                                        {{ $crop->growth_stage_label }}
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted very-small">طريقة الري:</span>
                                <span class="text-secondary very-small fw-normal">{{ $crop->irrigation_method ?? 'غير محدد' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted very-small">نوع التربة:</span>
                                <span class="text-secondary very-small fw-normal">{{ $crop->soil_type ?? 'غير محدد' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted very-small">مصدر البذور:</span>
                                <span class="text-secondary very-small fw-normal">{{ $crop->seed_source ?? 'غير محدد' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted very-small">المساحة:</span>
                                <span class="text-secondary very-small fw-normal">{{ $crop->area }} فدان</span>
                            </div>
                        </div>

                        <!-- Growth Progress Bar -->
                        <div class="mb-3" style="direction: rtl;">
                            @php
                                $p = $crop->growth_percentage;
                                $barColor = '#ef4444'; // Default
                                if ($p == 100) $barColor = '#ffc107'; // Yellow for READY FOR HARVEST as requested
                                elseif ($p <= 20) $barColor = '#a3e635'; // Lime for seedling
                                elseif ($p <= 50) $barColor = '#22c55e'; // Green for vegetative
                                elseif ($p <= 80) $barColor = '#eab308'; // Amber for flowering
                                else $barColor = '#16a34a'; // Deep green for fruiting
                                
                                $barBg = $barColor . '15'; // 15% opacity for background
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="very-small fw-bold text-muted">تقدم النمو:</span>
                                <span class="very-small fw-bold" style="color: {{ $barColor }};">{{ $p }}% ({{ $crop->growth_stage_label }})</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: {{ $barBg }}; border-radius: 4px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $p }}%; background-color: {{ $barColor }}; border-radius: 4px;" aria-valuenow="{{ $p }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>



                        <!-- Actions: Delete & Edit (from mockup) -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('crops.edit', $crop) }}" class="btn btn-outline-primary btn-sm px-4 py-2 rounded-2 flex-grow-1 fw-bold" style="font-size: 0.85rem;">
                                <i class="bi bi-pencil-square me-1"></i> تعديل المعلومات
                            </a>
                            <form action="{{ route('crops.destroy', $crop) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المحصول؟');" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-2 rounded-2">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>

                        <hr class="my-3 opacity-10">

                        <!-- Task & Activity Section (Mockup Refinement) -->
                        <div class="mb-3" style="direction: rtl;">
                            @php 
                                $pendingTasks = $crop->tasks->where('status', 'pending')->sortBy('due_date');
                                $completedTasks = $crop->tasks->where('status', 'completed')->sortByDesc('updated_at')->take(2);
                                $now = now();
                            @endphp
                            
                            <!-- المهام القادمة (تفاعلية) -->
                            @if($pendingTasks->count() > 0)
                                <h6 class="text-muted fw-bold very-small text-uppercase mb-2">المهام القادمة:</h6>
                                @foreach($pendingTasks as $task)
                                    @php
                                        $isOverdue = $task->due_date->isPast() && !$task->due_date->isToday();
                                        $bgColor = $isOverdue ? '#fff1f2' : '#f0f9ff';
                                        $borderColor = $isOverdue ? '#ef4444' : '#0ea5e9';
                                        $textColor = $isOverdue ? 'text-danger' : 'text-info';
                                        $btnColor = $isOverdue ? '#be123c' : '#0369a1';
                                    @endphp
                                    <div class="task-item d-flex align-items-center justify-content-between p-2 rounded-2 mb-2 shadow-sm" style="background-color: {{ $bgColor }}; border-right: 3px solid {{ $borderColor }};">
                                        <div class="d-flex align-items-center gap-1">
                                            <form action="{{ route('tasks.complete', $task->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="border-0 rounded-1 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; background-color: {{ $btnColor }}; color: white;" title="إتمام المهمة">
                                                    <i class="bi bi-check" style="font-size: 0.8rem;"></i>
                                                </button>
                                            </form>
                                            <span class="text-muted" style="direction: ltr; font-size: 0.65rem;">{{ $task->due_date->format('M d') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-1 flex-row-reverse text-end">
                                            <div class="rounded-circle" style="width: 6px; height: 6px; background-color: {{ $borderColor }};"></div>
                                            <span class="{{ $textColor }} fw-bold" style="font-size: 0.75rem;">{{ $task->title }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <!-- سجل النشاط (للقراءة فقط) -->
                            @if($completedTasks->count() > 0)
                                <h6 class="text-muted fw-bold very-small text-uppercase mt-3 mb-2">سجل النشاط (الأحدث):</h6>
                                @foreach($completedTasks as $task)
                                    <div class="task-item d-flex align-items-center justify-content-between p-2 rounded-2 mb-1" style="background-color: #f8fafc; border: 1px dashed #cbd5e1; opacity: 0.8;">
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="bi bi-check2-circle text-success fs-6"></i>
                                            <span class="text-muted" style="direction: ltr; font-size: 0.6rem;">{{ $task->updated_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="text-end">
                                            <span class="text-secondary fw-normal" style="font-size: 0.7rem;">{{ $task->title }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($pendingTasks->count() == 0 && $completedTasks->count() == 0)
                                <div class="p-2 text-center bg-light rounded-3 text-muted very-small">لا توجد مهام أو أنشطة حالياً</div>
                            @endif
                        </div>

                        <!-- Control Center (مركز التحكم) - Anchored to Bottom -->
                        <div class="mt-auto pt-3" style="direction: rtl;">
                            <h6 class="fw-bold mb-3" style="color: var(--reefy-primary); font-size: 0.9rem;">مركز التحكم:</h6>
                            <div class="row g-2 mb-3">
                                <div class="col-3">
                                    <button type="button" class="btn w-100 p-0 shadow-sm transition-all hover-up border-2" 
                                            style="border: 2px solid #00aeef !important; border-radius: 10px !important; background: white; min-height: 52px;"
                                            data-bs-toggle="modal" data-bs-target="#irrigationModal{{ $crop->id }}">
                                        <span class="fw-bold fs-7" style="color: #00aeef; font-size: 0.75rem;">الري</span>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn w-100 p-0 shadow-sm transition-all hover-up border-2" 
                                            style="border: 2px solid #ef4444 !important; border-radius: 10px !important; background: white; min-height: 52px;"
                                            data-bs-toggle="modal" data-bs-target="#treatmentModal{{ $crop->id }}">
                                        <span class="fw-bold fs-7" style="color: #ef4444; font-size: 0.75rem;">المعالجة</span>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn w-100 p-0 shadow-sm transition-all hover-up border-2" 
                                            style="border: 2px solid #1b4332 !important; border-radius: 10px !important; background: white; min-height: 52px;"
                                            data-bs-toggle="modal" data-bs-target="#harvestModal{{ $crop->id }}">
                                        <span class="fw-bold fs-7" style="color: #1b4332; font-size: 0.75rem;">الحصاد</span>
                                    </button>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn w-100 p-0 shadow-sm transition-all hover-up border-2" 
                                            style="border: 2px solid #f59e0b !important; border-radius: 10px !important; background: white; min-height: 52px;"
                                            data-bs-toggle="modal" data-bs-target="#growthModal{{ $crop->id }}">
                                        <span class="fw-bold fs-7" style="color: #f59e0b; font-size: 0.75rem;">النمو</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Actions: Task Management -->
                            <button class="btn btn-outline-secondary w-100 py-2 very-small d-flex align-items-center justify-content-center gap-2" style="border-radius: 8px; border: 1px dashed #ccc; color: #666;" data-bs-toggle="modal" data-bs-target="#addTaskModal{{ $crop->id }}">
                                إضافة مهمة سريعة <i class="bi bi-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Growth Progress Modal -->
            <div class="modal fade" id="growthModal{{ $crop->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-3 text-end" style="direction: rtl;">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="fw-bold"><i class="bi bi-graph-up-arrow text-warning me-2"></i>تحديث تقدم النمو: {{ $crop->name }}</h5>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" style="margin-right: auto !important;"></button>
                        </div>
                        <form action="{{ route('crops.updateGrowth', $crop) }}" method="POST">
                            @csrf
                            <div class="modal-body p-4">
                                <p class="text-muted small text-center mb-4">ما هي التغيرات التي تلاحظها على المحصول حالياً؟</p>
                                
                                <div class="list-group list-group-flush" style="border-radius: 8px; overflow: hidden;">
                                    <label class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 mb-2 shadow-sm" style="background-color: #f8fafc; border-radius: 8px !important;">
                                        <input class="form-check-input flex-shrink-0" type="radio" name="growth_percentage" value="10" {{ $crop->growth_percentage <= 10 ? 'checked' : '' }}>
                                        <span class="d-flex flex-column">
                                            <strong class="text-dark small">ظهور البادرات فوق التربة</strong>
                                            <span class="very-small text-muted">بداية خروج النبات من الأرض</span>
                                        </span>
                                    </label>

                                    <label class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 mb-2 shadow-sm" style="background-color: #f8fafc; border-radius: 8px !important;">
                                        <input class="form-check-input flex-shrink-0" type="radio" name="growth_percentage" value="30" {{ $crop->growth_percentage > 10 && $crop->growth_percentage <= 30 ? 'checked' : '' }}>
                                        <span class="d-flex flex-column">
                                            <strong class="text-dark small">زيادة ملحوظة في حجم الأوراق</strong>
                                            <span class="very-small text-muted">بدء تغطية الأوراق لمساحة من التربة</span>
                                        </span>
                                    </label>

                                    <label class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 mb-2 shadow-sm" style="background-color: #f8fafc; border-radius: 8px !important;">
                                        <input class="form-check-input flex-shrink-0" type="radio" name="growth_percentage" value="50" {{ $crop->growth_percentage > 30 && $crop->growth_percentage <= 50 ? 'checked' : '' }}>
                                        <span class="d-flex flex-column">
                                            <strong class="text-dark small">نبات طويل وساق قوية</strong>
                                            <span class="very-small text-muted">زيادة طول الساق وبدء مرحلة النضج الخضري</span>
                                        </span>
                                    </label>

                                    <label class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 mb-2 shadow-sm" style="background-color: #f8fafc; border-radius: 8px !important;">
                                        <input class="form-check-input flex-shrink-0" type="radio" name="growth_percentage" value="75" {{ $crop->growth_percentage > 50 && $crop->growth_percentage <= 75 ? 'checked' : '' }}>
                                        <span class="d-flex flex-column">
                                            <strong class="text-dark small">بدء تفتح الأزهار</strong>
                                            <span class="very-small text-muted">ظهور البراعم الزهرية وتفتحها</span>
                                        </span>
                                    </label>

                                    <label class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 mb-2 shadow-sm" style="background-color: #f8fafc; border-radius: 8px !important;">
                                        <input class="form-check-input flex-shrink-0" type="radio" name="growth_percentage" value="95" {{ $crop->growth_percentage > 75 && $crop->growth_percentage <= 95 ? 'checked' : '' }}>
                                        <span class="d-flex flex-column">
                                            <strong class="text-dark small">ظهور الثمار وبدء النضج</strong>
                                            <span class="very-small text-muted">المحصول في طريقه للاكتمال</span>
                                        </span>
                                    </label>

                                    <label class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 shadow-sm" style="background-color: #f0fdf4; border-radius: 8px !important; border: 1px solid #22c55e !important;">
                                        <input class="form-check-input flex-shrink-0" type="radio" name="growth_percentage" value="100" {{ $crop->growth_percentage == 100 ? 'checked' : '' }}>
                                        <span class="d-flex flex-column">
                                            <strong class="text-success small">جاهز للحصاد النهائي</strong>
                                            <span class="very-small text-success">اكتمال نضج جميع الثمار</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="submit" class="btn btn-warning rounded-2 px-5 fw-bold w-100">تحديث الحالة</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Irrigation Modal -->
            <div class="modal fade" id="irrigationModal{{ $crop->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-3 text-end" style="direction: rtl;">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="fw-bold"><i class="bi bi-water text-info me-2"></i>تسجيل عملية ري: {{ $crop->name }}</h5>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" style="margin-right: auto !important;"></button>
                        </div>
                        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="water">
                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="title" value="عملية ري منفذة">
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6 text-start">
                                        <label class="form-label small fw-bold">كمية المياه (لتر)</label>
                                        <input type="number" name="water_amount" id="water_amount_{{ $crop->id }}" class="form-control rounded-1 px-3" placeholder="مثال: 50" required>
                                        <div class="mt-2 d-flex flex-wrap gap-1">
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('water_amount_{{ $crop->id }}').value=50">50 لتر</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('water_amount_{{ $crop->id }}').value=100">100 لتر</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('water_amount_{{ $crop->id }}').value=500">500 لتر</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-start">
                                        <label class="form-label small fw-bold">المدة (دقيقة)</label>
                                        <input type="number" name="duration_minutes" id="duration_{{ $crop->id }}" class="form-control rounded-1 px-3" placeholder="مثال: 30" required>
                                        <div class="mt-2 d-flex flex-wrap gap-1">
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('duration_{{ $crop->id }}').value=15">15 د</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('duration_{{ $crop->id }}').value=30">30 د</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('duration_{{ $crop->id }}').value=60">60 د</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold">التاريخ والوقت</label>
                                        <input type="datetime-local" name="due_date" class="form-control rounded-1 px-3" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-bold">ملاحظات النظام</label>
                                        <textarea name="system_notes" class="form-control rounded-2 px-3" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="submit" class="btn btn-info text-white rounded-2 px-5 fw-bold w-100">حفظ عملية الري</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Treatment Modal -->
            <div class="modal fade" id="treatmentModal{{ $crop->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-3 text-end" style="direction: rtl;">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="fw-bold"><i class="bi bi-shield-plus text-danger me-2"></i>تسجيل معالجة: {{ $crop->name }}</h5>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" style="margin-right: auto !important;"></button>
                        </div>
                        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="fertilizer">
                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="title" value="عملية معالجة منفذة">
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold">اسم المادة</label>
                                        <input type="text" name="material_name" class="form-control rounded-1 px-3" placeholder="مثال: سماد عضوي" required>
                                    </div>
                                    <div class="col-md-6 text-start">
                                        <label class="form-label small fw-bold">الجرعة</label>
                                        <input type="number" step="0.1" name="dosage" id="dosage_{{ $crop->id }}" class="form-control rounded-1 px-3" placeholder="مثال: 5" required>
                                        <div class="mt-2 d-flex flex-wrap gap-1">
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('dosage_{{ $crop->id }}').value=5">5</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('dosage_{{ $crop->id }}').value=10">10</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('dosage_{{ $crop->id }}').value=20">20</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-start">
                                        <label class="form-label small fw-bold">الوحدة</label>
                                        <select name="dosage_unit" class="form-select rounded-1 px-3">
                                            <option value="لتر/فدان">لتر/فدان</option>
                                            <option value="كجم/فدان">كجم/فدان</option>
                                            <option value="مل/لتر">مل/لتر</option>
                                            <option value="جرام/لتر">جرام/لتر</option>
                                        </select>
                                    </div>
                                    <div class="col-12 text-start">
                                        <label class="form-label small fw-bold">التاريخ والوقت</label>
                                        <input type="datetime-local" name="due_date" class="form-control rounded-1 px-3" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="submit" class="btn btn-danger rounded-2 px-5 fw-bold w-100">حفظ المعالجة</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Harvest Modal -->
            <div class="modal fade" id="harvestModal{{ $crop->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-3 text-end" style="direction: rtl;">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="fw-bold"><i class="bi bi-archive text-success me-2"></i>تسجيل حصاد: {{ $crop->name }}</h5>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" style="margin-right: auto !important;"></button>
                        </div>
                        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="harvest">
                            <input type="hidden" name="status" value="completed">
                            <input type="hidden" name="title" value="عملية حصاد منفذة">
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6 text-start">
                                        <label class="form-label small fw-bold">الكمية</label>
                                        <input type="number" step="0.1" name="harvest_quantity" id="h_qty_{{ $crop->id }}" class="form-control rounded-1 px-3" placeholder="مثال: 100" required>
                                        <div class="mt-2 d-flex flex-wrap gap-1">
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('h_qty_{{ $crop->id }}').value=100">100</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('h_qty_{{ $crop->id }}').value=500">500</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm very-small py-0 px-2 rounded-pill" onclick="document.getElementById('h_qty_{{ $crop->id }}').value=1000">1000</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-start">
                                        <label class="form-label small fw-bold">الوحدة</label>
                                        <select name="harvest_unit" class="form-select rounded-1 px-3">
                                            <option value="كجم">كجم</option>
                                            <option value="طن">طن</option>
                                            <option value="صندوق">صندوق</option>
                                        </select>
                                    </div>
                                    <div class="col-12 text-start">
                                        <label class="form-label small fw-bold">التاريخ والوقت</label>
                                        <input type="datetime-local" name="due_date" class="form-control rounded-1 px-3" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="submit" class="btn btn-success rounded-2 px-5 fw-bold w-100">حفظ الحصاد</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal for adding task directly -->
            <div class="modal fade" id="addTaskModal{{ $crop->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-3 text-end" style="direction: rtl;">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="fw-bold">إضافة مهمة للمحصول: {{ $crop->name }}</h5>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" style="margin-right: auto !important;"></button>
                        </div>
                        <form action="{{ route('crops.tasks.store', $crop) }}" method="POST">
                            @csrf
                            <input type="hidden" name="crop_id" value="{{ $crop->id }}">
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">وصف المهمة</label>
                                    <input type="text" name="title" class="form-control rounded-1 px-3" placeholder="مثال: ري الجزء الجنوبي" required>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">النوع</label>
                                        <select name="type" class="form-select rounded-1 px-3">
                                            <option value="water">ري</option>
                                            <option value="fertilizer">تسميد</option>
                                            <option value="pest">مكافحة</option>
                                            <option value="harvest">حصاد</option>
                                            <option value="other">أخرى</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">التاريخ</label>
                                        <input type="datetime-local" name="due_date" class="form-control rounded-1 px-3" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="submit" class="btn btn-success rounded-2 px-5 fw-bold w-100">حفظ المهمة</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i class="bi bi-sprout display-1 text-muted opacity-25 mb-3"></i>
                    <h3 class="h5 text-muted">لا توجد محاصيل حالياً</h3>
                    <p class="text-muted small mb-4">ابدأ بإضافة أول محصول لمزرعتك وتابع نموه.</p>
                    <a href="{{ route('crops.create') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-plus-lg me-1"></i> أضف محصول جديد
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $crops->links() }}
    </div>

    <style>
        .transition-all { transition: all 0.3s ease; }
        .very-small { font-size: 0.65rem; }
        .header-carousel-icon { filter: invert(1); }
    </style>
</x-app-layout>
