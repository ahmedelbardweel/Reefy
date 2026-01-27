<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Images Section -->
        <div class="space-y-6">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Profile Photo') }}</label>
                    <div class="flex items-center gap-6 p-4 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 rounded-none">
                        <div class="w-24 h-24 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shrink-0 overflow-hidden">
                            <img id="avatar-preview-img" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=16a34a&color=fff' }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <input type="file" name="avatar" id="avatar-input" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-400" accept="image/*" onchange="previewImage(this, 'avatar-preview-img')">
                            <p class="text-[10px] text-gray-500 mt-2 font-medium">{{ __('Recommended 400x400 pixels. Max 2MB') }}</p>
                        </div>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Cover Image') }}</label>
                    <div class="p-4 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 rounded-none">
                        <div class="w-full h-32 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 mb-4 overflow-hidden relative">
                             <img id="cover-preview-img" src="{{ $user->cover_image ? asset('storage/' . $user->cover_image) : '' }}" class="w-full h-full object-cover {{ $user->cover_image ? '' : 'hidden' }}">
                             @if(!$user->cover_image)
                                <div id="cover-placeholder" class="absolute inset-0 flex items-center justify-center text-xs text-gray-400 font-medium italic">{{ __('No cover image currently') }}</div>
                             @endif
                        </div>
                        <input type="file" name="cover_image" id="cover-input" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/30 dark:file:text-green-400" accept="image/*" onchange="previewImage(this, 'cover-preview-img', 'cover-placeholder')">
                        <p class="text-[10px] text-gray-500 mt-2 font-medium">{{ __('Recommended 1200x400 pixels. Max 5MB') }}</p>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                </div>
            </div>
        </div>

        <div class="mb-4">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">
            <x-primary-button class="bg-green-600 hover:bg-green-700 px-8">{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-bold flex items-center gap-1"
                ><i class="bi bi-check-circle-fill"></i> {{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        function previewImage(input, previewId, placeholderId = null) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholderId) {
                        document.getElementById(placeholderId).classList.add('hidden');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</section>
