@props(['fullWidth' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
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
        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Outfit:wght@300;400;600;700&display=swap');
            
            :root {
                --reefy-primary: #1b4332;
                --reefy-success: #2d6a4f;
                --soil-primary: #795548;
                --soil-dark: #3E2723;
            }
            body { font-family: 'Outfit', 'Cairo', sans-serif; background-color: #fcfdfc; color: #333; }
            
            /* Premium Action Button Style */
            .btn-primary, .btn-success {
                background: linear-gradient(135deg, var(--reefy-success), var(--reefy-primary)) !important;
                border: none !important;
                color: white !important;
                font-weight: 700;
                box-shadow: 0 4px 12px rgba(45, 106, 79, 0.25);
                transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                border-radius: 12px !important;
                padding: 12px 24px;
            }
            .btn-primary:hover, .btn-success:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(45, 106, 79, 0.35);
                filter: brightness(110%);
            }
            a.text-success, .text-success { color: var(--reefy-success) !important; }
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
                            <span class="fs-2 fw-bold" style="color: var(--reefy-primary); letter-spacing: -1px;">Reefy</span>
                            <div class="bg-success text-white rounded-3 p-1 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--reefy-success), var(--reefy-primary)) !important;">
                                <i class="bi bi-layers-half fs-4"></i>
                            </div>
                        </a>
                    </div>
                </div>
                
                <div class="row justify-content-center">
                    <div class="col-md-7 col-lg-5">
                        <div class="card shadow-sm border-0" style="border-radius: 20px !important; background: #fcfcfc; border: 1.5px solid #e2eee8 !important;">
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
