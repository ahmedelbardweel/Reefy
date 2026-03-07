<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Profile Header -->
                <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="background: var(--bg-secondary) !important; border: 1px solid var(--border-color) !important;">
                    <div class="bg-success" style="height: 120px;"></div>
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-end" style="margin-top: -80px;">
                            <div class="p-1 rounded-0 shadow-sm" style="background: var(--bg-secondary) !important;">
                                <div class="rounded-0 d-flex align-items-center justify-content-center text-success fw-bold display-4" style="width: 120px; height: 120px; background: var(--bg-primary) !important;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h2 class="fw-bold mb-1" style="color: var(--heading-color) !important;">
                                {{ $user->name }} 
                                <i class="bi bi-patch-check-fill text-primary small" title="مزارع موثوق"></i>
                            </h2>
                            <p class="text-muted mb-2" style="color: var(--text-secondary) !important;">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> 
                                {{ $user->farmerProfile->city ?? 'غير محدد' }}، {{ $user->farmerProfile->country ?? 'فلسطين' }}
                            </p>
                            @if($user->farmerProfile->bio)
                                <p class="lead small" style="color: var(--text-primary) !important;">{{ $user->farmerProfile->bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Farm Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100" style="background: var(--bg-secondary) !important; border: 1px solid var(--border-color) !important;">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-flower1 text-success fs-1 mb-2"></i>
                                <h3 class="fw-bold mb-0" style="color: var(--heading-color) !important;">{{ $user->crops->count() }}</h3>
                                <small class="text-muted">محاصيل</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100" style="background: var(--bg-secondary) !important; border: 1px solid var(--border-color) !important;">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-calendar-check text-primary fs-1 mb-2"></i>
                                <h3 class="fw-bold mb-0" style="color: var(--heading-color) !important;">{{ $user->created_at->format('Y') }}</h3>
                                <small class="text-muted">عضو منذ</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Crops Showcase -->
                <h5 class="fw-bold mb-3">المحاصيل المزروعة</h5>
                <div class="row g-3">
                    @forelse($user->crops as $crop)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="background: var(--bg-secondary) !important; border: 1px solid var(--border-color) !important;">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="p-3 rounded-0" style="background: var(--bg-primary) !important;">
                                        <i class="bi bi-tree text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: var(--heading-color) !important;">{{ $crop->name }}</h6>
                                        <small class="text-muted" style="color: var(--text-secondary) !important;">{{ $crop->type }} | {{ $crop->area }} فدان</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted text-center py-4 rounded-0" style="background: var(--bg-primary) !important;">لا توجد محاصيل مسجلة حالياً.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
