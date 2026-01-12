<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center" style="direction: rtl;">
            <div>
                <h1 class="h4 fw-bold mb-1" style="color: var(--heading-color); letter-spacing: -0.01em;">
                    مركز قيادة الخبير (Expert Control Center)
                </h1>
                <p class="text-muted small mb-0">مرحباً بك مجدداً يا د. {{ Auth::user()->name }}، إليك ملخص نشاطك اليوم.</p>
            </div>
            <div class="d-none d-lg-flex bg-white px-3 py-2 border rounded-0 align-items-center gap-2" style="border-color: var(--border-color) !important; background: var(--bg-secondary) !important;">
                <i class="bi bi-calendar3 text-success"></i>
                <span class="small fw-bold text-dark" style="color: var(--heading-color) !important;">{{ now()->translatedFormat('l, d M Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-4 px-1" style="background-color: var(--bg-primary);">
        <!-- KPI Pillars - Expert Edition -->
        <div class="row g-3 mb-4 text-end" style="direction: rtl;">
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 0px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted fw-bold" style="font-size: 0.75rem;">استشارات معلقة</span>
                        <i class="bi bi-patch-question text-warning opacity-50 f-5"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #f59e0b;">{{ $pendingCount }}</h3>
                    <div class="very-small text-warning fw-bold">تحتاج إلى رد عاجل</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 0px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted fw-bold" style="font-size: 0.75rem;">تقديم نصائح</span>
                        <i class="bi bi-chat-heart text-success opacity-50 f-5"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: var(--reefy-primary);">{{ $answeredCount }}</h3>
                    <div class="very-small text-success fw-bold">إجمالي المساهمات</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 0px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted fw-bold" style="font-size: 0.75rem;">تقييم الأداء</span>
                        <i class="bi bi-star-fill text-info opacity-50 f-5"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #0369a1;">4.9</h3>
                    <div class="very-small text-info fw-bold">بناءً على رأي المزارعين</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border shadow-none h-100 p-3" style="background-color: var(--bg-secondary); border-color: var(--border-color) !important; border-radius: 0px !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted fw-bold" style="font-size: 0.75rem;">مزارعين مستفيدين</span>
                        <i class="bi bi-people text-secondary opacity-50 f-5"></i>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #334155;">85</h3>
                    <div class="very-small text-muted fw-bold">خلال الشهر الأخير</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Consultations List - Full Width -->
            <div class="col-12" style="direction: rtl;">
                <div class="card border-0 shadow-none" style="border-radius: 0px; border: 1px solid var(--border-color) !important;">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center" style="background: var(--bg-secondary) !important;">
                        <div>
                            <h5 class="fw-bold mb-1 fs-6" style="color: var(--heading-color) !important;">أحدث طلبات الاستشارة مجهولة الرد</h5>
                            <p class="very-small text-muted mb-0" style="color: var(--text-secondary) !important;">قم بمساعدة المزارعين من خلال خبراتك الميدانية</p>
                        </div>
                        <a href="{{ route('expert.consultations.index') }}" class="btn btn-sm btn-outline-success rounded-0 px-3 fw-bold" style="font-size: 0.75rem;">عرض الكل</a>
                    </div>
                    <div class="card-body p-4">
                        @forelse($recentConsultations as $consultation)
                            <div class="p-3 mb-3 border rounded-0 bg-white transition-all shadow-hover-light" style="border-color: #f1f5f9 !important;">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-0 bg-light p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-chat-dots text-success"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small" style="color: var(--heading-color) !important;">{{ $consultation->subject }}</div>
                                            <div class="very-small text-muted" style="color: var(--text-secondary) !important;">{{ $consultation->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <span class="badge rounded-0 bg-light text-dark px-3 py-2 very-small fw-bold border" style="border-color: #e2eee8 !important;">{{ $consultation->category }}</span>
                                </div>
                                <p class="text-muted small mb-3 ls-tight" style="line-height: 1.6;">{{ Str::limit($consultation->question, 140) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: #f8fafc !important;">
                                    <div class="very-small fw-bold text-muted">
                                        <i class="bi bi-person me-1"></i> مزارع ريفي
                                    </div>
                                    <a href="{{ route('consultations.show', $consultation) }}" class="btn btn-success btn-sm rounded-0 px-4 fw-bold">
                                        <i class="bi bi-reply-all me-1"></i> تقديم نصيحة
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="bg-light rounded-0 d-inline-flex p-4 mb-3">
                                    <i class="bi bi-check2-all text-success display-4"></i>
                                </div>
                                <h6 class="fw-bold text-dark">عمل رائع! لا توجد طلبات معلقة</h6>
                                <p class="text-muted small">قمت بالرد على كافة الاستشارات المتاحة حتى الآن.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Expert Tips Management -->
                <div class="card border-0 shadow-none mt-4" style="border-radius: 0px; border: 1px solid #e2eee8 !important;">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-dark mb-1 fs-6">إدارة النصائح العامة</h5>
                            <p class="very-small text-muted mb-0">انشر نصائح تظهر لجميع المزارعين في لوحة تحكمهم</p>
                        </div>
                        <button class="btn btn-sm btn-success rounded-0 px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addTipModal" style="font-size: 0.75rem;">
                            <i class="bi bi-plus-lg me-1"></i> إضافة نصيحة
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @forelse($myTips as $tip)
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-0 bg-white transition-all shadow-hover-light position-relative" style="border-color: #f1f5f9 !important;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold text-dark fs-6 mb-0">{{ $tip->title }}</h6>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-0 p-2">
                                                    <li>
                                                        <button class="dropdown-item rounded-0 py-2 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#editTipModal{{ $tip->id }}">
                                                            <i class="bi bi-pencil text-primary"></i> <span class="small">تعديل</span>
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('expert.tips.destroy', $tip) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه النصيحة؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger rounded-0 py-2 d-flex align-items-center gap-2">
                                                                <i class="bi bi-trash"></i> <span class="small">حذف</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-0 ls-tight" style="line-height: 1.5; height: 3rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $tip->content }}</p>
                                        <div class="very-small text-muted mt-2 border-top pt-2">
                                            <i class="bi bi-clock me-1"></i> {{ $tip->created_at->format('Y-m-d') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Tip Modal -->
                                <div class="modal fade" id="editTipModal{{ $tip->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg rounded-0">
                                            <div class="modal-header border-0 pb-0 px-4 pt-4">
                                                <h5 class="fw-bold text-dark fs-5">تعديل النصيحة</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('expert.tips.update', $tip) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-muted">عنوان النصيحة</label>
                                                        <input type="text" name="title" class="form-control rounded-0 border-light bg-light" value="{{ $tip->title }}" required>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label small fw-bold text-muted">محتوى النصيحة</label>
                                                        <textarea name="content" rows="4" class="form-control rounded-0 border-light bg-light" required>{{ $tip->content }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 p-4 pt-0">
                                                    <button type="submit" class="btn btn-success w-100 rounded-0 py-2 fw-bold">حفظ التعديلات</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 w-100">
                                    <p class="text-muted small mb-0">لم تقم بإضافة أي نصائح عامة بعد.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for Adding New Tip -->
            <div class="modal fade" id="addTipModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-0">
                        <div class="modal-header border-0 pb-0 px-4 pt-4">
                            <h5 class="fw-bold text-dark fs-5">إضافة نصيحة جديدة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('expert.tips.store') }}" method="POST">
                            @csrf
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">عنوان النصيحة</label>
                                    <input type="text" name="title" class="form-control rounded-0 border-light bg-light" placeholder="مثلاً: أفضل وقت للري في الصيف" required>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small fw-bold text-muted">محتوى النصيحة</label>
                                    <textarea name="content" rows="4" class="form-control rounded-0 border-light bg-light" placeholder="اكتب نصيحتك العلمية هنا..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-4 pt-0">
                                <button type="submit" class="btn btn-success w-100 rounded-0 py-2 fw-bold">نشر النصيحة</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </div>

    <style>
        .very-small { font-size: 0.7rem; }
        .ls-tight { letter-spacing: -0.01em; }
        .shadow-hover-light:hover { 
            box-shadow: 0 10px 25px rgba(0,0,0,0.03) !important;
            transform: translateY(-2px);
            border-color: #e2eee8 !important;
        }
        .transition-all { transition: all 0.3s ease; }
    </style>
</x-app-layout>
