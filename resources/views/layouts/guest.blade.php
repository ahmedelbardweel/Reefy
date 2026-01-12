@props(['fullWidth' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" data-bs-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Reefy') }}</title>

        <!-- Bootstrap 5 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <script>
            // Theme Initialization
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        </script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Outfit:wght@300;400;600;700&display=swap');
            
            :root {
                --reefy-primary: #3f6212;
                --reefy-success: #84cc16;
                --soil-primary: #795548;
                --soil-dark: #3E2723;
            }
            body { font-family: 'Outfit', 'Cairo', sans-serif; background-color: var(--bg-primary); color: var(--text-primary); }
            
            /* Sharp & Solid Buttons */
            .btn-primary, .btn-success {
                background: var(--reefy-success) !important;
                border: none !important;
                color: white !important;
                font-weight: 700;
                box-shadow: 0 4px 0px #3f6212;
                transition: all 0.2s ease;
                border-radius: 0px !important;
                padding: 12px 24px;
            }
            .btn-primary:hover, .btn-success:hover {
                transform: translateY(1px);
                box-shadow: 0 3px 0px #3f6212;
                filter: brightness(110%);
            }
            .btn-primary:active, .btn-success:active {
                transform: translateY(4px);
                box-shadow: none;
            }
            a.text-success, .text-success { color: var(--reefy-success) !important; }
            .card { border-radius: 0px !important; }
        </style>
    </head>
    <body>
        @if(isset($fullWidth) && $fullWidth)
            {{ $slot }}
        @else
            <div class="container py-5 min-vh-100 d-flex flex-column justify-content-center">
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center mb-4">
                        <a href="/" class="text-decoration-none d-flex align-items-center justify-content-center gap-2">
                            <span class="fs-2 fw-bold" style="color: var(--heading-color) !important; letter-spacing: -1px;">الريفي (Reefy)</span>
                            <div class="bg-success text-white p-1 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: var(--reefy-success) !important; border-radius: 0 !important;">
                                <i class="bi bi-layers-half fs-4"></i>
                            </div>
                        </a>
                    </div>
                </div>
                
                <div class="row justify-content-center">
                    <div class="col-md-7 col-lg-5">
                        <div class="card shadow-sm border-0" style="border-radius: 0px !important; background: var(--bg-secondary) !important; border: 1.5px solid var(--border-color) !important;">
                            <div class="card-body p-4 p-md-5">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
