<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            {{ __('Complete Verification') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('farmer.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="alert alert-info mb-4">
                                Please provide your official documents to verify your status as a farmer.
                            </div>

                            <div class="mb-3">
                                <x-input-label for="national_id" :value="__('National ID Number')" />
                                <x-text-input id="national_id" class="form-control" type="text" name="national_id" :value="$profile->national_id" required />
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-input-label for="country" :value="__('Country (الدولة)')" />
                                    <x-text-input id="country" class="form-control" type="text" name="country" :value="$profile->country" placeholder="e.g. Egypt" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <x-input-label for="city" :value="__('City (المدينة)')" />
                                    <x-text-input id="city" class="form-control" type="text" name="city" :value="$profile->city" placeholder="e.g. Tanta" />
                                </div>
                            </div>

                            <div class="mb-3">
                                <x-input-label for="national_id_image" :value="__('National ID Image')" />
                                <input id="national_id_image" type="file" name="national_id_image" class="form-control">
                            </div>

                            <div class="mb-3">
                                <x-input-label for="farm_document_image" :value="__('Farm Ownership Document')" />
                                <input id="farm_document_image" type="file" name="farm_document_image" class="form-control">
                            </div>

                            <div class="mb-3">
                                <x-input-label for="bio" :value="__('Bio / About Farm')" />
                                <textarea id="bio" name="bio" class="form-control" rows="3">{{ $profile->bio }}</textarea>
                            </div>

                            <div class="d-grid mt-4">
                                <x-primary-button class="btn btn-primary">
                                    {{ __('Submit for Verification') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
