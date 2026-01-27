<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold mb-1 text-gray-900 dark:text-white tracking-tight">
                    <i class="bi bi-capsule text-red-500 ml-2"></i> {{ __('Preventive Treatment Center') }}
                </h1>
                <p class="text-xs text-gray-500">{{ __('Log of scheduled and implemented fertilizer and pesticide use') }}</p>
            </div>
            <div class="hidden lg:flex px-3 py-2 border border-gray-200 dark:border-gray-700 items-center gap-2 bg-white dark:bg-gray-800">
                <i class="bi bi-shield-check text-green-600"></i>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('Control System Active') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Dashboard Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-900 p-6 border border-gray-800 text-white flex items-center justify-between">
                <div>
                    <h6 class="text-gray-400 text-[10px] font-bold uppercase mb-1">{{ __('Total Executed Operations') }}</h6>
                    <h3 class="text-3xl font-bold">{{ $tasks->where('status', 'completed')->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-500/10 flex items-center justify-center border border-green-500/30">
                    <i class="bi bi-check-all text-2xl text-green-500"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h6 class="text-gray-500 dark:text-gray-400 text-[10px] font-bold uppercase mb-1">{{ __('Pending Operations') }}</h6>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $tasks->where('status', 'pending')->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-500/10 flex items-center justify-center border border-yellow-500/30">
                    <i class="bi bi-hourglass-split text-2xl text-yellow-500"></i>
                </div>
            </div>
        </div>

        <!-- Treatment Log -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
                <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Detailed Treatment Log') }}</h5>
                <div class="flex gap-2">
                    <div class="w-2 h-2 bg-green-500"></div>
                    <div class="w-2 h-2 bg-red-500"></div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-[10px] font-bold uppercase">
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">{{ __('Target Crop') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">{{ __('Date') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">{{ __('Treatment Type') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">{{ __('Material & Dosage') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 text-center">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($tasks as $task)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 flex items-center justify-center">
                                        <i class="bi bi-box-seam text-gray-400"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $task->crop->name }}</div>
                                        <div class="text-[10px] text-gray-500">{{ __('Growth Area') }}: {{ __('Greenhouse 01') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium whitespace-nowrap">
                                {{ $task->due_date->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($task->type == 'fertilizer')
                                    <span class="px-3 py-1 bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400 text-[10px] font-bold border border-green-100 dark:border-green-800">{{ __('Regular Fertilization') }}</span>
                                @else
                                    <span class="px-3 py-1 bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400 text-[10px] font-bold border border-red-100 dark:border-red-800">{{ __('Pest Control') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $task->material_name ?? __('Not Specified') }}</div>
                                <div class="text-[10px] text-gray-500">{{ __('Approved Dosage') }}: {{ $task->dosage ?? __('As instructed') }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($task->status == 'completed')
                                    <div class="flex items-center justify-center gap-1 text-green-600 dark:text-green-500">
                                        <i class="bi bi-check-circle-fill text-xs"></i>
                                        <span class="text-[10px] font-bold">{{ __('Completed') }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center gap-1 text-yellow-600 dark:text-yellow-500 font-bold">
                                        <i class="bi bi-clock-history text-xs"></i>
                                        <span class="text-[10px]">{{ __('Waiting for execution') }}</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-slash-circle text-4xl text-gray-200 mb-2"></i>
                                    <p class="text-sm text-gray-500 font-bold">{{ __('No treatment records found') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tasks->hasPages())
                <div class="p-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 uppercase">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
