<x-guest-layout>
    <div class="text-center mb-5">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" 
             style="width: 80px; height: 80px; background: linear-gradient(135deg, #f1f8f5, #e2eee8);">
            <i class="bi bi-shield-lock fs-1" style="color: var(--reefy-success);"></i>
        </div>
        <h2 class="fw-bold mb-3" style="color: var(--reefy-primary); font-family: 'Cairo', sans-serif;">منطقة آمنة</h2>
        <p class="text-muted px-4">
            هذه منطقة آمنة من التطبيق. يرجى تأكيد كلمة المرور الخاصة بك قبل المتابعة.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        
        <!-- Password -->
        <div class="mb-5">
            <label for="password" class="form-label fw-semibold mb-2 text-end d-block" style="color: #495057;">كلمة المرور</label>
            <div class="position-relative">
                <input type="password" 
                       class="form-control form-control-lg text-end border-0 ps-4 pe-5" 
                       id="password" 
                       name="password" 
                       required 
                       autocomplete="current-password" 
                       placeholder="••••••••"
                       style="background: #f1f8f5; border-radius: 12px; height: 56px; font-size: 0.95rem; border: 1.5px solid transparent !important; transition: all 0.2s ease;">
                <i class="bi bi-shield-lock position-absolute top-50 translate-middle-y end-0 me-3 text-muted"></i>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small text-end" />
        </div>

        <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm border-0" 
                style="height: 56px; font-size: 1.1rem; border-radius: 14px !important;">
            تأكيد المتابعة
        </button>
    </form>

    <!-- Micro-interactions Style -->
    <style>
        input:focus {
            background: #ffffff !important;
            border-color: #2d6a4f !important;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1) !important;
            outline: none;
        }
    </style>
</x-guest-layout>
