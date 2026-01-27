<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl leading-tight mb-0" style="color: var(--heading-color);">
                {{ __('الملف الشخصي') }}
            </h2>
            <a href="{{ route('profile.edit') }}" class="btn btn-success shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> تعديل الملف
            </a>
        </div>
    </x-slot>

    <div class="profile-container mb-5">
        <!-- Cover Image Section -->
        <div class="profile-cover position-relative overflow-hidden" style="height: 300px; background: var(--bg-secondary); border-bottom: 3px solid var(--reefy-success);">
            @if(Auth::user()->cover_image)
                <img src="{{ asset(Auth::user()->cover_image) }}" alt="Cover" class="w-100 h-100 object-fit-cover">
            @else
                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-reefy-soft">
                    <i class="bi bi-image text-success opacity-25" style="font-size: 5rem;"></i>
                </div>
            @endif
        </div>

        <div class="container position-relative" style="margin-top: -80px; z-index: 10;">
            <div class="row align-items-end px-4">
                <div class="col-auto">
                    <!-- Avatar Section -->
                    <div class="profile-avatar-wrapper p-1 bg-white" style="border-radius: 0; width: 160px; height: 160px; border: 4px solid var(--bg-secondary);">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                <span class="fs-1 fw-bold text-success">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col mb-3">
                    <h1 class="fw-bold mb-1" style="color: var(--heading-color);">{{ Auth::user()->name }}</h1>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                             @if(Auth::user()->role == 'farmer') <i class="bi bi-tree-fill me-1"></i> مزارع 
                             @elseif(Auth::user()->role == 'expert') <i class="bi bi-patch-check-fill me-1"></i> خبير 
                             @else {{ Auth::user()->role }} @endif
                        </span>
                        <span class="text-muted"><i class="bi bi-envelope me-1"></i> {{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

            <!-- Profile Info Content -->
            <div class="row mt-5 g-4">
                <div class="col-lg-8">
                    <!-- Stats / Overview Card -->
                    <div class="card border-0 p-4 mb-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">نظرة عامة</h5>
                        <div class="row g-4 text-center">
                            @if(Auth::user()->role == 'farmer')
                                <div class="col-md-4">
                                    <div class="p-3 border">
                                        <div class="text-muted small mb-1">المحاصيل</div>
                                        <div class="fs-4 fw-bold text-success">{{ Auth::user()->crops()->count() }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border">
                                        <div class="text-muted small mb-1">المهام القادمة</div>
                                        <div class="fs-4 fw-bold text-success">
                                            @php 
                                                $pendingTasks = 0;
                                                foreach(Auth::user()->crops as $crop) {
                                                    $pendingTasks += $crop->tasks()->where('status', 'pending')->count();
                                                }
                                            @endphp
                                            {{ $pendingTasks }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border">
                                        <div class="text-muted small mb-1">الاستشارات</div>
                                        <div class="fs-4 fw-bold text-success">{{ Auth::user()->consultations()->count() }}</div>
                                    </div>
                                </div>
                            @elseif(Auth::user()->role == 'expert')
                                <div class="col-md-6">
                                    <div class="p-3 border">
                                        <div class="text-muted small mb-1">الاستشارات التي قدمتها</div>
                                        <div class="fs-4 fw-bold text-success">{{ Auth::user()->expertAdvice()->count() }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border">
                                        <div class="text-muted small mb-1">النصائح المنشورة</div>
                                        <div class="fs-4 fw-bold text-success">{{ Auth::user()->expertTips()->count() }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Profile Details -->
                    <div class="card border-0 p-4">
                         <h5 class="fw-bold mb-4 border-bottom pb-2">التفاصيل الشخصية</h5>
                         <div class="space-y-4">
                             <div class="d-flex justify-content-between align-items-center p-3 border-bottom border-light">
                                 <span class="text-muted">الاسم الكامل</span>
                                 <span class="fw-semibold">{{ Auth::user()->name }}</span>
                             </div>
                             <div class="d-flex justify-content-between align-items-center p-3 border-bottom border-light">
                                 <span class="text-muted">البريد الإلكتروني</span>
                                 <span class="fw-semibold">{{ Auth::user()->email }}</span>
                             </div>
                             <div class="d-flex justify-content-between align-items-center p-3">
                                 <span class="text-muted">تاريخ الانضمام</span>
                                 <span class="fw-semibold">{{ Auth::user()->created_at->format('Y/m/d') }}</span>
                             </div>
                         </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Actions / Sidebar Info -->
                    <div class="card border-0 p-4 mb-4 bg-success text-white">
                        <h5 class="fw-bold mb-3">حالة الحساب</h5>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="ripple-status bg-white" style="width: 12px; height: 12px;"></div>
                            <span class="fw-bold">نشط</span>
                        </div>
                        <p class="small opacity-75">حسابك مفعل وجاهز للاستخدام. يمكنك دائماً تحديث بياناتك من خلال زر التعديل.</p>
                    </div>

                    <div class="card border-0 p-4 mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">تأمين الحساب</h5>
                        <p class="small text-muted">ينصح بتغيير كلمة المرور بشكل دوري لضمان أمان حسابك.</p>
                        <a href="{{ route('profile.edit') }}#update-password" class="btn btn-outline-success btn-sm w-100">تغيير كلمة المرور</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-reefy-soft { background-color: var(--reefy-primary-soft); }
        .object-fit-cover { object-fit: cover; }
    </style>
</x-app-layout>
