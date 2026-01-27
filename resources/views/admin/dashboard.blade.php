<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0" style="color: var(--heading-color);">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h5 class="text-xl font-bold mb-2">{{ __("Welcome Admin!") }}</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __("You're logged in as an Administrator.") }}</p>
                    
                    <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 p-4 border border-blue-100 dark:border-blue-800">
                        <strong>Note:</strong> Admin features are coming soon.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
