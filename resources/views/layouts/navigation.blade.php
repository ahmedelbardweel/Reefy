<!-- Sidebar for Desktop -->
<aside class="d-none d-lg-flex flex-column bg-white shadow-sm border-end sticky-top vh-100 p-0 overflow-visible" style="width: 280px; z-index: 1050; border-left: none !important; background: var(--bg-secondary) !important; border-color: var(--border-color) !important;">
    <div class="p-4 border-bottom mb-2">
        <a class="navbar-brand fw-bold fs-3 text-success d-flex align-items-center gap-3 text-decoration-none" href="{{ route('dashboard') }}">
            <span class="ls-tight" style="color: var(--heading-color) !important; font-size: 2rem;">الريفي</span>
        </a>
    </div>

    <div class="px-3 flex-grow-1 overflow-auto py-2 custom-scrollbar">
        <div class="nav-section-label text-uppercase text-secondary fw-bold px-3 mb-2 opacity-50" style="font-size: 0.65rem; letter-spacing: 1px;">القائمة الرئيسية</div>
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 {{ request()->routeIs('*.dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-fill fs-5"></i>
                    <span class="fw-bold">لوحة التحكم</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 {{ request()->routeIs('community.index') ? 'active' : '' }}" href="{{ route('community.index') }}">
                    <i class="bi bi-people-fill fs-5"></i>
                    <span class="fw-bold">المجتمع الزراعي</span>
                </a>
            </li>
            
            @auth
            @if(auth()->user()->role === 'farmer')
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 {{ request()->routeIs('crops.*') ? 'active' : '' }}" href="{{ route('crops.index') }}">
                    <i class="bi bi-flower1 fs-5"></i>
                    <span class="fw-bold">إدارة المحاصيل</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'farmer')
            <div class="nav-section-label text-uppercase text-secondary fw-bold px-3 mt-4 mb-2 opacity-50" style="font-size: 0.65rem; letter-spacing: 1px;">الأنظمة الذكية</div>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 nav-link-info {{ request()->routeIs('farmer.systems.irrigation') ? 'active' : '' }}" href="{{ route('farmer.systems.irrigation') }}">
                    <i class="bi bi-moisture fs-5 text-info"></i>
                    <span class="fw-bold">نظام الري</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 nav-link-danger {{ request()->routeIs('farmer.systems.treatment') ? 'active' : '' }}" href="{{ route('farmer.systems.treatment') }}">
                    <i class="bi bi-shield-plus fs-5 text-danger"></i>
                    <span class="fw-bold">مركز المعالجة</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 {{ request()->routeIs('farmer.systems.harvesting') ? 'active' : '' }}" href="{{ route('farmer.systems.harvesting') }}">
                    <i class="bi bi-box-seam fs-5 text-success"></i>
                    <span class="fw-bold">تتبع الحصاد</span>
                </a>
            </li>
            @endif

            <div class="nav-section-label text-uppercase text-secondary fw-bold px-3 mt-4 mb-2 opacity-50" style="font-size: 0.65rem; letter-spacing: 1px;">خدمات ريفي</div>
            <li class="nav-item">
                @php
                    $consultationRoute = auth()->user()->role === 'expert' ? 'expert.consultations.index' : 'consultations.index';
                @endphp
                <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 {{ request()->routeIs('consultations.*') || request()->routeIs('expert.consultations.*') ? 'active' : '' }}" href="{{ route($consultationRoute) }}">
                    <i class="bi bi-chat-left-dots fs-5 text-warning"></i>
                    <span class="fw-bold">الاستشارات</span>
                </a>
            </li>
            @endauth
        </ul>
    </div>

    <div class="p-3 border-top bg-light bg-opacity-50">
        <!-- Notifications Shortcut in Sidebar -->
        <a href="{{ route('notifications.index') }}" class="d-flex align-items-center gap-3 px-3 py-2 rounded-0 text-secondary text-decoration-none hover-bg-white shadow-sm mb-2" style="background: var(--bg-primary); border: 1px solid var(--border-color);">
            <div class="position-relative">
                <i class="bi bi-bell-fill fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-0 bg-danger p-1 notification-badge" style="display: none; width: 8px; height: 8px;"></span>
            </div>
            <span class="fw-bold small">الإشعارات</span>
        </a>

        <!-- Theme Toggle -->
        <button onclick="toggleTheme()" class="btn d-flex align-items-center gap-3 px-3 py-2 rounded-0 text-secondary w-100 border-0 shadow-sm mb-3 hover-bg-white" style="background: var(--bg-primary); border: 1px solid var(--border-color) !important;">
            <i class="bi bi-sun-fill fs-5 theme-icon-light"></i>
            <i class="bi bi-moon-stars-fill fs-5 theme-icon-dark d-none"></i>
            <span class="fw-bold small theme-text">تبديل المظهر</span>
        </button>

        <!-- User Profile Card -->
        @auth
        <div class="dropdown">
            <button class="btn btn-outline-light border-0 d-flex align-items-center gap-3 w-100 px-3 py-2 rounded-0 text-start transition-all hover-white-shadow" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: var(--bg-primary); border: 1px solid var(--border-color) !important;">
                <div class="text-white rounded-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; flex-shrink: 0; background: var(--reefy-success);">
                    <i class="bi bi-person fs-5"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark small text-truncate" style="color: var(--heading-color) !important;">{{ Auth::user()->name }}</div>
                    <div class="text-secondary opacity-75" style="font-size: 0.7rem;">تحرير الشخصي</div>
                </div>
                <i class="bi bi-chevron-up text-secondary small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-start shadow-lg border-0 p-2 rounded-0 mb-2 w-100" style="bottom: 100% !important; top: auto !important; background: var(--bg-secondary); border: 1px solid var(--border-color) !important;">
                <li>
                    <a class="dropdown-item rounded-0 py-2 d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person-gear"></i> إعدادات الحساب
                    </a>
                </li>
                <li><hr class="dropdown-divider opacity-5"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger rounded-0 py-2 d-flex align-items-center gap-2">
                            <i class="bi bi-power"></i> تسجيل الخروج
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @else
        <a href="{{ route('login') }}" class="btn btn-success w-100 rounded-0 py-2 fw-bold">تسجيل الدخول</a>
        @endauth
    </div>
</aside>

<script>
    function updateThemeIcons() {
        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        document.querySelectorAll('.theme-icon-light').forEach(el => el.classList.toggle('d-none', isDark));
        document.querySelectorAll('.theme-icon-dark').forEach(el => el.classList.toggle('d-none', !isDark));
    }

    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcons();
    }

    // Initialize icons on load
    document.addEventListener('DOMContentLoaded', updateThemeIcons);
</script>

<!-- Offcanvas Sidebar for Mobile -->
<div class="offcanvas offcanvas-start border-0 p-0 shadow-lg" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel" style="width: 300px; direction: rtl;">
    <div class="offcanvas-header border-bottom p-4">
        <h5 class="offcanvas-title fw-bold text-success d-flex align-items-center gap-3" id="sidebarOffcanvasLabel">
            <div class="text-white p-2 rounded-0 shadow-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; background: var(--reefy-primary);">
                <i class="bi bi-layers-half fs-5"></i>
            </div>
            <span style="color: var(--reefy-primary) !important;">الريفي</span>
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-3 overflow-auto custom-scrollbar">
        <ul class="nav nav-pills flex-column gap-2">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-0 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-columns-gap fs-5"></i>
                    <span class="fw-bold">لوحة التحكم</span>
                </a>
            </li>

            @auth
                @if(auth()->user()->role === 'farmer')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-3 px-3 py-3 rounded-0 {{ request()->routeIs('crops.*') ? 'active' : '' }}" href="{{ route('crops.index') }}">
                        <i class="bi bi-flower1 fs-5"></i>
                        <span class="fw-bold">إدارة المحاصيل</span>
                    </a>
                </li>

                <div class="text-uppercase text-secondary fw-bold px-3 mt-4 mb-2 opacity-50" style="font-size: 0.7rem; letter-spacing: 1px;">الأنظمة</div>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 nav-link-info {{ request()->routeIs('farmer.systems.irrigation') ? 'active' : '' }}" href="{{ route('farmer.systems.irrigation') }}">
                        <i class="bi bi-moisture text-info fs-5"></i>
                        <span class="fw-bold">نظام الري</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 nav-link-danger {{ request()->routeIs('farmer.systems.treatment') ? 'active' : '' }}" href="{{ route('farmer.systems.treatment') }}">
                        <i class="bi bi-shield-plus text-danger fs-5"></i>
                        <span class="fw-bold">مركز المعالجة</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 {{ request()->routeIs('farmer.systems.harvesting') ? 'active' : '' }}" href="{{ route('farmer.systems.harvesting') }}">
                        <i class="bi bi-box-seam text-success fs-5"></i>
                        <span class="fw-bold">تتبع الحصاد</span>
                    </a>
                </li>
                @endif

                <div class="text-uppercase text-secondary fw-bold px-3 mt-4 mb-2 opacity-50" style="font-size: 0.7rem; letter-spacing: 1px;">الاستشارات والتجارة</div>
                <li class="nav-item">
                    @php
                        $mobileConsultationRoute = auth()->user()->role === 'expert' ? 'expert.consultations.index' : 'consultations.index';
                    @endphp
                    <a class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-0 {{ request()->routeIs('consultations.*') || request()->routeIs('expert.consultations.*') ? 'active' : '' }}" href="{{ route($mobileConsultationRoute) }}">
                        <i class="bi bi-chat-left-dots text-warning fs-5"></i>
                        <span class="fw-bold">الاستشارات</span>
                    </a>
                </li>
            @endauth
        </ul>
    </div>
    <div class="p-4 border-top bg-light">
        @auth
            <div class="d-flex align-items-center gap-3 mb-4">
                 <div class="bg-success text-white rounded-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px; flex-shrink: 0;">
                    <i class="bi bi-person-fill fs-4"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                    <div class="text-secondary small">{{ Auth::user()->email }}</div>
                </div>
            </div>
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-white shadow-sm border-0 w-100 rounded-0 d-flex align-items-center justify-content-center gap-2 py-3 text-danger fw-bold">
                    <i class="bi bi-power fs-5"></i> خروج آمن
                </button>
            </form>
        @else
            <div class="d-grid gap-2">
                <a href="{{ route('login') }}" class="btn btn-success rounded-0 py-3 fw-bold">دخول</a>
                <a href="{{ route('register') }}" class="btn btn-outline-success rounded-0 py-3 fw-bold">حساب جديد</a>
            </div>
        @endauth
    </div>
</div>

<style>
    .hover-bg-light:hover { background-color: #f8faf9; color: var(--reefy-success) !important; }
    .hover-white-shadow:hover { box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05) !important; transform: translateY(-1px); }
    .transition-all { transition: all 0.25s ease; }
    .ls-tight { letter-spacing: -0.015em; font-weight: 800; }
    
    /* Premium Sidebar Navigation Style */
    .nav-pills .nav-link { 
        border: none !important; 
        transition: all 0.2s ease; 
        color: #6c757d !important;
        position: relative;
    }
    
    .nav-pills .nav-link:hover {
        background-color: #f8faf9;
        color: var(--reefy-primary) !important;
    }

    .nav-pills .nav-link.active { 
        background-color: var(--reefy-primary-soft) !important; 
        color: var(--reefy-primary) !important;
        border-radius: 0px !important;
    }

    /* Active Indicator Line */
    .nav-pills .nav-link.active::after {
        content: "";
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: var(--reefy-primary);
    }

    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #eee; border-radius: 0px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #ddd; }
    
    .nav-link.active .bi { color: var(--reefy-primary) !important; opacity: 1; }
    .nav-link:not(.active) .bi { color: #6c757d !important; opacity: 0.6; }

    /* Custom Tints for Smart Systems */
    .nav-link-info.active { background-color: #e0f2fe !important; color: #0369a1 !important; border-right-color: #0369a1 !important; }
    .nav-link-info.active .bi { color: #0369a1 !important; }
    .nav-link-danger.active { background-color: #fef2f2 !important; color: #b91c1c !important; border-right-color: #b91c1c !important; }
    .nav-link-danger.active .bi { color: #b91c1c !important; }
</style>
