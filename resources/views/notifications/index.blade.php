<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-bell text-green-600"></i> {{ __('Notifications') }}
            </h2>
            
            @if($notifications->where('is_read', false)->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-50 hover:bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800 rounded-lg text-sm font-bold transition-colors">
                        <i class="bi bi-check2-all ml-2"></i> {{ __('Mark all as read') }}
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            @if($notifications->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 dark:bg-gray-700/50 rounded-full mb-6">
                        <i class="bi bi-bell-slash text-4xl text-gray-300 dark:text-gray-600"></i>
                    </div>
                    <h5 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('No notifications currently') }}</h5>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('All alerts related to your tasks and consultations will appear here.') }}</p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($notifications as $notification)
                            <div class="relative group p-4 sm:p-6 transition-colors {{ !$notification->is_read ? 'bg-green-50/30 dark:bg-green-900/10' : 'hover:bg-gray-50 dark:hover:bg-gray-700/30' }}">
                                @if(!$notification->is_read)
                                    <div class="absolute top-0 right-0 bottom-0 w-1 bg-green-500"></div>
                                @endif

                                <div class="flex items-start gap-4">
                                    <!-- Icon -->
                                    <div class="shrink-0">
                                        @php
                                            $iconColor = 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400';
                                            $icon = 'bi-info-circle-fill';
                                            
                                            switch($notification->type) {
                                                case 'task_due':
                                                    $iconColor = 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400';
                                                    $icon = 'bi-exclamation-triangle-fill';
                                                    break;
                                                case 'task_overdue':
                                                    $iconColor = 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400';
                                                    $icon = 'bi-clock-history';
                                                    break;
                                                case 'advice':
                                                    $iconColor = 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400';
                                                    $icon = 'bi-patch-check-fill';
                                                    break;
                                            }
                                        @endphp
                                        <div class="w-12 h-12 flex items-center justify-center rounded-xl {{ $iconColor }}">
                                            <i class="bi {{ $icon }} text-xl"></i>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-1 gap-2">
                                            <h6 class="text-base font-bold text-gray-900 dark:text-white truncate">
                                                {{ $notification->title }}
                                            </h6>
                                            <span class="shrink-0 text-xs text-gray-500 whitespace-nowrap">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-3">
                                            {{ $notification->message }}
                                        </p>

                                        @if($notification->task)
                                            <div class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-700 dark:text-gray-300 gap-1.5">
                                                <i class="bi bi-tag"></i> 
                                                <span>{{ $notification->task->title }}</span>
                                                <span class="mx-1 text-gray-300 dark:text-gray-600">•</span>
                                                <span>{{ $notification->task->due_date->format('Y-m-d') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if(!$notification->is_read)
                                        <div class="shrink-0 self-center">
                                            <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="p-2 text-gray-400 hover:text-green-600 transition-colors" title="{{ __('Mark as read') }}">
                                                    <i class="bi bi-check2 text-2xl"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
