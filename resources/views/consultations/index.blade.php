<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold text-dark mb-0">
                <i class="bi bi-chat-dots-fill text-success"></i> الاستشارات الزراعية (Consultations)
            </h2>
            <a href="{{ route('consultations.create') }}" class="btn btn-success rounded-pill px-4">
                <i class="bi bi-plus-lg"></i> اطلب استشارة جديدة
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        @if($consultations->isEmpty())
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-chat-left-text text-muted fs-1"></i>
                    </div>
                    <h5 class="text-dark fw-bold">لم تطلب أي استشارة بعد</h5>
                    <p class="text-muted">يمكنك التواصل مع خبرائنا للحصول على نصائح حول محاصيلك.</p>
                    <a href="{{ route('consultations.create') }}" class="btn btn-outline-success mt-2">اطلب أول استشارة</a>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($consultations as $consultation)
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge {{ $consultation->status == 'answered' ? 'bg-success' : 'bg-warning' }} rounded-pill px-3">
                                        {{ $consultation->status == 'answered' ? 'تم الرد' : 'بانتظار خبير' }}
                                    </span>
                                    <small class="text-muted">{{ $consultation->created_at->format('Y-m-d') }}</small>
                                </div>
                                <h5 class="fw-bold mb-2">{{ $consultation->subject }}</h5>
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-tag-fill me-1"></i> {{ $consultation->category }}
                                    @if($consultation->crop)
                                        <span class="ms-2"><i class="bi bi-flower1 me-1"></i> {{ $consultation->crop->name }}</span>
                                    @endif
                                </p>
                                <p class="text-secondary line-clamp-2">{{ $consultation->question }}</p>
                            </div>
                            <div class="card-footer bg-white border-top-0 pb-4 px-4">
                                <a href="{{ route('consultations.show', $consultation) }}" class="btn btn-light w-100 rounded-pill fw-bold">
                                    عرض التفاصيل {{ $consultation->status == 'answered' ? 'والرد' : '' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
