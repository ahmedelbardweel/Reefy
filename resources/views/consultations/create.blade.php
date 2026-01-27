<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold mb-0" style="color: var(--heading-color);">
            {{ __('Request New Consultation') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-0 p-4" style="background-color: var(--bg-secondary);">
                    <form action="{{ route('consultations.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: var(--heading-color);">{{ __('Subject') }}</label>
                            <input type="text" name="subject" class="form-control" placeholder="{{ __('e.g., Tomato leaf wilting') }}" style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color);" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--heading-color);">{{ __('Category') }}</label>
                                <select name="category" class="form-select" style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color);" required>
                                    <option value="">{{ __('Select a category...') }}</option>
                                    <option value="Irrigation">{{ __('Irrigation') }}</option>
                                    <option value="Pests & Diseases">{{ __('Pests & Diseases') }}</option>
                                    <option value="Fertilization">{{ __('Fertilization') }}</option>
                                    <option value="Soil">{{ __('Soil') }}</option>
                                    <option value="Other">{{ __('Other') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--heading-color);">{{ __('Related Crop (Optional)') }}</label>
                                <select name="crop_id" class="form-select" style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color);">
                                    <option value="">{{ __('No specific crop') }}</option>
                                    @foreach($crops as $crop)
                                        <option value="{{ $crop->id }}">{{ $crop->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: var(--heading-color);">{{ __('Your Question Detail') }}</label>
                            <textarea name="question" class="form-control" rows="6" placeholder="{{ __('Describe the problem clearly, e.g., when it appears, leaf color, currently used fertilizer...') }}" style="background-color: var(--bg-primary); color: var(--text-primary); border-color: var(--border-color);" required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg rounded-0 fw-bold">
                                {{ __('Send Request to Expert') }}
                            </button>
                            <a href="{{ route('consultations.index') }}" class="btn btn-link text-decoration-none" style="color: var(--text-secondary);">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
