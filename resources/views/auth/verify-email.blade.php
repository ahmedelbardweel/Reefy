<x-guest-layout>
    <div class="text-center mb-5">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" 
             style="width: 80px; height: 80px; background: linear-gradient(135deg, #f1f8f5, #e2eee8);">
            <i class="bi bi-envelope-check fs-1" style="color: var(--reefy-success);"></i>
        </div>
        <h2 class="fw-bold mb-3" style="color: var(--reefy-primary); font-family: 'Cairo', sans-serif;">تأكيد البريد الإلكتروني</h2>
        <p class="text-muted px-4">
            شكراً لتسجيلك! قبل البدء، يرجى تأكيد بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه لك. إذا لم تستلم الرسالة، سنرسل لك واحدة جديدة بكل سرور.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success text-end mb-4" role="alert" style="background: #f1f8f5; border: 1.5px solid #2d6a4f; border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i>
            تم إرسال رابط تأكيد جديد إلى بريدك الإلكتروني.
        </div>
    @endif

    <div class="d-flex flex-column gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm border-0" 
                    style="height: 56px; font-size: 1rem; border-radius: 14px !important;">
                <i class="bi bi-send me-2"></i> إعادة إرسال رسالة التأكيد
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-light w-100 py-2 fw-semibold text-muted border-0" 
                    style="background: #f8f9fa; border-radius: 10px;">
                <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
            </button>
        </form>
    </div>
</x-guest-layout>
