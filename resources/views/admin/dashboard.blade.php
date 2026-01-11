<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">{{ __("Welcome Admin!") }}</h5>
                <p class="card-text text-muted">{{ __("You're logged in as an Administrator.") }}</p>
                
                <div class="alert alert-info mt-3">
                    <strong>Note:</strong> Admin features are coming soon.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
