<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0" style="color: var(--heading-color);">
            <i class="bi bi-capsule me-2 text-danger"></i> {{ __('نظام المعالجة والمكافحة') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <!-- Treatment Log -->
        <div class="card shadow-sm border-0">
            <div class="card-header py-3" style="background-color: var(--bg-secondary); border-bottom: 1px solid var(--border-color);">
                <h5 class="mb-0 fw-bold" style="color: var(--heading-color);">سجل المعالجة (أسمدة ومبيدات)</h5>
            </div>
            <div class="card-body p-0" style="background-color: var(--bg-secondary);">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="color: var(--text-primary);">
                        <thead style="background-color: var(--bg-primary);">
                            <tr>
                                <th class="border-0" style="color: var(--text-secondary);">المحصول</th>
                                <th class="border-0" style="color: var(--text-secondary);">التاريخ</th>
                                <th class="border-0" style="color: var(--text-secondary);">النوع</th>
                                <th class="border-0" style="color: var(--text-secondary);">المادة المستخدمة</th>
                                <th class="border-0" style="color: var(--text-secondary);">الجرعة</th>
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
                                <td>{{ $task->due_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($task->type == 'fertilizer')
                                        <span class="badge bg-success bg-opacity-10 text-success">تسميد</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger">مكافحة آفات</span>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $task->material_name ?? '-' }}</td>
                                <td>{{ $task->dosage ?? '-' }}</td>
                                <td>
                                    @if($task->status == 'completed')
                                        <span class="badge bg-success">مكتملة</span>
                                    @else
                                        <span class="badge bg-warning text-dark">قيد الانتظار</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-capsule fs-1 d-block mb-3 opacity-50"></i>
                                    لا توجد سجلات معالجة حتى الآن
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
