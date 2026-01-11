<x-guest-layout :fullWidth="true">
    <div class="row g-0 min-vh-100 overflow-hidden">
        
        <!-- Left Section: Serene Visual (Hidden on mobile) -->
        <div class="col-lg-6 d-none d-lg-block position-relative">
            <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&q=80&w=2000" 
                 class="w-100 h-100 object-fit-cover" 
                 alt="Reefy Farming">
            
            <!-- Dynamic Gradient Overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" 
                 style="background: linear-gradient(220deg, rgba(27, 67, 50, 0.45) 0%, rgba(45, 106, 79, 0.8) 100%);"></div>

            <!-- Glassmorphic Testimonial -->
            <div class="position-absolute bottom-0 start-0 w-100 p-5 p-xl-6 text-white text-end">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-white bg-opacity-20 backdrop-blur border border-white border-opacity-30 rounded-pill mb-4 small fw-semibold shadow-sm">
                    <span class="bi bi-patch-check-fill text-mint"></span>
                    <span>ثقة المزارعين في ريفي</span>
                </div>
                
                <h2 class="display-6 fw-bold mb-4 lh-base" style="font-family: 'Cairo', sans-serif;">
                    "ريفي ليس مجرد تطبيق، بل هو شريك في كل حبة نزرعها، جعل الزراعة أكثر هدوءاً وذكاءً."
                </h2>
                
                <div class="d-flex align-items-center gap-3 justify-content-end mt-4">
                    <div class="text-end">
                        <div class="fw-bold fs-5">د. عبد الرحمن خالد</div>
                        <div class="small opacity-75">مزارع وخبير إنتاج عضوي</div>
                    </div>
                    <div class="rounded-circle border border-2 border-white border-opacity-50 overflow-hidden shadow-lg" style="width: 56px; height: 56px;">
                        <img src="https://ui-avatars.com/api/?name=Abdelrahman+Khaled&background=2d6a4f&color=fff" class="w-100 h-100" alt="Avatar">
                    </div>
                </div>
            </div>
            
            <!-- Abstract Shapes for Premium Feel -->
            <div class="position-absolute top-0 end-0 p-5">
                <div class="rounded-circle bg-white bg-opacity-10 backdrop-blur" style="width: 150px; height: 150px;"></div>
            </div>
        </div>

        <!-- Right Section: Login Form -->
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
                    <h1 class="fw-bold mb-3 h2" style="color: var(--reefy-primary); font-family: 'Cairo', sans-serif;">عودة حميدة</h1>
                    <p class="text-muted">سجل دخولك الآن لمتابعة نمو محاصيلك وإدارة أنظمتك الذكية بهدوء.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="needs-validation">
                    @csrf
                    
                    <!-- Email -->
                    <div class="mb-4">
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

                    <!-- Password -->
                    <div class="mb-2">
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

                    <div class="d-flex justify-content-between align-items-center mb-5 mt-3">
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none small fw-bold" href="{{ route('password.request') }}" style="color: var(--reefy-success);">
                                هل نسيت كلمة المرور؟
                            </a>
                        @endif
                        <div class="form-check d-flex align-items-center gap-2">
                            <label class="form-check-label small text-muted cursor-pointer" for="remember_me">تذكرني على هذا الجهاز</label>
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="remember" id="remember_me" style="width: 18px; height: 18px;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm border-0 mb-4" 
                            style="height: 56px; font-size: 1.1rem; border-radius: 14px !important;">
                        تسجيل الدخول
                    </button>
                    
                    <!-- Alternative Sign In -->
                    <div class="text-center mb-4">
                        <span class="small text-muted fw-semibold">أو عبر المنصات الاجتماعية</span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <button type="button" class="btn btn-light w-100 py-2 border-0 fw-bold small text-muted" style="background: #f8f9fa; border-radius: 10px;">
                                <i class="bi bi-google me-2"></i> Google
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-light w-100 py-2 border-0 fw-bold small text-muted" style="background: #f8f9fa; border-radius: 10px;">
                                <i class="bi bi-apple me-2"></i> Apple
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Footer -->
                <div class="text-center mt-5">
                    <p class="text-muted small">
                        أنت جديد هنا؟ 
                        <a href="{{ route('register') }}" class="fw-bold text-decoration-none ms-1" style="color: var(--reefy-success);">
                            ابدأ رحلتك الزراعية الآن
                        </a>
                    </p>
                </div>
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
        .cursor-pointer { cursor: pointer; }
    </style>
</x-guest-layout>
