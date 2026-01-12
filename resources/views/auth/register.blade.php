<x-guest-layout :fullWidth="true">
    <div class="row g-0 min-vh-100 overflow-hidden">
        
        <!-- Left Section: Serene Visual (Hidden on mobile) -->
        <div class="col-lg-6 d-none d-lg-block position-relative">
            <img src="https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&q=80&w=2000" 
                 class="w-100 h-100 object-fit-cover" 
                 alt="Reefy Community">
            
            <!-- Dynamic Gradient Overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" 
                 style="background: linear-gradient(220deg, rgba(27, 67, 50, 0.45) 0%, rgba(45, 106, 79, 0.8) 100%);"></div>

            <!-- Glassmorphic Testimonial -->
            <div class="position-absolute bottom-0 start-0 w-100 p-5 p-xl-6 text-white text-end">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-white bg-opacity-20 backdrop-blur border border-white border-opacity-30 rounded-pill mb-4 small fw-semibold shadow-sm">
                    <span class="bi bi-stars text-mint"></span>
                    <span>مجتمع ريفي المتنامي</span>
                </div>
                
                <h2 class="display-6 fw-bold mb-4 lh-base" style="font-family: 'Cairo', sans-serif;">
                    "ابدأ رحلتك اليوم في أكبر تجمع للمزارعين الأذكياء، حيث التكنولوجيا تلتقي بالأرض."
                </h2>
                
                <div class="d-flex align-items-center gap-3 justify-content-end mt-4">
                    <div class="text-end">
                        <div class="fw-bold fs-5">مجتمع المزارعين</div>
                        <div class="small opacity-75">أكثر من 5000 مزارع نشط</div>
                    </div>
                    <div class="d-flex -space-x-2">
                        <img src="https://ui-avatars.com/api/?name=1&background=random" class="rounded-circle border border-2 border-white" width="40" height="40" alt="User">
                        <img src="https://ui-avatars.com/api/?name=2&background=random" class="rounded-circle border border-2 border-white" width="40" height="40" alt="User">
                        <img src="https://ui-avatars.com/api/?name=3&background=random" class="rounded-circle border border-2 border-white" width="40" height="40" alt="User">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section: Register Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-md-5 overflow-y-auto" style="max-height: 100vh; background-color: var(--bg-secondary);">
            <div class="w-100" style="max-width: 480px; padding: 2rem 0;">
                
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
                    <h1 class="fw-bold mb-3 h2" style="color: var(--heading-color) !important; font-family: 'Cairo', sans-serif;">انضم إلينا</h1>
                    <p class="text-muted" style="color: var(--text-secondary) !important;">كن جزءاً من مستقبل الزراعة الذكية في المنطقة. خطوات بسيطة تفصلك عن البداية.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="needs-validation">
                    @csrf
                    
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label fw-semibold mb-2 text-end d-block" style="color: var(--text-secondary);">الاسم بالكامل</label>
                            <div class="position-relative">
                                <input type="text" 
                                       class="form-control form-control-lg text-end border-0 ps-4 pe-5 theme-input" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autofocus 
                                       placeholder="أحمد محمد"
                                       style="background: var(--bg-primary); color: var(--text-primary); border-radius: 0px !important; height: 50px; font-size: 0.9rem; border: 1.5px solid var(--border-color) !important; transition: all 0.2s ease;">
                                <i class="bi bi-person position-absolute top-50 translate-middle-y end-0 me-3 text-muted"></i>
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger small text-end" />
                        </div>

                        <!-- Role -->
                        <div class="col-md-6 mb-4">
                            <label for="role" class="form-label fw-semibold mb-2 text-end d-block" style="color: var(--text-secondary);">أقوم بالتسجيل كـ</label>
                            <div class="position-relative">
                                <select id="role" name="role" class="form-select form-control-lg text-end border-0 ps-4 pe-5 theme-input" 
                                        style="background: var(--bg-primary); color: var(--text-primary); border-radius: 0px !important; height: 50px; font-size: 0.9rem; border: 1.5px solid var(--border-color) !important; appearance: none; -webkit-appearance: none;">
                                    <option value="farmer">مزارع (Farmer)</option>
                                    <option value="expert">خبير زراعي (Expert)</option>
                                </select>
                                <i class="bi bi-briefcase position-absolute top-50 translate-middle-y end-0 me-3 text-muted"></i>
                            </div>
                            <x-input-error :messages="$errors->get('role')" class="mt-2 text-danger small text-end" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold mb-2 text-end d-block" style="color: var(--text-secondary);">البريد الإلكتروني</label>
                        <div class="position-relative">
                            <input type="email" 
                                   class="form-control form-control-lg text-end border-0 ps-4 pe-5 theme-input" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   placeholder="name@example.com"
                                   style="background: var(--bg-primary); color: var(--text-primary); border-radius: 0px !important; height: 50px; font-size: 0.9rem; border: 1.5px solid var(--border-color) !important; transition: all 0.2s ease;">
                            <i class="bi bi-envelope position-absolute top-50 translate-middle-y end-0 me-3 text-muted"></i>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small text-end" />
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-4">
                            <label for="password" class="form-label fw-semibold mb-2 text-end d-block" style="color: var(--text-secondary);">كلمة المرور</label>
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
                        <div class="col-md-6 mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold mb-2 text-end d-block" style="color: var(--text-secondary);">تأكيد الكلمة</label>
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
                    </div>

                    <div class="form-check d-flex align-items-center gap-2 mb-5 mt-2 justify-content-end">
                        <label class="form-check-label small text-muted cursor-pointer" for="terms">أوافق على شروط الاستخدام وسياسة الخصوصية</label>
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="terms" required style="width: 18px; height: 18px;">
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm border-0 mb-4" 
                            style="height: 56px; font-size: 1.1rem; border-radius: 0px !important; background: var(--reefy-primary) !important; color: white !important;">
                        إنشاء حساب جديد
                    </button>
                    
                    <div class="text-center">
                        <span class="small text-muted fw-semibold">أو اشترك عبر</span>
                    </div>
                    
                    <div class="row g-3 mt-1">
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
                        لديك حساب بالفعل؟ 
                        <a href="{{ route('login') }}" class="fw-bold text-decoration-none ms-1" style="color: var(--reefy-success);">
                            سجل دخولك من هنا
                        </a>
                    </p>
                </div>
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
        select:focus {
            background: var(--bg-secondary) !important;
            border-color: var(--reefy-success) !important;
            box-shadow: 0 0 0 4px rgba(132, 204, 22, 0.1) !important;
            outline: none;
            color: var(--text-primary) !important;
        }
        .theme-input option {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }
        .backdrop-blur { backdrop-filter: blur(10px); }
        .text-mint { color: #8ef9d5; }
        .cursor-pointer { cursor: pointer; }
        .-space-x-2 > * + * { margin-right: -0.75rem; }
    </style>
</x-guest-layout>
