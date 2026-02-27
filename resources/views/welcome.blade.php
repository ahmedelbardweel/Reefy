<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
      class="scroll-smooth">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title> {{ __('Reefy | Your Smart Agriculture') }} </title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>

/* ── HERO SLIDER ───────────────────────────────── */
.hero-slide {
    position: absolute; inset: 0;
    opacity: 0;
    transition: opacity 1.2s ease-in-out;
}
.hero-slide.active { opacity: 1; }

/* ── NAVBAR ────────────────────────────────────── */
nav {
    opacity: 0;
    animation: navIn .55s .1s ease forwards;
}
@keyframes navIn {
    from { opacity:0; transform:translateY(-10px); }
    to   { opacity:1; transform:translateY(0); }
}
.nl { position:relative; padding-bottom:2px; }
.nl::after {
    content:'';
    position:absolute; bottom:-2px; left:0;
    width:0; height:1.5px;
    background:#4ade80;
    transition:width .3s ease;
}
.nl:hover::after { width:100%; }

/* ── HERO TEXT: lines slide up ─────────────────── */
.h-line { overflow:hidden; display:block; }
.h-line span {
    display:block;
    opacity:0; transform:translateY(100%);
    animation:lineUp .75s cubic-bezier(.16,1,.3,1) forwards;
}
.h-line:nth-child(1) span { animation-delay:.3s; }
.h-line:nth-child(2) span { animation-delay:.46s; }
.hero-sub  { opacity:0; animation:fade .7s .68s ease forwards; }
.hero-btn  { opacity:0; animation:fade .7s .88s ease forwards; }
@keyframes lineUp { to{ opacity:1; transform:translateY(0); } }
@keyframes fade   { to{ opacity:1; } }

/* ── CTA BUTTON: sheen sweep ───────────────────── */
.btn-g {
    position:relative; overflow:hidden;
    transition:transform .2s ease;
}
.btn-g::before {
    content:'';
    position:absolute; top:0; left:-110%;
    width:55%; height:100%;
    background:rgba(255,255,255,.13);
    transform:skewX(-18deg);
    transition:left .42s ease;
}
.btn-g:hover::before { left:165%; }
.btn-g:hover  { transform:translateY(-2px); }
.btn-g:active { transform:translateY(0); }

/* ── SECTION ACCENT LINE ───────────────────────── */
.a-line {
    display:block; height:2px; width:0;
    background:linear-gradient(90deg,#16a34a,#86efac);
    border-radius:2px; margin:10px auto 0;
    transition:width .75s cubic-bezier(.16,1,.3,1);
}
.a-line.on { width:44px; }

/* ── SERVICE CARDS ─────────────────────────────── */
.s-card {
    border:1px solid transparent;
    transition:
        transform .35s cubic-bezier(.16,1,.3,1),
        box-shadow .35s ease,
        border-color .35s ease;
}
.s-card:hover {
    transform:translateY(-6px);
    box-shadow:0 16px 36px rgba(22,163,74,.1);
    border-color:rgba(22,163,74,.2);
}
.s-card img { transition:transform .5s cubic-bezier(.16,1,.3,1); }
.s-card:hover img { transform:scale(1.05); }

/* ── POST CARDS ────────────────────────────────── */
.p-card {
    transition:
        transform .35s cubic-bezier(.16,1,.3,1),
        box-shadow .35s ease;
}
.p-card:hover {
    transform:translateY(-5px);
    box-shadow:0 12px 32px rgba(0,0,0,.09);
}
.act { transition:color .2s ease, transform .2s ease; }
.act:hover { color:#16a34a; transform:scale(1.1); }

/* ── ABOUT IMAGE ───────────────────────────────── */
.ab-img img { transition:transform .55s cubic-bezier(.16,1,.3,1); }
.ab-img:hover img { transform:scale(1.03); }

/* ── STAT BADGE ────────────────────────────────── */
.s-badge { transition:transform .3s ease, box-shadow .3s ease; }
.s-badge:hover {
    transform:scale(1.05) rotate(-1deg);
    box-shadow:0 10px 28px rgba(22,163,74,.32);
}

/* ── VALUE CARDS ───────────────────────────────── */
.v-card { transition:transform .28s ease, box-shadow .28s ease; }
.v-card:hover {
    transform:translateY(-3px);
    box-shadow:0 8px 22px rgba(22,163,74,.1);
}

/* ── STAGGER REVEAL ────────────────────────────── */
[data-sr] {
    opacity:0; transform:translateY(22px);
    transition:opacity .5s ease, transform .5s cubic-bezier(.16,1,.3,1);
}
[data-sr].on { opacity:1; transform:translateY(0); }

/* ── SCROLL CARET ──────────────────────────────── */
.caret {
    position:absolute; bottom:26px; left:50%;
    transform:translateX(-50%); z-index:20;
    display:flex; flex-direction:column; align-items:center; gap:4px;
    opacity:.55; animation:caretBob 2s ease-in-out infinite;
}
.c-line {
    width:1px; height:30px;
    background:linear-gradient(to bottom,transparent,white);
}
.c-dot {
    width:4px; height:4px; border-radius:50%; background:white;
    animation:dotPulse 2s ease-in-out infinite;
}
@keyframes caretBob {
    0%,100%{ transform:translateX(-50%) translateY(0); }
    50%     { transform:translateX(-50%) translateY(5px); }
}
@keyframes dotPulse {
    0%,100%{ opacity:.35; } 50%{ opacity:1; }
}

/* ── FOOTER SHEEN ──────────────────────────────── */
footer { position:relative; overflow:hidden; }
footer::after {
    content:'';
    position:absolute; inset:0;
    background:linear-gradient(110deg,transparent 0%,rgba(255,255,255,.05) 50%,transparent 100%);
    background-size:200% 100%;
    animation:sheen 6s linear infinite;
}
@keyframes sheen {
    from{ background-position:-200% 0; }
    to  { background-position: 200% 0; }
}

</style>

</head>

<body class="font-[Cairo] bg-white text-gray-900">


<!-- ================= HEADER ================= -->
<nav class="fixed top-0 w-full z-50 bg-black/50 backdrop-blur-md">
<div class="max-w-7xl mx-auto px-6">
<div class="flex justify-between items-center h-20">

<a href="#" class="nl text-green-400 font-bold text-3xl">{{ __('Reefy') }}</a>

<div class="hidden md:flex items-center gap-8 text-white font-semibold">
<a href="#about"     class="nl hover:text-green-400 transition">{{ __('About Reefy') }}</a>
<a href="#services"  class="nl hover:text-green-400 transition">{{ __('Services') }}</a>
<a href="#community" class="nl hover:text-green-400 transition">{{ __('Community') }}</a>
<a href="#consultations" class="nl hover:text-green-400 transition px-20">{{ __('Consultations') }}</a>

@auth
<a href="/dashboard" class="text-green-400">{{ __('Dashboard') }}</a>
@else
<a href="{{ route('login') }}" class="nl hover:text-green-400 px-2">{{ __('Login') }}</a>
<a href="{{ route('register') }}" class="btn-g bg-green-600 hover:bg-green-700 px-2 py-2 rounded-lg">
{{ __('Register') }}</a>
@endauth

@php $currentLang = app()->getLocale(); @endphp
@if($currentLang === 'ar')
<a href="{{ route('lang.switch','en') }}" class="px-4 py-2 bg-white text-green-700 rounded-full hover:bg-gray-100 transition">EN</a>
@else
<a href="{{ route('lang.switch','ar') }}" class="px-4 py-2 bg-white text-green-700 rounded-full hover:bg-gray-100 transition">AR</a>
@endif
</div>

</div>
</div>
</nav>


<!-- ================= HERO ================= -->
<section class="relative h-screen overflow-hidden">

<div class="hero-slide active"><img src="/images/welcome/hero/hero-1.png" class="w-full h-full object-cover"></div>
<div class="hero-slide"><img src="/images/welcome/hero/hero-2.png" class="w-full h-full object-cover"></div>
<div class="hero-slide"><img src="/images/welcome/hero/hero-3.png" class="w-full h-full object-cover"></div>

<div class="absolute inset-0 bg-black/60"></div>

<div class="relative z-10 h-full flex items-center justify-center text-center text-white px-4">
<div class="max-w-4xl">

<h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
<span class="h-line py-4"><span>{{ __('Reefy — Your Smart') }}</span></span>
<span class="h-line py-5"><span>{{ __('Agriculture Platform') }}</span></span>
</h1>

<p class="hero-sub text-xl md:text-2xl mb-8 leading-relaxed">
{{ __('A complete agricultural platform to manage your crops efficiently, communicate with experts, and share experiences with a modern agricultural community.') }}
</p>

<a href="{{ route('register') }}"
   class="hero-btn btn-g inline-block bg-green-600 px-8 py-4 rounded-lg text-lg hover:bg-green-700 transition">
{{ __('Start your journey now') }}
</a>

</div>
</div>

<div class="caret"><div class="c-line"></div><div class="c-dot"></div></div>

</section>


<!-- ================= ABOUT ================= -->
<section id="about" class="py-10 bg-white">
<div class="max-w-7xl mx-auto px-6">

    <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-bold mb-2" data-aos="fade-down">{{ __('About Reefy') }}</h2>
        <em class="a-line not-italic"></em>
        <p class="text-gray-600 max-w-2xl mx-auto text-lg leading-relaxed mt-6" data-aos="fade-up" data-aos-delay="150">
            {{ __('Reefy is a smart agricultural platform designed to empower farmers with modern tools, expert knowledge, and a collaborative community.') }}
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-16 items-center">

        <div class="ab-img relative" data-aos="fade-right" data-aos-delay="100">
            <img src="https://images.unsplash.com/photo-1523348830708-15d4a09cfac2?auto=format&fit=crop&q=80&w=800" class="rounded-2xl shadow-2xl h-[500px] w-full object-cover">
            <div class="s-badge absolute -bottom-6 -right-6 bg-green-600 text-white px-6 py-4 rounded-xl shadow-lg">
                <span class="text-2xl font-bold">+5000</span>
                <p class="text-sm">{{ __('Active Farmers') }}</p>
            </div>
        </div>

        <div class="px-10" data-aos="fade-left" data-aos-delay="150">
            <h3 class="text-3xl font-bold mb-6 text-gray-800">{{ __('Empowering Agriculture Through Innovation') }}</h3>
            <p class="text-gray-600 mb-6 leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                {{ __('Our mission is to modernize agriculture by providing digital solutions that help farmers track crops, access expert consultations, and share experiences seamlessly.') }}
            </p>
            <p class="text-gray-600 mb-8 leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                {{ __('We believe that technology can transform farming into a more efficient, sustainable, and profitable journey for everyone.') }}
            </p>
            <div class="grid grid-cols-2 gap-6">
                <div class="v-card bg-gray-50 p-4 rounded-xl shadow-sm" data-sr data-d="0">
                    <h4 class="font-bold text-green-600 mb-2">{{ __('Innovation') }}</h4>
                    <p class="text-sm text-gray-600">{{ __('Smart digital tools for modern farming.') }}</p>
                </div>
                <div class="v-card bg-gray-50 p-4 rounded-xl shadow-sm" data-sr data-d="80">
                    <h4 class="font-bold text-green-600 mb-2">{{ __('Community') }}</h4>
                    <p class="text-sm text-gray-600">{{ __('Connecting farmers across regions.') }}</p>
                </div>
                <div class="v-card bg-gray-50 p-4 rounded-xl shadow-sm" data-sr data-d="160">
                    <h4 class="font-bold text-green-600 mb-2">{{ __('Sustainability') }}</h4>
                    <p class="text-sm text-gray-600">{{ __('Promoting eco-friendly agricultural practices.') }}</p>
                </div>
                <div class="v-card bg-gray-50 p-4 rounded-xl shadow-sm" data-sr data-d="240">
                    <h4 class="font-bold text-green-600 mb-2">{{ __('Growth') }}</h4>
                    <p class="text-sm text-gray-600">{{ __('Helping farmers increase productivity and success.') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
</section>


<!-- ================= SERVICES ================= -->
<section id="services" class="py-10 bg-gray-50">
<div class="max-w-7xl mx-auto px-6 text-center">

<h2 class="text-4xl font-bold mb-2" data-aos="fade-down">{{ __('Services') }}</h2>
<em class="a-line not-italic"></em>
<p class="text-gray-600 max-w-2xl mx-auto mb-14 mt-6" data-aos="fade-up" data-aos-delay="100">{{ __('Crop Growth Tracking') }}</p>

<div class="grid md:grid-cols-3 gap-10">

<div class="s-card bg-white p-6 rounded-xl shadow transition" data-aos="fade-up" data-aos-delay="100">
<img src="/images/welcome/services/growth.png" class="h-40 w-full object-cover rounded mb-4">
<h3 class="text-xl font-bold mb-3">{{ __('A complete set of smart tools to support farmers and improve agricultural production.') }}</h3>
<p class="text-gray-600">{{ __('Track your crop stages from planting to harvest and record your progress easily.') }}</p>
</div>

<div class="s-card bg-white p-6 rounded-xl shadow transition" data-aos="fade-up" data-aos-delay="200">
<img src="/images/welcome/services/expert.png" class="h-40 w-full object-cover rounded mb-4">
<h3 class="text-xl font-bold mb-3">{{ __('Expert Consultations') }}</h3>
<p class="text-gray-600">{{ __('Get advice and guidance from agricultural experts to solve problems and improve production.') }}</p>
</div>

<div class="s-card bg-white p-6 rounded-xl shadow transition" data-aos="fade-up" data-aos-delay="300">
<img src="/images/welcome/services/community.png" class="h-40 w-full object-cover rounded mb-4">
<h3 class="text-xl font-bold mb-3">{{ __('Photo Sharing') }}</h3>
<p class="text-gray-600">{{ __('Share your crop photos and experiences with the farming community.') }}</p>
</div>

<div class="s-card bg-white p-6 rounded-xl shadow transition" data-aos="fade-up" data-aos-delay="100">
<img src="https://images.unsplash.com/photo-1512428559087-560fa5ceab42?auto=format&fit=crop&q=80&w=800" class="h-40 w-full object-cover rounded mb-4">
<h3 class="text-xl font-bold mb-3">{{ __('Smart Notifications') }}</h3>
<p class="text-gray-600">{{ __('Instant alerts about your activities and important updates for your account.') }}</p>
</div>

<div class="s-card bg-white p-6 rounded-xl shadow transition" data-aos="fade-up" data-aos-delay="200">
<img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=800" class="h-40 w-full object-cover rounded mb-4">
<h3 class="text-xl font-bold mb-3">{{ __('Crop Management') }}</h3>
<p class="text-gray-600">{{ __('A complete dashboard to manage your crops and posts easily.') }}</p>
</div>

<div class="s-card bg-white p-6 rounded-xl shadow transition" data-aos="fade-up" data-aos-delay="300">
<img src="https://images.unsplash.com/photo-1558444479-2706fa5f269c?auto=format&fit=crop&q=80&w=800" class="h-40 w-full object-cover rounded mb-4">
<h3 class="text-xl font-bold mb-3">{{ __('Interactive Community') }}</h3>
<p class="text-gray-600">{{ __('Connect with thousands of farmers, share your experiences, and learn from others.') }}</p>
</div>

</div>
</div>


<!-- ================= COMMUNITY ================= -->
<section id="community" class="py-10 bg-gray-50">
<div class="max-w-7xl mx-auto px-6">

<div class="text-center mb-16">
<h2 class="text-4xl md:text-5xl font-bold mb-2" data-aos="fade-down">{{ __('Reefy Community') }}</h2>
<em class="a-line not-italic"></em>
<p class="text-gray-600 max-w-2xl mx-auto text-lg leading-relaxed mt-6" data-aos="fade-up" data-aos-delay="200">
{{ __('Discover the latest farmers posts, learn from their experiences, and share your agricultural journey with a complete community.') }}
</p>
</div>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

@forelse($posts as $post)
<div class="p-card bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 hover:-translate-y-1"
     data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">

    <div class="p-2 flex items-center">
        @if($post->user && $post->user->profile_image)
            <img src="{{ asset('storage/'.$post->user->profile_image) }}" class="w-12 h-12 rounded-full object-cover border shadow-sm">
        @else
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white font-bold shadow">
                {{ mb_substr($post->user?->name ?? 'R',0,1) }}
            </div>
        @endif
        <div class="mr-20">
            <div class="font-semibold text-gray-800">{{ $post->user?->name ?? 'مستخدم ريفي' }}</div>

            <div class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
        <div px-4>
            <span class="text-sm text-gray-500">{{ $post->type }}</span>
        </div>
        </div>
    </div>

    @if($post->content)
    <div class="px-4 pb-3">
        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">{{ $post->content }}</p>
    </div>
    @endif

    @if($post->image_path)
    <div class="w-full h-56 bg-gray-100 overflow-hidden">
        <img src="{{ asset('storage/'.$post->image_path) }}" class="w-full h-full object-cover hover:scale-105 transition duration-500">
    </div>
    @endif

    <div class="px-4 py-3 flex justify-between text-sm text-gray-500 border-t">
        <div class="flex items-center gap-1">❤️ <span class="font-medium">{{ $post->likes_count }}</span></div>
        <div class="flex items-center gap-1">💬 <span class="font-medium">{{ $post->comments_count }}</span></div>
    </div>

    <div class="px-4 py-3 flex justify-around border-t bg-gray-50">
        <a href="{{ route('register') }}" class="act flex items-center gap-2 text-gray-600 font-medium">❤️ {{ __('Like') }}</a>
        <a href="{{ route('register') }}" class="act flex items-center gap-2 text-gray-600 font-medium">💬 {{ __('Comment') }}</a>
    </div>

</div>
@empty
<div class="col-span-3 text-center py-16 text-gray-500">{{ __('No posts available') }}</div>
@endforelse

</div>

<div class="text-center mt-16" data-aos="fade-up" data-aos-delay="100">
<a href="{{ route('register') }}"
   class="btn-g inline-block bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition shadow-lg">
{{ __('Share with us') }}
</a>
</div>

</div>
</section>

<!-- ================= CONSULTATIONS ================= -->
<section id="consultations" class="py-10 bg-white">
<div class="max-w-7xl mx-auto px-6">

<div class="text-center mb-16">
<h2 class="text-4xl md:text-5xl font-bold mb-2" data-aos="fade-down">
{{ __('Latest Consultations') }}
</h2>
<em class="a-line not-italic"></em>
<p class="text-gray-600 max-w-2xl mx-auto text-lg leading-relaxed mt-6"
   data-aos="fade-up"
   data-aos-delay="200">
{{ __('Explore the latest agricultural consultations and expert advice shared by our community.') }}
</p>
</div>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

@forelse($consultations as $consultation)
<div class="p-card bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 hover:-translate-y-1"
     data-aos="fade-up"
     data-aos-delay="{{ $loop->index * 80 }}">

    <!-- Header -->
    <div class="p-4 flex items-center">
        @if($consultation->user && $consultation->user->profile_image)
            <img src="{{ asset('storage/'.$consultation->user->profile_image) }}"
                 class="w-12 h-12 rounded-full object-cover border shadow-sm">
        @else
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white font-bold shadow">
                {{ mb_substr($consultation->user?->name ?? 'R',0,1) }}
            </div>
        @endif

        <div class="mr-3">
            <div class="font-semibold text-gray-800">
                {{ $consultation->user?->name ?? __('Reefy User') }}
            </div>
            <div class="text-sm text-gray-500">
                {{ $consultation->created_at->diffForHumans() }}
            </div>
        </div>
    </div>

    <!-- Title -->
    <div class="px-4 pb-2">
        <h3 class="font-bold text-gray-800 text-md line-clamp-2">
            {{ $consultation->question }}
        </h3>
    </div>

    <!-- Content -->
    <div class="px-4 pb-4">
        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
            {{ $consultation->subject }}
        </p>
    </div>

    <!-- Footer -->
    <div class="px-4 py-3 flex justify-between text-sm text-gray-500 border-t">
        <div class="flex items-center gap-1">
            💬 <span class="font-medium">{{ $consultation->replies_count ?? 0 }}</span>
        </div>
        <div class="text-green-600 font-semibold text-sm">
            {{ $consultation->category }}
        </div>
    </div>

</div>
@empty
<div class="col-span-3 text-center py-16 text-gray-500">
{{ __('No consultations available') }}
</div>
@endforelse

</div>

<div class="text-center mt-16" data-aos="fade-up" data-aos-delay="100">
<a href="{{ route('register') }}"
   class="btn-g inline-block bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition shadow-lg">
{{ __('Ask for Consultation') }}
</a>
</div>

</div>
</section>
<!-- ================= FOOTER ================= -->
<footer class="bg-green-700 text-white text-center py-8">
<p class="mb-2 relative z-10" data-aos="fade-up" data-aos-delay="200">
{{ __('Reefy — A smart platform to support farmers and improve agricultural production') }}
</p>
<p class="relative z-10">© 2026 {{ __('All rights reserved') }}</p>
</footer>


<!-- ================= SCRIPTS ================= -->
<script>
/* hero slider */
const slides = document.querySelectorAll('.hero-slide');
let i = 0;
setInterval(() => {
    slides[i].classList.remove('active');
    i = (i + 1) % slides.length;
    slides[i].classList.add('active');
}, 3000);
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration:700, once:true, easing:'ease-out-cubic', offset:60 });
</script>

<script>
(function () {

    /* accent lines */
    const lineObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('on'); lineObs.unobserve(e.target); }
        });
    }, { threshold: 0.5 });
    document.querySelectorAll('.a-line').forEach(el => lineObs.observe(el));

    /* stagger reveal */
    const srObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                const d = parseInt(e.target.dataset.d || 0);
                setTimeout(() => e.target.classList.add('on'), d);
                srObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.15 });
    document.querySelectorAll('[data-sr]').forEach(el => srObs.observe(el));

})();
</script>

</body>
</html>
