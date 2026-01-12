<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold text-dark mb-0">
                <i class="bi bi-bell"></i> {{ __('Notifications (الإشعارات)') }}
            </h2>
            @if($notifications->where('is_read', false)->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-check-all"></i> Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-4">
        @if($notifications->isEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-bell-slash display-1 text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد إشعارات</h5>
                    <p class="text-muted">All notifications will appear here.</p>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        <div class="list-group-item {{ !$notification->is_read ? 'bg-light border-start border-primary border-3' : '' }}">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    @if($notification->type === 'task_due')
                                        <div class="bg-warning bg-opacity-10 p-2" style="border-radius: 0;">
                                            <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
                                        </div>
                                    @elseif($notification->type === 'task_overdue')
                                        <div class="bg-danger bg-opacity-10 p-2" style="border-radius: 0;">
                                            <i class="bi bi-clock-history text-danger fs-4"></i>
                                        </div>
                                    @else
                                        <div class="bg-info bg-opacity-10 p-2" style="border-radius: 0;">
                                            <i class="bi bi-info-circle-fill text-info fs-4"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0 fw-bold">{{ $notification->title }}</h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-2 text-secondary">{{ $notification->message }}</p>
                                    @if($notification->task)
                                        <small class="text-muted">
                                            <i class="bi bi-tag"></i> {{ $notification->task->title }} 
                                            - {{ $notification->task->due_date->format('Y-m-d') }}
                                        </small>
                                    @endif
                                </div>
                                @if(!$notification->is_read)
                                    <div class="flex-shrink-0 ms-2">
                                        <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Mark as Read">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
