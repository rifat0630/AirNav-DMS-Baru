@extends('layouts.guest')

@section('title','AirNav DMS - Login')

@section('content')

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

<script>
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "error-container": "#ffdad6",
                "on-primary-container": "#eeefff",
                "on-secondary-fixed-variant": "#38485d",
                "on-secondary-fixed": "#0b1c30",
                "on-primary-fixed-variant": "#003ea8",
                "background": "#f7f9fd",
                "outline": "#737686",
                "inverse-primary": "#b4c5ff",
                "on-tertiary-container": "#e4f2ff",
                "on-tertiary": "#ffffff",
                "secondary-fixed-dim": "#b7c8e1",
                "surface-container": "#eceef2",
                "inverse-on-surface": "#eff1f5",
                "secondary": "#505f76",
                "tertiary-fixed-dim": "#89ceff",
                "inverse-surface": "#2d3134",
                "surface-dim": "#d8dade",
                "outline-variant": "#c3c6d7",
                "surface-variant": "#e0e3e6",
                "tertiary-fixed": "#c9e6ff",
                "on-tertiary-fixed-variant": "#004c6e",
                "on-secondary": "#ffffff",
                "primary-fixed-dim": "#b4c5ff",
                "primary-fixed": "#dbe1ff",
                "error": "#ba1a1a",
                "primary-container": "#2563eb",
                "surface-container-highest": "#e0e3e6",
                "on-primary-fixed": "#00174b",
                "surface-tint": "#0053db",
                "surface-container-high": "#e6e8ec",
                "secondary-fixed": "#d3e4fe",
                "primary": "#004ac6",
                "on-surface": "#181c1f",
                "on-background": "#181c1f",
                "secondary-container": "#d0e1fb",
                "tertiary-container": "#0074a6",
                "on-secondary-container": "#54647a",
                "surface-bright": "#f7f9fd",
                "surface-container-low": "#f2f4f8",
                "on-tertiary-fixed": "#001e2f",
                "on-surface-variant": "#434655",
                "on-primary": "#ffffff",
                "tertiary": "#005a82",
                "surface-container-lowest": "#ffffff",
                "on-error": "#ffffff",
                "surface": "#f7f9fd",
                "on-error-container": "#93000a"
            },
            borderRadius:{
                DEFAULT:"0.25rem",
                lg:"0.5rem",
                xl:"0.75rem",
                full:"9999px"
            },
            spacing:{
                "margin-desktop":"32px",
                "3xl":"64px",
                "container-max":"1440px",
                "sm":"8px",
                "xs":"4px",
                "2xl":"48px",
                "md":"16px",
                "xl":"32px",
                "unit":"8px",
                "lg":"24px",
                "margin-mobile":"16px",
                "gutter":"24px"
            }
        }
    }
}
</script>

<style>

.fade-in-up{
animation:fadeInUp .6s ease-out forwards;
opacity:0;
transform:translateY(20px);
}

@keyframes fadeInUp{
to{
opacity:1;
transform:translateY(0);
}
}

.floating-input:focus~.floating-label,
.floating-input:not(:placeholder-shown)~.floating-label{
transform:translateY(-130%) scale(.85);
color:#004ac6;
background:white;
padding:0 4px;
}

</style>

<body class="bg-background text-on-background font-body-md min-h-screen overflow-hidden antialiased">
<img src="{{ asset('images/logo-airnav.png') }}" width="200">
<div class="flex h-screen w-full">

<div class="hidden lg:block w-[45%] relative overflow-hidden">

    <!-- Background -->
    <img
        src="{{ asset('images/login-bg.jpg') }}"
        class="absolute inset-0 w-full h-full object-cover"
        alt="Login Background">

    <!-- Overlay lembut -->
    <div class="absolute inset-0 bg-gradient-to-r from-white/55 via-white/30 to-transparent"></div>

    <!-- Content -->
    <div class="relative z-10 flex items-center h-full px-16">

        <div class="max-w-md">

            <h1 class="text-5xl font-bold text-slate-800 leading-tight">
                Welcome Back
            </h1>

            <p class="mt-6 text-xl text-slate-700 leading-relaxed">
                Sign in to access the AirNav Document Management System.
            </p>

            <p class="mt-3 text-slate-600 leading-relaxed">
                Your mission-critical documents, secure and readily available.
            </p>

        </div>

    </div>

</div>

<div class="w-full lg:w-[55%] flex items-center justify-center bg-surface relative z-30 shadow-[-10px_0_30px_rgba(0,0,0,0.03)] p-margin-mobile md:p-margin-desktop">

<div class="w-full max-w-[480px] bg-surface-container-lowest rounded-[20px] p-xl shadow-[0px_4px_24px_rgba(15,23,42,0.06)] border border-surface-variant fade-in-up">

<div class="lg:hidden text-center mb-xl">

<h1 class="font-headline-lg-mobile text-headline-lg-mobile text-primary font-bold">

AirNav DMS

</h1>

</div>

<div class="mb-lg">

<h2 class="font-headline-md text-headline-md text-on-surface mb-xs">

Sign In

</h2>

<p class="font-body-sm text-body-sm text-on-surface-variant">

Enter your credentials to continue

</p>

</div>

<form method="POST" action="{{ route('login') }}" class="space-y-lg">

@csrf

{{-- ================= EMAIL ================= --}}

<div class="relative group">

    <div class="absolute inset-y-0 left-0 pl-md flex items-center pointer-events-none">

        <span class="material-symbols-outlined text-outline group-focus-within:text-primary transition-colors">
            mail
        </span>

    </div>

    <input
        id="email"
        name="email"
        type="email"
        value="{{ old('email') }}"
        required
        autofocus
        placeholder=" "
        class="floating-input w-full h-[48px] pl-[48px] pr-md bg-surface-container-lowest border border-outline-variant rounded-lg text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all peer @error('email') border-red-500 @enderror">

    <label
        for="email"
        class="floating-label absolute left-[48px] top-1/2 -translate-y-1/2 text-body-md text-outline transition-all duration-200 pointer-events-none origin-left">

        Email Address

    </label>

</div>

@error('email')

<p class="text-red-500 text-sm -mt-3">

    {{ $message }}

</p>

@enderror


{{-- ================= PASSWORD ================= --}}

<div class="relative group">

    <div class="absolute inset-y-0 left-0 pl-md flex items-center pointer-events-none">

        <span class="material-symbols-outlined text-outline group-focus-within:text-primary transition-colors">

            lock

        </span>

    </div>

    <input

        id="password"
        name="password"
        type="password"
        required
        placeholder=" "

        class="floating-input w-full h-[48px] pl-[48px] pr-md bg-surface-container-lowest border border-outline-variant rounded-lg text-body-md text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all peer @error('password') border-red-500 @enderror">

    <label

        for="password"

        class="floating-label absolute left-[48px] top-1/2 -translate-y-1/2 text-body-md text-outline transition-all duration-200 pointer-events-none origin-left">

        Password

    </label>

</div>

@error('password')

<p class="text-red-500 text-sm -mt-3">

    {{ $message }}

</p>

@enderror



{{-- ================= OPTIONS ================= --}}

<div class="flex items-center justify-between mt-sm">

    <label class="flex items-center gap-sm cursor-pointer group">

        <input

            type="checkbox"

            name="remember"

            class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 transition-colors">

        <span class="font-body-sm text-body-sm text-on-surface-variant group-hover:text-on-surface transition-colors">

            Remember me

        </span>

    </label>

    @if (Route::has('password.request'))

    <a

        href="{{ route('password.request') }}"

        class="font-label-md text-label-md text-primary hover:text-primary-fixed-variant transition-colors">

        Forgot Password?

    </a>

    @endif

</div>

{{-- ================= ACTION BUTTONS ================= --}}

<div class="pt-sm space-y-md">

    <button

        type="submit"

        class="w-full flex items-center justify-center gap-sm h-[48px] bg-gradient-to-r from-primary to-surface-tint text-on-primary rounded-full font-label-md text-label-md hover:shadow-[0px_4px_12px_rgba(0,74,198,0.2)] hover:scale-[1.01] active:scale-[0.99] transition-all duration-200">

        Sign In

        <span class="material-symbols-outlined text-[20px]">

            arrow_right_alt

        </span>

    </button>

    <button

        type="button"

        onclick="window.location='{{ url('/') }}'"

        class="w-full flex items-center justify-center h-[48px] bg-surface-container-lowest border border-outline-variant text-on-surface rounded-full font-label-md text-label-md hover:bg-surface-container-low active:bg-surface-container transition-colors duration-200">

        Back to Home

    </button>

</div>

</form>

{{-- ================= FOOTER ================= --}}

<div class="mt-xl text-center">

    <p class="font-body-sm text-body-sm text-on-surface-variant">

        Don't have an account?

        <a

            href="{{ route('register') }}"

            class="font-label-md text-label-md text-primary hover:underline ml-xs">

            Create Account

        </a>

    </p>

</div>

</div>

</div>

</div>

@endsection

