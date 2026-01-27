<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold mb-1 text-gray-900 dark:text-white tracking-tight">
                    <i class="bi bi-water text-blue-500 ml-2"></i> {{ __('Smart Irrigation Management') }}
                </h1>
                <p class="text-xs text-gray-500">{{ __('Monitor water consumption and track scheduled irrigation operations') }}</p>
            </div>
            <div class="hidden lg:flex px-3 py-2 border border-gray-200 dark:border-gray-700 items-center gap-2 bg-white dark:bg-gray-800">
                <i class="bi bi-droplet-fill text-blue-500"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('Total Consumption') }}: {{ number_format($totalWater) }} {{ __('L') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Quick Insight -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-600 p-6 shadow-sm border border-blue-700 text-white flex items-center justify-between">
                <div>
                    <h6 class="text-blue-100 text-xs font-bold uppercase mb-1">{{ __('Total Registered Water') }}</h6>
                    <h3 class="text-3xl font-bold">{{ number_format($totalWater) }} <span class="text-sm font-normal">{{ __('L') }}</span></h3>
                </div>
                <i class="bi bi-droplet-half text-4xl opacity-30"></i>
            </div>
            
            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h6 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase mb-1">{{ __('Irrigation Operations') }}</h6>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $tasks->total() }}</h3>
                </div>
                <i class="bi bi-calendar-check text-4xl text-blue-500 opacity-20"></i>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h6 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase mb-1">{{ __('Active Crops') }}</h6>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->crops()->count() }}</h3>
                </div>
                <i class="bi bi-sprout text-4xl text-green-500 opacity-20"></i>
            </div>
        </div>

        <!-- Irrigation Log -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
                <h5 class="font-bold text-sm text-gray-900 dark:text-white uppercase tracking-wider">{{ __('Live Irrigation Log') }}</h5>
                <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300 text-[10px] font-bold">{{ __('Real-time Update') }}</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-[10px] font-bold uppercase">
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">{{ __('Crop') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">{{ __('Operation Date') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 text-center">{{ __('Flow Amount') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 text-center">{{ __('Duration') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 text-center">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($tasks as $task)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 border border-gray-200 dark:border-gray-600 overflow-hidden shrink-0">
                                        <img src="{{ $task->crop->image_url ?? asset('images/crop-placeholder.png') }}" class="w-full h-full object-cover" alt="{{ $task->crop->name }}">
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $task->crop->name }}</div>
                                        <div class="text-[10px] text-gray-500">{{ __('Field') }}: {{ $task->crop->location ?? __('Main') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">
                                {{ $task->due_date->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 text-xs font-bold border border-blue-100 dark:border-blue-800">
                                    {{ $task->water_amount ?? '-' }} {{ __('L') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700 dark:text-gray-300 font-bold">
                                {{ $task->duration ?? '-' }} <span class="text-[10px] font-normal text-gray-500 uppercase">{{ __('min') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($task->status == 'completed')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-bold border border-green-200 dark:border-green-800 leading-none">{{ __('Completed') }}</span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-[10px] font-bold border border-yellow-200 dark:border-yellow-800 leading-none">{{ __('Pending') }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-wind text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-sm text-gray-500 font-bold">{{ __('No irrigation records found yet') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tasks->hasPages())
                <div class="p-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
