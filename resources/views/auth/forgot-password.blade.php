<x-guest-layout :fullWidth="true">
    <div class="row g-0 min-vh-100 overflow-hidden">
        
        <!-- Left Section: Serene Visual (Hidden on mobile) -->
        <div class="col-lg-6 d-none d-lg-block position-relative">
            <img src="https://images.unsplash.com/photo-1516192518150-0d8fee5425e3?auto=format&fit=crop&q=80&w=2000" 
                 class="w-100 h-100 object-fit-cover" 
                 alt="Reefy Security">
            
            <!-- Dynamic Gradient Overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" 
                 style="background: linear-gradient(220deg, rgba(27, 67, 50, 0.45) 0%, rgba(45, 106, 79, 0.8) 100%);"></div>

            <!-- Glassmorphic Message -->
            <div class="position-absolute bottom-0 start-0 w-100 p-5 p-xl-6 text-white text-end">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-white bg-opacity-20 backdrop-blur border border-white border-opacity-30 rounded-pill mb-4 small fw-semibold shadow-sm">
                    <span class="bi bi-shield-check text-mint"></span>
                    <span>حماية حسابك أولوية</span>
                </div>
                
                <h2 class="display-6 fw-bold mb-4 lh-base" style="font-family: 'Cairo', sans-serif;">
                    "لا تقلق، استعادة كلمة المرور سهلة وآمنة. سنرسل لك رابطاً فورياً."
                </h2>
            </div>
        </div>

        <!-- Right Section: Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white p-4 p-md-5">
            <div class="w-100" style="max-width: 440px;">
                
                <!-- Brand Mobile -->
                <div class="d-flex justify-content-center mb-5 d-lg-none">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-2 fw-bold text-dark">ريفي</span>
                        <div class="bg-success text-white rounded-3 p-1 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #2d6a4f, #1b4332) !important;">
                            <i class="bi bi-layers-half fs-4"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-5 text-end">
                    <h1 class="fw-bold mb-3 h2" style="color: var(--reefy-primary); font-family: 'Cairo', sans-serif;">نسيت كلمة المرور؟</h1>
                    <p class="text-muted">لا مشكلة. أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور فوراً.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div class="mb-5">
                        <label for="email" class="form-label fw-semibold mb-2 text-end d-block" style="color: #495057;">البريد الإلكتروني</label>
                        <div class="position-relative">
                            <input type="email" 
                                   class="form-control form-control-lg text-end border-0 ps-4 pe-5" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   placeholder="name@example.com"
                                   style="background: #f1f8f5; border-radius: 12px; height: 56px; font-size: 0.95rem; border: 1.5px solid transparent !important; transition: all 0.2s ease;">
                            <i class="bi bi-envelope position-absolute top-50 translate-middle-y end-0 me-3 text-muted"></i>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small text-end" />
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm border-0 mb-4" 
                            style="height: 56px; font-size: 1.1rem; border-radius: 14px !important;">
                        إرسال رابط إعادة التعيين
                    </button>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none small fw-bold" style="color: var(--reefy-success);">
                            <i class="bi bi-arrow-right me-2"></i> العودة لتسجيل الدخول
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Micro-interactions Style -->
    <style>
        input:focus {
            background: #ffffff !important;
            border-color: #2d6a4f !important;
            box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.1) !important;
            outline: none;
        }
        .backdrop-blur { backdrop-filter: blur(10px); }
        .text-mint { color: #8ef9d5; }
    </style>
</x-guest-layout>
