<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold text-dark mb-0">
                لوحة تحكم الخبير (Expert Command Center)
            </h2>
            <div class="text-muted small">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <!-- Stats Row -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white shadow-hover transition">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-patch-question-fill text-warning fs-3"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0 text-dark">{{ $pendingCount }}</h3>
                            <p class="text-muted small mb-0">استشارات بانتظار الرد</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-chat-heart-fill text-success fs-3"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0 text-dark">{{ $answeredCount }}</h3>
                            <p class="text-muted small mb-0">نصائح قدمتها</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-star-fill text-info fs-3"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0 text-dark">4.9</h3>
                            <p class="text-muted small mb-0">تقييم المزارعين لك</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Pending Requests -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">أحدث طلبات الاستشارة</h5>
                        <a href="{{ route('expert.consultations.index') }}" class="btn btn-sm btn-outline-success rounded-pill">عرض الكل</a>
                    </div>
                    <div class="card-body p-0">
                        @if($recentConsultations->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-check2-circle text-success display-3 mb-3"></i>
                                <h6 class="text-muted">لا توجد طلبات معلقة حالياً</h6>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($recentConsultations as $consultation)
                                    <div class="list-group-item p-4 border-start border-warning border-3 mb-1">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="badge bg-light text-dark">{{ $consultation->category }}</span>
                                            <small class="text-muted">{{ $consultation->created_at->diffForHumans() }}</small>
                                        </div>
                                        <h6 class="fw-bold text-dark">{{ $consultation->subject }}</h6>
                                        <p class="text-muted small mb-3">{{ Str::limit($consultation->question, 100) }}</p>
                                        <a href="{{ route('consultations.show', $consultation) }}" class="btn btn-sm btn-success rounded-pill px-3">تقديم نصيحة</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Expert Profile Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-center bg-success text-white">
                    <div class="mb-3">
                        <div class="bg-white bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-badge-fill text-white fs-1"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
                    <p class="small mb-3 opacity-75">خبير زراعي معتمد</p>
                    <hr class="bg-white opacity-25">
                    <div class="row text-center mt-3">
                        <div class="col-6 border-end border-white border-opacity-25">
                            <h6 class="fw-bold mb-0">12</h6>
                            <small class="opacity-75">سنة خبرة</small>
                        </div>
                        <div class="col-6">
                            <h6 class="fw-bold mb-0">85</h6>
                            <small class="opacity-75">مزارع مستفيد</small>
                        </div>
                    </div>
                </div>

                <!-- Guidance Tips -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-lightbulb text-warning me-1"></i> نصائح للخبراء</h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2"><i class="bi bi-check2 text-success me-1"></i> كن سريعاً في الرد لبناء الثقة.</li>
                        <li class="mb-2"><i class="bi bi-check2 text-success me-1"></i> استخدم لغة بسيطة ومفهومة للمزارع.</li>
                        <li><i class="bi bi-check2 text-success me-1"></i> اطلب المزيد من الصور إذا كان التشخيص صعباً.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
