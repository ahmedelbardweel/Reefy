<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            <i class="bi bi-capsule me-2 text-danger"></i> {{ __('نظام المعالجة والمكافحة') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <!-- Treatment Log -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">سجل المعالجة (أسمدة ومبيدات)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">المحصول</th>
                                <th class="border-0">التاريخ</th>
                                <th class="border-0">النوع</th>
                                <th class="border-0">المادة المستخدمة</th>
                                <th class="border-0">الجرعة</th>
                                <th class="border-0">الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $task->crop->image_url ?? asset('images/crop-placeholder.png') }}" class="rounded-circle me-2" width="40" height="40" alt="{{ $task->crop->name }}">
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
                <div class="card-footer bg-white">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
