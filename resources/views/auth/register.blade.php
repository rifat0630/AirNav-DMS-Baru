@extends('layouts.guest')

@section('title','AirNav DMS - Create Account')

@section('content')

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>

<script>

tailwind.config={

darkMode:"class",

theme:{

extend:{

colors:{

"error-container":"#ffdad6",

"on-primary-container":"#eeefff",

"on-secondary-fixed-variant":"#38485d",

"on-secondary-fixed":"#0b1c30",

"on-primary-fixed-variant":"#003ea8",

"background":"#f7f9fd",

"outline":"#737686",

"inverse-primary":"#b4c5ff",

"on-tertiary-container":"#e4f2ff",

"on-tertiary":"#ffffff",

"secondary-fixed-dim":"#b7c8e1",

"surface-container":"#eceef2",

"inverse-on-surface":"#eff1f5",

"secondary":"#505f76",

"tertiary-fixed-dim":"#89ceff",

"inverse-surface":"#2d3134",

"surface-dim":"#d8dade",

"outline-variant":"#c3c6d7",

"surface-variant":"#e0e3e6",

"tertiary-fixed":"#c9e6ff",

"on-tertiary-fixed-variant":"#004c6e",

"on-secondary":"#ffffff",

"primary-fixed-dim":"#b4c5ff",

"primary-fixed":"#dbe1ff",

"error":"#ba1a1a",

"primary-container":"#2563eb",

"surface-container-highest":"#e0e3e6",

"on-primary-fixed":"#00174b",

"surface-tint":"#0053db",

"surface-container-high":"#e6e8ec",

"secondary-fixed":"#d3e4fe",

"primary":"#004ac6",

"on-surface":"#181c1f",

"on-background":"#181c1f",

"secondary-container":"#d0e1fb",

"tertiary-container":"#0074a6",

"on-secondary-container":"#54647a",

"surface-bright":"#f7f9fd",

"surface-container-low":"#f2f4f8",

"on-tertiary-fixed":"#001e2f",

"on-surface-variant":"#434655",

"on-primary":"#ffffff",

"tertiary":"#005a82",

"surface-container-lowest":"#ffffff",

"on-error":"#ffffff",

"surface":"#f7f9fd",

"on-error-container":"#93000a"

}

}

}

}

</script>

<style>

.animate-fade-in-up{

animation:fadeInUp .8s ease-out forwards;

opacity:0;

transform:translateY(20px);

}

@keyframes fadeInUp{

to{

opacity:1;

transform:translateY(0);

}

}

.floating-input:focus-within label,

.floating-input input:not(:placeholder-shown)+label{

transform:translateY(-50%) scale(.85);

top:0;

background:#fff;

padding:0 4px;

color:#2563eb;

}

</style>

<body class="bg-background text-on-background min-h-screen font-body-md overflow-hidden flex flex-col md:flex-row">

<div class="hidden md:flex flex-col w-[45%] relative bg-gradient-to-br from-primary-container to-tertiary text-on-primary justify-center items-center p-2xl overflow-hidden">

<div class="absolute right-0 top-0 bottom-0 w-[100px] z-10 pointer-events-none mix-blend-overlay opacity-30"></div>

<div class="z-20 flex flex-col items-center max-w-md animate-fade-in-up">

<div class="flex items-center gap-sm mb-lg">

<span class="material-symbols-outlined text-[48px] text-primary-fixed">

flight_takeoff

</span>

<h1 class="font-display-lg text-display-lg text-primary-fixed">

AirNav DMS

</h1>

</div>

<p class="font-body-lg text-body-lg text-primary-fixed-dim text-center mb-2xl">

Enterprise-grade aviation document management. Precision, speed, and absolute control over your critical data.

</p>

</div>

</div>

<div class="w-full md:w-[55%] flex items-center justify-center p-md md:p-2xl bg-surface">

<div class="w-full max-w-[480px] bg-surface-container-lowest rounded-[20px] p-lg md:p-2xl shadow-[0px_8px_24px_rgba(15,23,42,.08)] border border-outline-variant animate-fade-in-up">

<div class="mb-xl">

<h2 class="font-headline-lg text-headline-lg text-on-surface mb-xs">

Create Account

</h2>

<p class="font-body-md text-body-md text-on-surface-variant">

Join AirNav Document Management System

</p>

</div>

<form method="POST" action="{{ route('register') }}" class="space-y-lg">

@csrf

{{-- ================= FULL NAME ================= --}}

<div class="relative floating-input mb-4">

    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 z-10">
        person
    </span>

    <input
        id="name"
        name="name"
        type="text"
        value="{{ old('name') }}"
        required
        autofocus
        placeholder=" "
        class="peer w-full h-12 pl-12 pr-4 border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition @error('name') border-red-500 @enderror">

    <label
        for="name"
        class="absolute left-12 top-1/2 -translate-y-1/2 bg-white px-1 text-gray-500 transition-all duration-200 pointer-events-none
        peer-focus:top-0 peer-focus:text-xs peer-focus:text-blue-600
        peer-[:not(:placeholder-shown)]:top-0
        peer-[:not(:placeholder-shown)]:text-xs">

        Full Name

    </label>

</div>

@error('name')
<p class="text-red-500 text-sm mt-1">
    {{ $message }}
</p>
@enderror


{{-- ================= EMAIL ================= --}}

<div class="relative floating-input mb-4">

    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 z-10">
        mail
    </span>

    <input
        id="email"
        name="email"
        type="email"
        value="{{ old('email') }}"
        required
        placeholder=" "
        class="peer w-full h-12 pl-12 pr-4 border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition @error('email') border-red-500 @enderror">

    <label
        for="email"
        class="absolute left-12 top-1/2 -translate-y-1/2 bg-white px-1 text-gray-500 transition-all duration-200 pointer-events-none
        peer-focus:top-0
        peer-focus:-translate-y-1/2
        peer-focus:text-xs
        peer-focus:text-blue-600
        peer-[:not(:placeholder-shown)]:top-0
        peer-[:not(:placeholder-shown)]:-translate-y-1/2
        peer-[:not(:placeholder-shown)]:text-xs">

        Email Address

    </label>

</div>

@error('email')
<p class="text-red-500 text-sm mt-1">
    {{ $message }}
</p>
@enderror



{{-- ================= USERNAME ================= --}}

<div class="relative floating-input mb-4">

    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 z-10">
        alternate_email
    </span>

    <input
        id="username"
        name="username"
        type="text"
        value="{{ old('username') }}"
        required
        placeholder=" "
        class="peer w-full h-12 pl-12 pr-4 border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition @error('username') border-red-500 @enderror">

    <label
        for="username"
        class="absolute left-12 top-1/2 -translate-y-1/2 bg-white px-1 text-gray-500 transition-all duration-200 pointer-events-none
        peer-focus:top-0
        peer-focus:-translate-y-1/2
        peer-focus:text-xs
        peer-focus:text-blue-600
        peer-[:not(:placeholder-shown)]:top-0
        peer-[:not(:placeholder-shown)]:-translate-y-1/2
        peer-[:not(:placeholder-shown)]:text-xs">

        Username

    </label>

</div>

@error('username')
<p class="text-red-500 text-sm mt-1">
    {{ $message }}
</p>
@enderror

{{-- ================= PASSWORD ================= --}}
<div class="relative floating-input mb-4">

    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 z-10">
        lock
    </span>

    <input
        id="password"
        name="password"
        type="password"
        required
        placeholder=" "
        class="peer w-full h-12 pl-12 pr-12 border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition @error('password') border-red-500 @enderror">

    <label
        for="password"
        class="absolute left-12 top-1/2 -translate-y-1/2 bg-white px-1 text-gray-500 transition-all duration-200 pointer-events-none
        peer-focus:top-0
        peer-focus:-translate-y-1/2
        peer-focus:text-xs
        peer-focus:text-blue-600
        peer-[:not(:placeholder-shown)]:top-0
        peer-[:not(:placeholder-shown)]:-translate-y-1/2
        peer-[:not(:placeholder-shown)]:text-xs">

        Password

    </label>

    <button
        type="button"
        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">

        <span class="material-symbols-outlined">
            visibility_off
        </span>

    </button>

</div>

@error('password')
<p class="text-red-500 text-sm mt-1">
    {{ $message }}
</p>
@enderror



{{-- ================= CONFIRM PASSWORD ================= --}}


<div class="relative floating-input mb-4">

    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 z-10">
        lock_reset
    </span>

    <input
        id="password_confirmation"
        name="password_confirmation"
        type="password"
        required
        placeholder=" "
        class="peer w-full h-12 pl-12 pr-12 border border-gray-300 rounded-lg focus:border-blue-600 focus:ring-2 focus:ring-blue-200 outline-none transition">

    <label
        for="password_confirmation"
        class="absolute left-12 top-1/2 -translate-y-1/2 bg-white px-1 text-gray-500 transition-all duration-200 pointer-events-none
        peer-focus:top-0
        peer-focus:-translate-y-1/2
        peer-focus:text-xs
        peer-focus:text-blue-600
        peer-[:not(:placeholder-shown)]:top-0
        peer-[:not(:placeholder-shown)]:-translate-y-1/2
        peer-[:not(:placeholder-shown)]:text-xs">

        Confirm Password

    </label>

    <button
        type="button"
        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">

        <span class="material-symbols-outlined">
            visibility_off
        </span>

    </button>

</div>



{{-- ================= TERMS ================= --}}

<div class="flex items-start gap-sm mt-md">

    <div class="flex items-center h-5">

        <input
            id="terms"
            name="terms"
            type="checkbox"
            required
            class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20 bg-surface-container-lowest mt-[2px]">

    </div>

    <label
        for="terms"
        class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer">

        I accept the

        <a href="#" class="text-primary hover:underline font-medium">

            Terms of Service

        </a>

        and

        <a href="#" class="text-primary hover:underline font-medium">

            Privacy Policy

        </a>

    </label>

</div>



{{-- ================= SUBMIT ================= --}}

<div class="pt-sm">

    <button
        type="submit"
        class="w-full flex items-center justify-center gap-sm bg-gradient-to-r from-primary to-primary-container text-on-primary h-[48px] rounded-full font-label-md text-label-md shadow-md hover:shadow-lg hover:-translate-y-[1px] transition-all duration-200">

        <span class="material-symbols-outlined text-[20px]">

            person_add

        </span>

        Create Account

    </button>

</div>

</form>



{{-- ================= FOOTER ================= --}}

<div class="mt-xl text-center">

    <p class="font-body-sm text-body-sm text-on-surface-variant">

        Already have an account?

        <a
            href="{{ route('login') }}"
            class="font-label-md text-label-md text-primary hover:text-primary-container hover:underline ml-xs transition-colors">

            Sign In

        </a>

    </p>

</div>

</div>

</div>

</body>

@endsection