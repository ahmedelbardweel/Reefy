<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <div>
                <h1 class="text-xl font-bold mb-1 text-gray-900 dark:text-white tracking-tight">
                    <i class="bi bi-box-seam text-green-600 ml-2"></i> {{ __('Harvest & Production Record') }}
                </h1>
                <p class="text-xs text-gray-500">{{ __('Track actual production quantities and quality levels for crops') }}</p>
            </div>
            <div class="hidden lg:flex px-4 py-2 border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 items-center gap-2">
                <i class="bi bi-graph-up-arrow"></i>
                <span class="text-xs font-bold uppercase tracking-wider">{{ __('Plentiful Production Season') }}</span>
            </div>
        </div>

        <!-- Print-only Header -->
        <div class="hidden print:block text-center border-b-2 border-green-600 pb-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Production & Harvest Report - Reefy') }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ __('Report Date') }}: {{ now()->translatedFormat('d F Y') }}</p>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Production Analytics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 print:grid-cols-2">
            <div class="md:col-span-2 bg-green-600 p-8 border border-green-700 text-white flex items-center justify-between print:bg-white print:text-gray-900 print:border-gray-200">
                <div>
                    <h6 class="text-green-100 text-[10px] font-bold uppercase mb-2">{{ __('Total Registered Production') }}</h6>
                    <h3 class="text-4xl font-bold tracking-tight">{{ number_format($totalYield) }} <span class="text-sm font-normal opacity-80">{{ __('kg/ton') }}</span></h3>
                    <div class="mt-4 flex gap-2">
                        <span class="text-[10px] bg-white/20 px-2 py-0.5 font-bold uppercase">{{ __('Current Season') }}</span>
                    </div>
                </div>
                <i class="bi bi-basket3 text-6xl opacity-20"></i>
            </div>
            
            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 flex flex-col justify-center">
                <h6 class="text-gray-500 dark:text-gray-400 text-[10px] font-bold uppercase mb-1">{{ __('Harvest Operations') }}</h6>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $tasks->count() }}</h3>
                <div class="mt-2 text-[10px] text-green-600 font-bold">+12% {{ __('from last month') }}</div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 border border-gray-200 dark:border-gray-700 flex flex-col justify-center">
                <h6 class="text-gray-500 dark:text-gray-400 text-[10px] font-bold uppercase mb-1">{{ __('Average Quality') }}</h6>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Excellent') }}</h3>
                <div class="mt-2 text-[10px] text-gray-400 font-bold">{{ __('Based on recent reports') }}</div>
            </div>
        </div>

        <!-- Harvest Record -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center whitespace-nowrap overflow-x-auto no-scrollbar">
                <div class="flex items-center gap-4">
                    <h5 class="font-bold text-sm text-gray-900 dark:text-white">{{ __('Detailed Production Log') }}</h5>
                    <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 hidden sm:block"></div>
                    <div class="flex gap-4 text-[10px] font-bold uppercase text-gray-500">
                        <span class="flex items-center gap-1"><i class="bi bi-circle-fill text-green-500 text-[6px]"></i> {{ __('Export') }}</span>
                        <span class="flex items-center gap-1"><i class="bi bi-circle-fill text-blue-500 text-[6px]"></i> {{ __('Local') }}</span>
                    </div>
                </div>
                <div class="flex gap-2 no-print">
                    <button onclick="window.print()" class="bg-white text-gray-900 border border-gray-200 px-4 py-1.5 text-[10px] font-bold hover:bg-gray-50 transition flex items-center gap-2">
                        <i class="bi bi-file-earmark-pdf"></i> {{ __('Export (PDF)') }}
                    </button>
                    <a href="{{ route('farmer.systems.harvesting.export') }}" class="bg-gray-900 text-white px-4 py-1.5 text-[10px] font-bold hover:bg-black transition whitespace-nowrap block">{{ __('Export (CSV)') }}</a>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-[10px] font-bold uppercase">
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">{{ __('Crop') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">{{ __('Harvest Date') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 text-center">{{ __('Registered Quantity') }}</th>
                            <th class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 text-center">{{ __('Quality Grade') }}</th>
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
                                        <div class="text-[10px] text-gray-500">{{ __($task->harvest_unit ?? 'kg') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">
                                {{ $task->updated_at->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-lg font-black text-green-700 dark:text-green-500">
                                    {{ $task->harvest_quantity ?? '-' }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase mr-1">{{ __($task->harvest_unit ?? 'kg') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($task->quality_grade)
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-[10px] font-bold border border-gray-200 dark:border-gray-600">{{ $task->quality_grade }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="flex items-center justify-center gap-1 text-green-600 dark:text-green-500">
                                    <i class="bi bi-patch-check"></i>
                                    <span class="text-[10px] font-bold">{{ __('Harvested') }}</span>
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="bi bi-flower2 text-5xl text-gray-200 mb-3"></i>
                                    <p class="text-sm text-gray-400 font-bold tracking-widest uppercase">{{ __('Waiting for first harvest fruits') }}</p>
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

    @push('scripts')
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .py-6 { padding-top: 0 !important; }
            .shadow-sm { shadow: none !important; border: 1px solid #eee !important; }
            nav { display: none !important; }
            .bg-green-600 { background-color: transparent !important; color: black !important; border: 2px solid #059669 !important; }
            .text-green-100 { color: #666 !important; }
            table { border-collapse: collapse !important; width: 100% !important; }
            th, td { border: 1px solid #eee !important; padding: 12px !important; }
            tr { page-break-inside: avoid !important; }
        }
    </style>
    @endpush
</x-app-layout>
