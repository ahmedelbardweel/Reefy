<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0" style="color: var(--heading-color);">
            <i class="bi bi-box-seam me-2 text-success"></i> {{ __('سجل الحصاد والإنتاج') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <!-- Stats Card -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="display-4 me-3"><i class="bi bi-basket-fill"></i></div>
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1">إجمالي الإنتاج المسجل</h6>
                                <h3 class="mb-0 fw-bold">{{ number_format($totalYield) }} كجم/طن</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Harvest Log -->
        <div class="card shadow-sm border-0">
            <div class="card-header py-3" style="background-color: var(--bg-secondary); border-bottom: 1px solid var(--border-color);">
                <h5 class="mb-0 fw-bold" style="color: var(--heading-color);">عمليات الحصاد السابقة</h5>
            </div>
            <div class="card-body p-0" style="background-color: var(--bg-secondary);">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="color: var(--text-primary);">
                        <thead style="background-color: var(--bg-primary);">
                            <tr>
                                <th class="border-0" style="color: var(--text-secondary);">المحصول</th>
                                <th class="border-0" style="color: var(--text-secondary);">تاريخ الحصاد</th>
                                <th class="border-0" style="color: var(--text-secondary);">الكمية</th>
                                <th class="border-0" style="color: var(--text-secondary);">الوحدة</th>
                                <th class="border-0" style="color: var(--text-secondary);">الجودة</th>
                                <th class="border-0" style="color: var(--text-secondary);">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $task->crop->image_url ?? asset('images/crop-placeholder.png') }}" class="rounded-0 me-2" width="40" height="40" alt="{{ $task->crop->name }}">
                                        <span class="fw-bold">{{ $task->crop->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $task->updated_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="fw-bold text-success fs-5">
                                        {{ $task->harvest_quantity ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $task->harvest_unit ?? 'كجم' }}</td>
                                <td>
                                    @if($task->quality_grade)
                                        <span class="badge bg-light text-dark border">{{ $task->quality_grade }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success">تم الحصاد</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-flower1 fs-1 d-block mb-3 opacity-50"></i>
                                    لا توجد عمليات حصاد مسجلة حتى الآن
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($tasks->hasPages())
                <div class="card-footer" style="background-color: var(--bg-secondary); border-top: 1px solid var(--border-color);">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
