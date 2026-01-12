<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0" style="color: var(--heading-color);">
            تفاصيل الاستشارة
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Question Section -->
                <div class="card border-0 shadow-sm rounded-0 mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between">
                        <span class="badge {{ $consultation->status == 'answered' ? 'bg-success' : 'bg-warning' }} rounded-0 px-3">
                            {{ $consultation->status == 'answered' ? 'تم الرد' : 'بانتظار خبير' }}
                        </span>
                <!-- Question Section -->
                <div class="card border-0 shadow-sm rounded-0 mb-4" style="background-color: var(--bg-secondary);">
                    <div class="card-header border-bottom-0 pt-4 px-4 d-flex justify-content-between" style="background-color: var(--bg-secondary);">
                    <div class="card-body px-4 pb-4">
                        <h3 class="fw-bold mb-3" style="color: var(--reefy-success);">{{ $consultation->subject }}</h3>
                        <div class="p-3 rounded-0 mb-3" style="background-color: var(--bg-primary);">
                            <p class="mb-0" style="color: var(--text-primary); white-space: pre-wrap;">{{ $consultation->question }}</p>
                        </div>
                        <div class="d-flex gap-3 small" style="color: var(--text-secondary);">
                            <span><i class="bi bi-tag-fill me-1"></i> {{ $consultation->category }}</span>
                            @if($consultation->crop)
                                <span><i class="bi bi-flower1 me-1"></i> {{ $consultation->crop->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Response Section -->
                @if($consultation->status == 'answered')
                    <div class="card border-0 shadow rounded-0 overflow-hidden border-start border-success border-4" style="background-color: var(--bg-secondary);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success rounded-0 p-2 me-3">
                                    <i class="bi bi-person-badge text-white fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold" style="color: var(--heading-color);">رد الخبير: {{ $consultation->expert->name }}</h6>
                                    <small style="color: var(--text-secondary);">{{ $consultation->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <div class="p-3 rounded-0 border border-success border-opacity-10" style="background-color: rgba(20, 164, 77, 0.1);">
                                <p class="mb-0" style="color: var(--text-primary); white-space: pre-wrap; font-size: 1.1rem;">{{ $consultation->response }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    @if(auth()->user()->role === 'expert')
                        <div class="card border-0 shadow-sm rounded-0 p-4" style="background-color: var(--bg-secondary);">
                            <h5 class="fw-bold mb-3" style="color: var(--heading-color);">إعطاء نصيحة الخبير (Expert Advice)</h5>
                            <form action="{{ route('consultations.answer', $consultation) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="response" class="form-control" rows="5" placeholder="اكتب ردك هنا بمصداقية وعلم..." style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color);" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100 rounded-0 fw-bold">
                                    إرسال الرد للمزارع
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert border-0 shadow-sm rounded-0 text-center" style="background-color: rgba(255, 193, 7, 0.1); color: #856404;">
                            <i class="bi bi-hourglass-split fs-3 d-block mb-2"></i>
                            طلبك قيد المراجعة من قبل خبرائنا. سيتم إشعارك فور الرد.
                        </div>
                    @endif
                @endif

                <div class="text-center mt-4">
                    <a href="{{ auth()->user()->role === 'expert' ? route('expert.consultations.index') : route('consultations.index') }}" class="btn btn-link text-decoration-none" style="color: var(--text-secondary);">
                        <i class="bi bi-arrow-right"></i> العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
