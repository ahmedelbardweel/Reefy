<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0" style="color: var(--heading-color);">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm border-0" style="background: var(--bg-secondary) !important; border: 1px solid var(--border-color) !important;">
            <div class="card-body">
                <h5 class="card-title" style="color: var(--heading-color);">{{ __("Welcome Admin!") }}</h5>
                <p class="card-text" style="color: var(--text-secondary);">{{ __("You're logged in as an Administrator.") }}</p>
                
                <div class="alert mt-3" style="background-color: rgba(0, 174, 239, 0.1); color: #0056b3; border: 1px solid rgba(0, 174, 239, 0.2);">
                    <strong>Note:</strong> Admin features are coming soon.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
