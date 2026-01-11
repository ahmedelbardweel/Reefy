<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            <i class="bi bi-mortarboard text-success"></i> لوحة الخبراء: طلبات الاستشارة (Expert Feed)
        </h2>
    </x-slot>

    <div class="py-4">
        @if($consultations->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <i class="bi bi-check-circle text-success display-1 mb-3"></i>
                <h5 class="fw-bold">رائع! لا توجد استشارات متأخرة</h5>
                <p class="text-muted">لقد تم الرد على كافة الطلبات المتاحة حالياً.</p>
            </div>
        @else
            <div class="row">
                @foreach($consultations as $consultation)
                    <div class="col-12 mb-3">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-9 border-end">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-2 text-muted small">
                                            <i class="bi bi-person-circle me-1"></i> مزارع: {{ $consultation->user->name }}
                                            <span class="mx-2">|</span>
                                            <i class="bi bi-clock me-1"></i> {{ $consultation->created_at->diffForHumans() }}
                                            <span class="mx-2">|</span>
                                            <span class="badge bg-light text-dark">{{ $consultation->category }}</span>
                                        </div>
                                        <h5 class="fw-bold mb-3 text-dark">{{ $consultation->subject }}</h5>
                                        <p class="text-muted mb-0">{{ Str::limit($consultation->question, 150) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3 bg-light d-flex align-items-center justify-content-center p-4">
                                    <div class="text-center w-100">
                                        @if($consultation->crop)
                                            <div class="small text-muted mb-2">المحصول: {{ $consultation->crop->name }}</div>
                                        @endif
                                        <a href="{{ route('consultations.show', $consultation) }}" class="btn btn-success rounded-pill px-4 fw-bold w-100">
                                            تقديم النصيحة
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
