<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Reefy') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                /* Premium Agricultural Palette */
                --reefy-primary: #1b4332;       /* Deep Forest */
                --reefy-success: #2d6a4f;       /* Emerald Forest */
                --reefy-accent: #40916c;        /* Vibrant Leaf Green */
                --reefy-primary-soft: #f0f7f4;
                --reefy-border: rgba(0,0,0,0.06);
            }

            body { font-family: 'Outfit', 'Cairo', sans-serif; background-color: #fcfdfc; color: #333; }

            /* Premium Action Button (Revisited) */
            .btn-success { 
                background: linear-gradient(135deg, var(--reefy-success), var(--reefy-primary)) !important;
                border: none !important;
                color: white !important;
                font-weight: 700;
                letter-spacing: 0.02em;
                box-shadow: 0 4px 12px rgba(45, 106, 79, 0.25);
                transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                border-radius: 12px !important;
            }
            
            .btn-success:hover { 
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(45, 106, 79, 0.35);
                filter: brightness(110%);
            }

            .btn-success:active {
                transform: translateY(0);
            }

            .btn-outline-success { 
                color: var(--reefy-success) !important; 
                border: 2px solid var(--reefy-success) !important;
                font-weight: 700;
                border-radius: 12px !important;
            }
            .btn-outline-success:hover { 
                background: var(--reefy-success) !important; 
                color: white !important; 
            }

            /* Text & Background Utilities */
            .text-success { color: var(--reefy-success) !important; }
            .bg-success { background-color: var(--reefy-success) !important; }
            .bg-light { background-color: #f8faf9 !important; }

            /* Micro-animations */
            @keyframes pulse-status {
                0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(45, 106, 79, 0.4); }
                70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(45, 106, 79, 0); }
                100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(45, 106, 79, 0); }
            }
            .ripple-status { animation: pulse-status 2s infinite cubic-bezier(0.66, 0, 0, 1); }

            /* Helper for toast animations */
            @keyframes toastSlideIn { from { transform: translateY(-100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
            @keyframes toastSlideOut { to { transform: translateY(-20px); opacity: 0; } }
            
            /* Custom Scrollbar for better UX */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #f1f1f1; }
            ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
            ::-webkit-scrollbar-thumb:hover { background: #555; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="d-flex min-vh-100 bg-light flex-column flex-lg-row">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-grow-1 overflow-hidden d-flex flex-column">
                <!-- Mobile Header (Visible only on mobile) -->
                <header class="d-lg-none bg-white shadow-sm p-3 d-flex justify-content-between align-items-center sticky-top" style="z-index: 1040;">
                    <button class="btn btn-light border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                        <i class="bi bi-list fs-3"></i>
                    </button>
                    <div class="fw-bold fs-4 text-success d-flex align-items-center gap-2">
                        <i class="bi bi-intersect"></i> Reefy
                    </div>
                    <div></div> <!-- Spacer -->
                </header>

                <!-- Page Heading (Desktop/Global Header) -->
                @if (isset($header))
                    <header class="bg-white shadow-sm py-4 px-4 sticky-top d-none d-lg-block" style="z-index: 1000; border-bottom: 1px solid rgba(0,0,0,0.05); backdrop-filter: blur(10px); background: rgba(255,255,255,0.8) !important;">
                        <div class="container-fluid">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Content Area -->
                <main class="container-fluid py-4 px-4 flex-grow-1">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center mb-4 shadow-sm border-0 rounded-4" role="alert">
                            <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                            <div>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm border-0 rounded-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
                            <div>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif


                    {{ $slot }}
                </main>
            </div>
        </div>

        <style>
            /* Layout Adjustments */
            @media (min-width: 992px) {
                main.container-fluid { padding-right: 2rem !important; padding-left: 2rem !important; }
            }
        </style>

        <!-- Notification Permission Modal -->
        <div class="modal fade" id="notificationPermissionModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg relative rounded-4 overflow-hidden">
                     <div class="modal-header bg-success text-white border-0 py-4 justify-content-center flex-column align-items-center">
                        <i class="bi bi-bell-fill display-4 mb-2"></i>
                        <h5 class="modal-title fw-bold">نظام التذكير الذكي</h5>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <p class="lead mb-4 text-muted">لا تفوت مهامك الزراعية!</p>
                        <p class="mb-4 text-start bg-light p-3 rounded-3 border">
                            نحتاج إذنك لإرسال <strong>إشعارات تذكير</strong> لك عند:
                            <br>
                            <i class="bi bi-check-circle-fill text-success ms-2"></i> موعد المهمة المحدد.
                            <br>
                            <i class="bi bi-exclamation-triangle-fill text-warning ms-2"></i> التأخر عن تنفيذ المهمة.
                            <br>
                            <i class="bi bi-info-circle-fill text-info ms-2"></i> إضافة مهام جديدة.
                        </p>
                        <div class="d-grid gap-2">
                             <button type="button" class="btn btn-success btn-lg fw-bold shadow-sm" id="enableNotificationsBtn">
                                <i class="bi bi-bell-fill me-1"></i> السماح بالإشعارات
                            </button>
                             <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">
                                لاحقاً
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <div class="reify-toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050; pointer-events: none;" id="reifyToastContainer"></div>

        <!-- Real-time Notification Polling -->
        @auth
        <script>
            // Sleek Notification Toast Helper
            function showReifyNotification(title, msg, isOverdue = false, tag = '') {
                const container = document.getElementById('reifyToastContainer');
                if (!container) return;

                const toastId = 'toast-' + Date.now();
                const bgColor = isOverdue ? 'rgba(220, 38, 38, 0.95)' : 'rgba(16, 185, 129, 0.95)';
                const icon = isOverdue ? 'bi-exclamation-triangle-fill' : 'bi-stars';
                
                const toastHtml = `
                    <div id="${toastId}" class="toast border-0 shadow-lg mb-3 overflow-hidden rounded-4" role="alert" aria-live="assertive" aria-atomic="true" style="pointer-events: auto; background: ${bgColor}; backdrop-filter: blur(10px); min-width: 320px;">
                        <div class="d-flex align-items-center p-3 text-white">
                            <div class="bg-white bg-opacity-20 rounded-circle p-2 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                <i class="bi ${icon} fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="mb-0 fw-bold ls-tight">${title}</h6>
                                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast" aria-label="Close" style="font-size: 0.7rem;"></button>
                                </div>
                                <div class="small opacity-90 mt-1" style="font-size: 0.8rem; line-height: 1.4;">${msg}</div>
                            </div>
                        </div>
                        <div class="progress" style="height: 3px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar bg-white bg-opacity-50" id="progress-${toastId}" role="progressbar" style="width: 100%;"></div>
                        </div>
                    </div>
                `;
                
                container.insertAdjacentHTML('afterbegin', toastHtml);
                const toastElement = document.getElementById(toastId);
                const progressBar = document.getElementById(`progress-${toastId}`);
                
                const bsToast = new bootstrap.Toast(toastElement, { delay: 8000, autohide: true });
                bsToast.show();

                // Animate progress bar
                progressBar.style.transition = 'width 8s linear';
                setTimeout(() => progressBar.style.width = '0%', 10);
                
                toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());

                // 2. Also show browser notification if permitted
                if ('Notification' in window && Notification.permission === 'granted') {
                    try {
                        new Notification(title, {
                            body: msg,
                            icon: '/favicon.ico',
                            tag: tag || ('reify-' + Date.now()),
                            requireInteraction: true
                        });
                    } catch (e) {
                        console.error('Browser notification failed:', e);
                    }
                }
            }

            // Show notification permission modal on first visit
            if ('Notification' in window) {
                const hasSeenModal = localStorage.getItem('notificationModalShown');
                
                if (!hasSeenModal && Notification.permission === 'default') {
                    const modalEl = document.getElementById('notificationPermissionModal');
                    if (modalEl) {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    }
                    localStorage.setItem('notificationModalShown', 'true');
                }

                document.getElementById('enableNotificationsBtn')?.addEventListener('click', async () => {
                    const permission = await Notification.requestPermission();
                    if (permission === 'granted') {
                        showReifyNotification('✅ تم التفعيل بنجاح!', 'سنرسل لك تذكيرات بمهامك الزراعية في الوقت المحدد.');
                        const modal = bootstrap.Modal.getInstance(document.getElementById('notificationPermissionModal'));
                        if (modal) modal.hide();
                    } else {
                        alert('⚠️ لم يتم السماح بالإشعارات. يمكنك تفعيلها لاحقاً من إعدادات المتصفح.');
                    }
                });
            }

            // Function to check for unread notifications count
            function checkNotifications() {
                fetch('{{ route('notifications.unread') }}')
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.querySelector('.notification-badge');
                        const list = document.getElementById('notificationList');
                        
                        if (badge) {
                            if (data.count > 0) {
                                badge.textContent = data.count > 9 ? '9+' : data.count;
                                badge.style.display = 'inline-flex';
                            } else {
                                badge.style.display = 'none';
                            }
                        }

                        if (list && data.notifications) {
                            if (data.notifications.length > 0) {
                                let html = '';
                                data.notifications.forEach(notif => {
                                    let icon = 'bi-info-circle';
                                    let color = 'text-info';
                                    if(notif.type === 'task_due') { icon = 'bi-clock'; color = 'text-warning'; }
                                    if(notif.type === 'task_overdue') { icon = 'bi-exclamation-triangle'; color = 'text-danger'; }
                                    
                                    html += `
                                        <a href="{{ url('/notifications') }}" class="list-group-item list-group-item-action p-3 border-0 border-bottom">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="bg-light rounded-circle p-2">
                                                    <i class="bi ${icon} ${color}"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-dark small">${notif.title}</div>
                                                    <div class="text-muted small">${notif.message.substring(0, 60)}...</div>
                                                    <div class="text-primary mt-1" style="font-size: 0.7rem;">منذ قليل</div>
                                                </div>
                                            </div>
                                        </a>
                                    `;
                                });
                                list.innerHTML = html;
                            } else if (data.count === 0) {
                                list.innerHTML = `
                                    <div class="p-4 text-center text-muted">
                                        <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
                                        <span class="small">لا توجد إشعارات جديدة</span>
                                    </div>
                                `;
                            }
                        }
                    })
                    .catch(error => console.log('Notification check failed:', error));
            }

            setInterval(checkNotifications, 5000);
            checkNotifications();

            // ========== PRECISE SCHEDULED NOTIFICATIONS ==========
            let scheduledTimers = [];
            const notifiedInSession = new Set(); 

            function scheduleUpcomingTasks() {
                fetch('{{ route('notifications.upcoming') }}')
                    .then(response => response.json())
                    .then(data => {
                        // Clear old timers
                        scheduledTimers.forEach(timer => clearTimeout(timer));
                        scheduledTimers = [];

                        if (data.tasks && data.tasks.length > 0) {
                            // Current server time (synced via PHP timestamp)
                            const nowUnix = Math.floor(Date.now() / 1000);

                            data.tasks.forEach(task => {
                                const reminderUnix = task.reminder_timestamp;
                                const diffSeconds = reminderUnix - nowUnix;
                                const sessionKey = `task_${task.id}_${reminderUnix}`;

                                // Case 1: ALREADY OVERDUE (Time passed)
                                if (diffSeconds < 0) {
                                    if (!notifiedInSession.has(sessionKey + '_overdue')) {
                                        showReifyNotification(
                                            '⚠️ مهمة متأخرة!', 
                                            `أنت متأخر عن المهمة "${task.title}" للمحصول "${task.crop_name}"! موعدها كان (${task.reminder_time}).`,
                                            true,
                                            'overdue-' + task.id
                                        );
                                        notifiedInSession.add(sessionKey + '_overdue');
                                    }
                                }
                                // Case 2: UPCOMING
                                else if (diffSeconds >= 0 && diffSeconds < 48 * 60 * 60) {
                                    const timer = setTimeout(() => {
                                        if (!notifiedInSession.has(sessionKey)) {
                                            showReifyNotification(
                                                '⏰ تذكير بمهمة!', 
                                                `المهمة "${task.title}" للمحصول "${task.crop_name}" موعدها الآن!`,
                                                false,
                                                'reminder-' + task.id
                                            );
                                            notifiedInSession.add(sessionKey);
                                        }
                                        checkNotifications();

                                        // Overdue follow-up 1 hour later
                                        const overdueTimer = setTimeout(() => {
                                            const overdueKey = sessionKey + '_overdue';
                                            if (!notifiedInSession.has(overdueKey)) {
                                                showReifyNotification(
                                                    '⚠️ مهمة متأخرة!', 
                                                    `أنت متأخر عن المهمة "${task.title}" للمحصول "${task.crop_name}"! مرّت ساعة منذ الموعد.`,
                                                    true,
                                                    'overdue-' + task.id
                                                );
                                                notifiedInSession.add(overdueKey);
                                            }
                                        }, 60 * 60 * 1000);
                                        scheduledTimers.push(overdueTimer);
                                    }, diffSeconds * 1000);

                                    scheduledTimers.push(timer);
                                    console.log(`✅ [${task.title}] scheduled in ${diffSeconds}s`);
                                }
                            });
                        }
                    })
                    .catch(error => console.error('Scheduling error:', error));
            }

            scheduleUpcomingTasks();
            setInterval(scheduleUpcomingTasks, 5 * 60 * 1000);
        </script>
        @endauth

        @stack('scripts')
    </body>
</html>
