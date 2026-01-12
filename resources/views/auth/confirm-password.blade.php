<x-guest-layout>
    <div class="text-center mb-5">
        <div class="d-inline-flex align-items-center justify-content-center rounded-0 mb-4" 
             style="width: 80px; height: 80px; background: var(--bg-secondary); border: 1px solid var(--border-color);">
            <i class="bi bi-shield-lock fs-1" style="color: var(--reefy-success);"></i>
        </div>
        <h2 class="fw-bold mb-3" style="color: var(--heading-color); font-family: 'Cairo', sans-serif;">منطقة آمنة</h2>
        <p class="text-muted px-4" style="color: var(--text-secondary) !important;">
            هذه منطقة آمنة من التطبيق. يرجى تأكيد كلمة المرور الخاصة بك قبل المتابعة.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        
        <!-- Password -->
        <div class="mb-5">
            <label for="password" class="form-label fw-semibold mb-2 text-end d-block" style="color: var(--text-secondary);">كلمة المرور</label>
            <div class="position-relative">
                <input type="password" 
                       class="form-control form-control-lg text-end border-0 ps-4 pe-5 theme-input" 
                       id="password" 
                       name="password" 
                       required 
                       autocomplete="current-password" 
                       placeholder="••••••••"
                       style="background: var(--bg-primary); color: var(--text-primary); border-radius: 0px !important; height: 56px; font-size: 0.95rem; border: 1.5px solid var(--border-color) !important; transition: all 0.2s ease;">
                <i class="bi bi-shield-lock position-absolute top-50 translate-middle-y end-0 me-3 text-muted"></i>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small text-end" />
        </div>

        <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-0 shadow-sm border-0" 
                style="height: 56px; font-size: 1.1rem; border-radius: 0px !important; background: var(--reefy-primary) !important; color: white !important;">
            تأكيد المتابعة
        </button>
    </form>

    <!-- Micro-interactions Style -->
    <style>
        input:focus {
            background: var(--bg-secondary) !important;
            border-color: var(--reefy-success) !important;
            box-shadow: 0 0 0 4px rgba(132, 204, 22, 0.1) !important;
            outline: none;
            color: var(--text-primary) !important;
        }
    </style>
</x-guest-layout>
