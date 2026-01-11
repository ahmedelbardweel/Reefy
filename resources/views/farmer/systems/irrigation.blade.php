<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            <i class="bi bi-water me-2 text-primary"></i> {{ __('نظام إدارة الري') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <!-- Stats Card -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="display-4 me-3"><i class="bi bi-droplet-fill"></i></div>
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1">إجمالي المياه المستهلكة</h6>
                                <h3 class="mb-0 fw-bold">{{ number_format($totalWater) }} لتر</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Irrigation Log -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">سجل عمليات الري</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">المحصول</th>
                                <th class="border-0">التاريخ</th>
                                <th class="border-0">كمية المياه</th>
                                <th class="border-0">مدة الري</th>
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
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        {{ $task->water_amount ?? '-' }} لتر
                                    </span>
                                </td>
                                <td>{{ $task->duration ?? '-' }} دقيقة</td>
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
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                    لا توجد سجلات ري حتى الآن
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
