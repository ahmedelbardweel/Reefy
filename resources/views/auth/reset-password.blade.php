<x-guest-layout :fullWidth="true">
    <div class="row g-0 min-vh-100 overflow-hidden">
        
        <!-- Left Section: Serene Visual (Hidden on mobile) -->
        <div class="col-lg-6 d-none d-lg-block position-relative">
            <img src="https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?auto=format&fit=crop&q=80&w=2000" 
                 class="w-100 h-100 object-fit-cover" 
                 alt="Reefy New Start">
            
            <!-- Dynamic Gradient Overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" 
                 style="background: linear-gradient(220deg, rgba(27, 67, 50, 0.45) 0%, rgba(45, 106, 79, 0.8) 100%);"></div>

            <!-- Glassmorphic Message -->
            <div class="position-absolute bottom-0 start-0 w-100 p-5 p-xl-6 text-white text-end">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-white bg-opacity-20 backdrop-blur border border-white border-opacity-30 rounded-pill mb-4 small fw-semibold shadow-sm">
                    <span class="bi bi-arrow-clockwise text-mint"></span>
                    <span>بداية جديدة آمنة</span>
                </div>
                
                <h2 class="display-6 fw-bold mb-4 lh-base" style="font-family: 'Cairo', sans-serif;">
                    "اختر كلمة مرور قوية لحماية حسابك وبياناتك الزراعية."
                </h2>
            </div>
        </div>

        <!-- Right Section: Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-md-5" style="background-color: var(--bg-secondary);">
            <div class="w-100" style="max-width: 440px;">
                
                <!-- Brand Mobile -->
                <div class="d-flex justify-content-center mb-5 d-lg-none">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-2 fw-bold text-dark">ريفي</span>
                        <div class="bg-success text-white rounded-3 p-1 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #84cc16, #3f6212) !important;">
                            <i class="bi bi-layers-half fs-4"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-5 text-end">
                    <h1 class="fw-bold mb-3 h2" style="color: var(--heading-color) !important; font-family: 'Cairo', sans-serif;">إعادة تعيين كلمة المرور</h1>
                    <p class="text-muted" style="color: var(--text-secondary) !important;">أدخل بريدك الإلكتروني وكلمة المرور الجديدة لاستعادة الوصول إلى حسابك.</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold mb-2 text-end d-block" style="color: var(--text-secondary);">البريد الإلكتروني</label>
                        <div class="position-relative">
                            <input type="email" 
                                   class="form-control form-control-lg text-end border-0 ps-4 pe-5 theme-input" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $request->email) }}" 
                                   required 
                                   autofocus 
                                   placeholder="name@example.com"
                                   style="background: var(--bg-primary); color: var(--text-primary); border-radius: 0px !important; height: 50px; font-size: 0.9rem; border: 1.5px solid var(--border-color) !important; transition: all 0.2s ease;">
                            <i class="bi bi-envelope position-absolute top-50 translate-middle-y end-0 me-3 text-muted"></i>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small text-end" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold mb-2 text-end d-block" style="color: var(--text-secondary);">كلمة المرور الجديدة</label>
                        <div class="position-relative">
                            <input type="password" 
                                   class="form-control form-control-lg text-end border-0 ps-4 pe-5 theme-input" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="••••••••"
                                   style="background: var(--bg-primary); color: var(--text-primary); border-radius: 0px !important; height: 50px; font-size: 0.9rem; border: 1.5px solid var(--border-color) !important; transition: all 0.2s ease;">
                            <i class="bi bi-shield-lock position-absolute top-50 translate-middle-y end-0 me-3 text-muted"></i>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small text-end" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-5">
                        <label for="password_confirmation" class="form-label fw-semibold mb-2 text-end d-block" style="color: var(--text-secondary);">تأكيد كلمة المرور</label>
                        <div class="position-relative">
                            <input type="password" 
                                   class="form-control form-control-lg text-end border-0 ps-4 pe-5 theme-input" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password" 
                                   placeholder="••••••••"
                                   style="background: var(--bg-primary); color: var(--text-primary); border-radius: 0px !important; height: 50px; font-size: 0.9rem; border: 1.5px solid var(--border-color) !important; transition: all 0.2s ease;">
                            <i class="bi bi-shield-check position-absolute top-50 translate-middle-y end-0 me-3 text-muted"></i>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger small text-end" />
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-0 shadow-sm border-0" 
                            style="height: 56px; font-size: 1.1rem; border-radius: 0px !important; background: var(--reefy-primary) !important; color: white !important;">
                        إعادة تعيين كلمة المرور
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Micro-interactions Style -->
    <style>
        input:focus {
            background: var(--bg-secondary) !important;
            border-color: var(--reefy-success) !important;
            box-shadow: 0 0 0 4px rgba(132, 204, 22, 0.1) !important;
            outline: none;
            color: var(--text-primary) !important;
        }
        .backdrop-blur { backdrop-filter: blur(10px); }
        .text-mint { color: #8ef9d5; }
    </style>
</x-guest-layout>
