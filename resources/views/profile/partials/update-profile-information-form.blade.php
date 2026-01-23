<section>
    <header>
        <h2 class="text-lg font-medium" style="color: var(--heading-color);">
            {{ __('معلومات الحساب') }}
        </h2>

        <p class="mt-1 text-sm" style="color: var(--text-secondary);">
            {{ __("قم بتحديث معلومات ملفك الشخصي وعنوان بريدك الإلكتروني.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Images Section -->
        <div class="mb-4">
            <div class="row g-4">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold d-block" style="color: var(--heading-color);">الصورة الشخصية</label>
                    <div class="d-flex align-items-center gap-4 p-3 border" style="background: var(--bg-primary);">
                        <div class="avatar-preview border" style="width: 100px; height: 100px; overflow: hidden; background: var(--bg-secondary);">
                            <img id="avatar-preview-img" src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=84cc16&color=fff' }}" class="w-100 h-100 object-fit-cover">
                        </div>
                        <div class="flex-grow-1">
                            <input type="file" name="avatar" id="avatar-input" class="form-control form-control-sm border-0 bg-light" accept="image/*" onchange="previewImage(this, 'avatar-preview-img')">
                            <div class="small text-muted mt-2">يوصى بـ 400x400 بكسل. الحد الأقصى 2MB</div>
                        </div>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold" style="color: var(--heading-color);">صورة الغلاف</label>
                    <div class="d-flex flex-column gap-3 p-3 border" style="background: var(--bg-primary);">
                        <div class="cover-preview border" style="width: 100%; height: 150px; overflow: hidden; background: var(--bg-secondary);">
                             <img id="cover-preview-img" src="{{ $user->cover_image ? asset($user->cover_image) : '' }}" class="w-100 h-100 {{ $user->cover_image ? 'object-fit-cover' : 'd-none' }}">
                             @if(!$user->cover_image)
                                <div id="cover-placeholder" class="w-100 h-100 d-flex align-items-center justify-content-center text-muted small">لا توجد صورة غلاف حالياً</div>
                             @endif
                        </div>
                        <input type="file" name="cover_image" id="cover-input" class="form-control form-control-sm border-0 bg-light" accept="image/*" onchange="previewImage(this, 'cover-preview-img', 'cover-placeholder')">
                        <div class="small text-muted">يوصى بـ 1200x400 بكسل. الحد الأقصى 5MB</div>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('cover_image')" />
                </div>
            </div>
        </div>

        <div class="mb-4">
            <x-input-label for="name" :value="__('الاسم الكامل')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-4">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2" style="color: var(--text-primary);">
                        {{ __('بريدك الإلكتروني غير مفعل.') }}

                        <button form="send-verification" class="underline text-sm rounded-0 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" style="color: var(--text-secondary);">
                            {{ __('اضغط هنا لإعادة إرسال رابط التفعيل.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('تم إرسال رابط تفعيل جديد إلى بريدك الإلكتروني.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-3 border-top">
            <x-primary-button class="btn btn-success px-5">{{ __('حفظ التغييرات') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success"
                ><i class="bi bi-check-circle-fill me-1"></i> {{ __('تم الحفظ.') }}</p>
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
                    preview.classList.remove('d-none');
                    if (placeholderId) {
                        document.getElementById(placeholderId).classList.add('d-none');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</section>
