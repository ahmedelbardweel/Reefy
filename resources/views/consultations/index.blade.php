<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0" style="color: var(--heading-color);">
                <i class="bi bi-chat-dots-fill text-success"></i> الاستشارات الزراعية (Consultations)
            </h2>
            <a href="{{ route('consultations.create') }}" class="btn btn-success rounded-0 px-4">
                <i class="bi bi-plus-lg"></i> اطلب استشارة جديدة
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        @if($consultations->isEmpty())
            <div class="card border-0 shadow-sm rounded-0" style="background-color: var(--bg-secondary);">
                <div class="card-body text-center py-5">
                    <div class="rounded-0 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background-color: var(--bg-primary);">
                        <i class="bi bi-chat-left-text fs-1" style="color: var(--text-secondary);"></i>
                    </div>
                    <h5 class="fw-bold" style="color: var(--heading-color);">لم تطلب أي استشارة بعد</h5>
                    <p style="color: var(--text-secondary);">يمكنك التواصل مع خبرائنا للحصول على نصائح حول محاصيلك.</p>
                    <a href="{{ route('consultations.create') }}" class="btn btn-outline-success mt-2 rounded-0">اطلب أول استشارة</a>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($consultations as $consultation)
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm rounded-0 overflow-hidden h-100" style="background-color: var(--bg-secondary);">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge {{ $consultation->status == 'answered' ? 'bg-success' : 'bg-warning' }} rounded-0 px-3">
                                        {{ $consultation->status == 'answered' ? 'تم الرد' : 'بانتظار خبير' }}
                                    </span>
                                    <small style="color: var(--text-secondary);">{{ $consultation->created_at->format('Y-m-d') }}</small>
                                </div>
                                <h5 class="fw-bold mb-2" style="color: var(--heading-color);">{{ $consultation->subject }}</h5>
                                <p class="small mb-3" style="color: var(--text-secondary);">
                                    <i class="bi bi-tag-fill me-1"></i> {{ $consultation->category }}
                                    @if($consultation->crop)
                                        <span class="ms-2"><i class="bi bi-flower1 me-1"></i> {{ $consultation->crop->name }}</span>
                                    @endif
                                </p>
                                <p class="line-clamp-2" style="color: var(--text-primary);">{{ $consultation->question }}</p>
                            </div>
                            <div class="card-footer border-top-0 pb-4 px-4" style="background-color: var(--bg-secondary);">
                                <a href="{{ route('consultations.show', $consultation) }}" class="btn w-100 rounded-0 fw-bold" style="background-color: var(--bg-primary); color: var(--text-primary);">
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
