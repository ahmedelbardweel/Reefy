<x-app-layout>
    <div class="container py-5">
        <div class="row mb-5 align-items-center justify-content-center text-center">
            <div class="col-lg-8">
                <i class="bi bi-person-check-fill text-success" style="font-size: 4rem;"></i>
                <h1 class="fw-bold h2 mt-3 mb-2">Welcome back, {{ Auth::user()->name }}</h1>
                <p class="text-muted">You are logged in to Reefy.</p>
                
                @if(Auth::user()->role === 'farmer')
                    <a href="{{ route('farmer.dashboard') }}" class="btn btn-success rounded-pill px-4 mt-3">
                        Go to Farmer Dashboard
                    </a>
                @elseif(Auth::user()->role === 'expert')
                    <a href="{{ route('expert.dashboard') }}" class="btn btn-success rounded-pill px-4 mt-3">
                        Go to Expert Dashboard
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
