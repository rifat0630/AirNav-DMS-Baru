<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Create Account - AirNav DMS</title>

    @vite([
        'resources/css/app.css',
        'resources/css/login.css',
        'resources/css/register.css',
        'resources/js/app.js'
    ])

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body>

<div class="register-page">


    {{-- =====================================================
        LEFT SIDE / VISUAL
    ====================================================== --}}

    <div class="register-visual">

        <div class="register-visual-overlay"></div>

        <div class="register-visual-content">

            {{-- BRAND --}}

            <div class="register-brand">

                <div class="register-brand-icon">

                    <i class="fas fa-plane"></i>

                </div>

                <span>
                    AirNav DMS
                </span>

            </div>


            {{-- WELCOME --}}

            <div class="register-welcome">

                <h1>
                    Welcome
                </h1>

                <p>
                    Sistem Manajemen Dokumen dan Informasi
                    AirNav Indonesia.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
        RIGHT SIDE / REGISTER FORM
    ====================================================== --}}

    <div class="register-form-area">

        <div class="register-form-container">


            {{-- HEADER --}}

            <div class="register-form-header">

                <div class="register-form-icon">

                    <i class="fas fa-user-plus"></i>

                </div>

                <div>

                    <h2>
                        Create Account
                    </h2>

                    <p>
                        Buat akun baru untuk menggunakan AirNav DMS
                    </p>

                </div>

            </div>


            {{-- ERROR --}}

            @if ($errors->any())

                <div class="register-alert">

                    <div class="register-alert-icon">

                        <i class="fas fa-exclamation-circle"></i>

                    </div>

                    <div>

                        @foreach ($errors->all() as $error)

                            <div>
                                {{ $error }}
                            </div>

                        @endforeach

                    </div>

                </div>

            @endif


            {{-- REGISTER FORM --}}

            <form method="POST"
                  action="{{ route('register') }}"
                  class="register-form">

                @csrf


                {{-- =================================================
                    FULL NAME
                ================================================== --}}

                <div class="register-form-group">

                    <label for="name">
                        Full Name
                    </label>

                    <div class="register-input-wrapper">

                        <i class="fas fa-user"></i>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"
                            required
                            autofocus
                            autocomplete="name"
                        >

                    </div>

                </div>


                {{-- =================================================
                    EMAIL
                ================================================== --}}

                <div class="register-form-group">

                    <label for="email">
                        Email
                    </label>

                    <div class="register-input-wrapper">

                        <i class="fas fa-envelope"></i>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Masukkan alamat email"
                            required
                            autocomplete="username"
                        >

                    </div>

                </div>


                {{-- =================================================
                    USERNAME
                ================================================== --}}

                <div class="register-form-group">

                    <label for="username">
                        Username
                    </label>

                    <div class="register-input-wrapper">

                        <i class="fas fa-at"></i>

                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            required
                            autocomplete="username"
                        >

                    </div>

                </div>


                {{-- =================================================
                    PASSWORD
                ================================================== --}}

                <div class="register-form-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="register-input-wrapper">

                        <i class="fas fa-lock"></i>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            class="register-password-toggle"
                            data-target="password"
                            title="Tampilkan password">

                            <i class="fas fa-eye"></i>

                        </button>

                    </div>

                </div>


                {{-- =================================================
                    CONFIRM PASSWORD
                ================================================== --}}

                <div class="register-form-group">

                    <label for="password_confirmation">
                        Confirm Password
                    </label>

                    <div class="register-input-wrapper">

                        <i class="fas fa-lock"></i>

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Konfirmasi password"
                            required
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            class="register-password-toggle"
                            data-target="password_confirmation"
                            title="Tampilkan password">

                            <i class="fas fa-eye"></i>

                        </button>

                    </div>

                </div>


                {{-- =================================================
                    TERMS
                ================================================== --}}

                <div class="register-terms">

                    <label>

                        <input
                            type="checkbox"
                            name="terms"
                            required
                        >

                        <span>

                            Saya menyetujui

                            <a href="#">
                                Terms of Service
                            </a>

                            dan

                            <a href="#">
                                Privacy Policy
                            </a>

                        </span>

                    </label>

                </div>


                {{-- =================================================
                    SUBMIT
                ================================================== --}}

                <button
                    type="submit"
                    class="register-submit">

                    <span>
                        Create Account
                    </span>

                    <i class="fas fa-arrow-right"></i>

                </button>

            </form>


            {{-- =================================================
                LOGIN
            ================================================== --}}

            <div class="register-login">

                <span>
                    Sudah memiliki akun?
                </span>

                <a href="{{ route('login') }}">
                    Sign In
                </a>

            </div>


            {{-- =================================================
                BACK HOME
            ================================================== --}}

            <a
                href="{{ url('/') }}"
                class="register-back-home">

                <i class="fas fa-arrow-left"></i>

                <span>
                    Kembali ke halaman utama
                </span>

            </a>

        </div>

    </div>

</div>


{{-- =====================================================
    PASSWORD TOGGLE
====================================================== --}}

<script>

document
    .querySelectorAll('.register-password-toggle')
    .forEach(function(button) {

        button.addEventListener('click', function() {

            const targetId =
                this.getAttribute('data-target');

            const password =
                document.getElementById(targetId);

            const icon =
                this.querySelector('i');


            if (password.type === 'password') {

                password.type = 'text';

                icon.classList.remove('fa-eye');

                icon.classList.add('fa-eye-slash');

            } else {

                password.type = 'password';

                icon.classList.remove('fa-eye-slash');

                icon.classList.add('fa-eye');

            }

        });

    });

</script>


</body>

</html>