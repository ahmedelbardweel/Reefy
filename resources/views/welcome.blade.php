<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reefy - Smart Agriculture</title>
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <script>
        // Theme Initialization
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-bs-theme', 'light');
        }
    </script>
    <style>
            :root {
                --reefy-primary: #3f6212;       /* Deep Olive */
                --reefy-success: #84cc16;       /* Vibrant Lime */
                --reefy-accent: #a3e635;        /* Bright Leaf */
                --reefy-soft: #f7fee7;
                --bg-primary: #ffffff;
                --text-primary: #333333;
                --card-bg: #ffffff;
                --border-color: #f1f5f9;
            }

            [data-bs-theme="dark"] {
                --reefy-primary: #ecfccb;
                --reefy-success: #84cc16;
                --reefy-soft: #1a2e05;
                --bg-primary: #0a0f02;
                --text-primary: #ecfccb;
                --card-bg: #141d05;
                --border-color: rgba(255,255,255,0.1);
            }
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Outfit', 'Cairo', sans-serif;
            background-color: var(--bg-primary);
            margin: 0;
            color: var(--text-primary);
        }
        .text-reefy { color: var(--reefy-primary) !important; }
        .bg-reefy { background-color: var(--reefy-primary) !important; }
        
        .btn-reefy {
            background: var(--reefy-success);
            border: none;
            color: white;
            font-weight: 700;
            padding: 0.8rem 2rem;
            border-radius: 0px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 0px #3f6212;
        }
        .btn-reefy:hover {
            transform: translateY(1px);
            box-shadow: 0 3px 0px #3f6212;
            color: white;
            filter: brightness(110%);
        }

        .navbar {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        .navbar-transparent .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }
        .navbar-scrolled {
            background: var(--bg-primary) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }
        .navbar-scrolled .nav-link { color: var(--reefy-primary) !important; }
        .navbar-scrolled .navbar-brand { color: var(--reefy-success) !important; }

        .hero-section {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.2)),
                        url('https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            color: white;
            text-align: center;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .feature-card {
            border: 1px solid var(--border-color);
            border-radius: 0px;
            padding: 2.5rem;
            background: var(--card-bg);
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.05);
            border-color: var(--reefy-success);
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--reefy-soft);
            color: var(--reefy-success);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0px;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
    </style>
</head>
<body class="antialiased">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-transparent fixed-top">
        <div class="container">
            <a class="navbar-brand fs-3 fw-bold d-flex align-items-center gap-2" href="#">
                <i class="bi bi-intersect"></i> Reefy
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="bi bi-list fs-2 text-white"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3 align-items-center">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="btn btn-reefy py-2 px-4 shadow">لوحة التحكم</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link px-3">تسجيل الدخول</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-light text-success fw-bold py-2 px-4 rounded-3 shadow">انضم إلينا</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="hero-title">مستقبل الزراعة بين يديك</h1>
                    <p class="lead mb-5 fs-4 opacity-90">منصة "ريفي" هي شريكك الذكي لإدارة مزرعتك بفعالية، التواصل مع الخبراء، والنمو بمحاصيلك إلى آفاق جديدة.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-reefy btn-lg px-5 py-3 shadow-lg">ابدأ رحلتك الآن</a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-5 py-3 border-2">اكتشف المميزات</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="features" class="py-5" style="background-color: var(--bg-primary);">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color: var(--text-primary);">لماذا تختار ريفي؟</h2>
                <p class="text-muted">نقدم حلولاً تقنية متكاملة صممت خصيصاً للمزارع العربي</p>
            </div>
            <div class="row g-4 text-end">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h4 class="fw-bold mb-3">تتبع ذكي للنمو</h4>
                        <p class="text-muted small">راقب مراحل نمو محاصيلك بدقة واحصل على تنبيهات ذكية لمواعيد الري والتسميد.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <h4 class="fw-bold mb-3">استشارات الخبراء</h4>
                        <p class="text-muted small">تواصل مباشرة مع نخبة من الخبراء الزراعيين للحصول على نصائح دقيقة لمشكلات مزرعتك.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h4 class="fw-bold mb-3">مجتمع تعاوني</h4>
                        <p class="text-muted small">شارك تجاربك مع مزارعين آخرين، وتبادل المعرفة والخبرات في أكبر مجتمع زراعي رقمي.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer or Final CTA -->
    <section class="py-5" style="background: var(--reefy-soft);">
        <div class="container text-center py-4">
            <h3 class="fw-bold mb-4">هل أنت جاهز لتطوير مزرعتك؟</h3>
            <a href="{{ route('register') }}" class="btn btn-reefy btn-lg px-5 shadow">سجل مجاناً اليوم</a>
        </div>
    </section>

    <!-- Navbar Scroll Script -->
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            const navBtn = document.querySelector('.navbar .btn-light');

            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
                navbar.classList.remove('navbar-transparent', 'navbar-dark');
                navbar.classList.add('navbar-light');
                if(navBtn) {
                    navBtn.classList.remove('btn-light');
                    navBtn.classList.add('btn-reefy');
                    navBtn.classList.remove('text-success');
                }
            } else {
                navbar.classList.add('navbar-transparent', 'navbar-dark');
                navbar.classList.remove('navbar-scrolled', 'navbar-light');
                if(navBtn) {
                    navBtn.classList.add('btn-light');
                    navBtn.classList.remove('btn-reefy');
                    navBtn.classList.add('text-success');
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>