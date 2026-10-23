<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>AirNav DMS</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    <style>

        /* =====================================================
           REGISTER PASSWORD WARNING
        ====================================================== */

        .password-warning {
            display: flex;
            align-items: center;
            gap: 8px;

            margin-top: 8px;
            padding: 8px 12px;

            border-radius: 8px;

            background: #fff7ed;
            border: 1px solid #fed7aa;

            color: #c2410c;

            font-size: 12px;
            line-height: 1.4;

            animation: passwordWarningIn 0.2s ease;
        }


        .password-warning i {
            font-size: 13px;
            flex-shrink: 0;
        }


        @keyframes passwordWarningIn {

            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        /* =====================================================
           PASSWORD INVALID
        ====================================================== */

        #register_password.password-invalid {
            border-color: #f97316 !important;
        }


        /* =====================================================
           PASSWORD VALID
        ====================================================== */

        #register_password.password-valid {
            border-color: #22c55e !important;
        }


        /* =====================================================
           PASSWORD CONFIRMATION INVALID
        ====================================================== */

        #register_password_confirmation.password-invalid {
            border-color: #f97316 !important;
        }


        /* =====================================================
           PASSWORD CONFIRMATION VALID
        ====================================================== */

        #register_password_confirmation.password-valid {
            border-color: #22c55e !important;
        }


        /* =====================================================
           CONFIRM PASSWORD WARNING
        ====================================================== */

        .password-match-warning {
            display: flex;
            align-items: center;
            gap: 8px;

            margin-top: 8px;
            padding: 8px 12px;

            border-radius: 8px;

            background: #fff7ed;
            border: 1px solid #fed7aa;

            color: #c2410c;

            font-size: 12px;
            line-height: 1.4;

            animation: passwordWarningIn 0.2s ease;
        }


        .password-match-success {
            display: flex;
            align-items: center;
            gap: 8px;

            margin-top: 8px;
            padding: 8px 12px;

            border-radius: 8px;

            background: #f0fdf4;
            border: 1px solid #bbf7d0;

            color: #15803d;

            font-size: 12px;
            line-height: 1.4;

            animation: passwordWarningIn 0.2s ease;
        }


        /* =====================================================
           AUTH PANEL TRANSITION
        ====================================================== */

        .auth-panel {
            transition:
                opacity 0.25s ease,
                transform 0.25s ease;
        }


        .auth-panel-hidden {
            display: none;
        }


        /* =====================================================
           REGISTER TERMS
        ====================================================== */

        .register-terms {
            margin-top: 4px;
        }


    </style>

</head>


<body>

<div class="login-page">


    {{-- =====================================================
        LEFT SIDE / VISUAL
    ====================================================== --}}

    <div class="login-visual">

        <div class="login-visual-overlay"></div>


        <div class="login-visual-content">


            {{-- BRAND --}}

            <div class="login-brand">

                <div class="login-brand-icon">

                    <i class="fas fa-plane"></i>

                </div>

                <span>
                    AirNav DMS
                </span>

            </div>


            {{-- WELCOME --}}

            <div class="login-welcome">

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
        RIGHT SIDE
    ====================================================== --}}

    <div class="login-form-area">


        <div class="login-form-container">


            {{-- =================================================
                LOGIN PANEL
            ================================================== --}}

            <div
                class="auth-panel"
                id="loginPanel"
            >


                {{-- HEADER --}}

                <div class="login-form-header">

                    <h2>
                        Sign in
                    </h2>

                    <p>
                        Masuk ke akun AirNav DMS Anda
                    </p>

                </div>



                {{-- SUCCESS MESSAGE --}}

                @if (session('status'))

                    <div class="login-alert login-alert-success">

                        {{ session('status') }}

                    </div>

                @endif



                {{-- ERROR MESSAGE --}}

                @if ($errors->any() && old('name') === null)

                    <div class="login-alert login-alert-danger">

                        <ul>

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif



                {{-- =================================================
                    LOGIN FORM
                ================================================== --}}

                <form
                    method="POST"
                    action="{{ route('login') }}"
                    class="login-form"
                >

                    @csrf


                    {{-- EMAIL --}}

                    <div class="login-form-group">

                        <label for="login_email">
                            Email
                        </label>


                        <div class="login-input-wrapper">

                            <i class="fas fa-envelope"></i>


                            <input
                                id="login_email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email Anda"
                                required
                                autofocus
                                autocomplete="username"
                            >

                        </div>

                    </div>



                    {{-- PASSWORD --}}

                    <div class="login-form-group">

                        <div class="login-label-row">

                            <label for="login_password">
                                Password
                            </label>


                            @if (Route::has('password.request'))

                                <a
                                    href="{{ route('password.request') }}"
                                >
                                    Lupa password?
                                </a>

                            @endif

                        </div>


                        <div class="login-input-wrapper">

                            <i class="fas fa-lock"></i>


                            <input
                                id="login_password"
                                type="password"
                                name="password"
                                placeholder="Masukkan password Anda"
                                required
                                autocomplete="current-password"
                            >


                            <button
                                type="button"
                                class="login-password-toggle"
                                id="toggleLoginPassword"
                                title="Tampilkan password"
                            >

                                <i class="fas fa-eye"></i>

                            </button>

                        </div>

                    </div>



                    {{-- REMEMBER ME --}}

                    <div class="login-remember">

                        <label>

                            <input
                                type="checkbox"
                                name="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >

                            <span>
                                Ingat saya
                            </span>

                        </label>

                    </div>



                    {{-- LOGIN BUTTON --}}

                    <button
                        type="submit"
                        class="login-submit"
                    >

                        <span>
                            Sign In
                        </span>

                        <i class="fas fa-arrow-right"></i>

                    </button>

                </form>



                {{-- REGISTER SWITCH --}}

                <div class="login-register">

                    <span>
                        Belum memiliki akun?
                    </span>


                    <button
                        type="button"
                        class="auth-switch-button"
                        id="showRegister"
                    >
                        Buat akun
                    </button>

                </div>



                {{-- BACK HOME --}}

                <a
                    href="{{ url('/') }}"
                    class="login-back-home"
                >

                    <i class="fas fa-arrow-left"></i>

                    <span>
                        Kembali ke halaman utama
                    </span>

                </a>

            </div>



            {{-- =================================================
                REGISTER PANEL
            ================================================== --}}

            <div
                class="auth-panel auth-panel-hidden"
                id="registerPanel"
            >


                {{-- HEADER --}}

                <div class="login-form-header">

                    <h2>
                        Create Account
                    </h2>

                    <p>
                        Buat akun baru AirNav DMS Anda
                    </p>

                </div>



                {{-- REGISTER ERROR --}}

                @if ($errors->any() && old('name') !== null)

                    <div
                        class="login-alert login-alert-danger"
                        id="registerError"
                    >

                        <ul>

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif



                {{-- =================================================
                    REGISTER FORM
                ================================================== --}}

                <form
                    method="POST"
                    action="{{ route('register') }}"
                    class="login-form"
                    id="registerForm"
                >

                    @csrf



                    {{-- =================================================
                        FULL NAME
                    ================================================== --}}

                    <div class="login-form-group">

                        <label for="register_name">
                            Full Name
                        </label>


                        <div class="login-input-wrapper">

                            <i class="fas fa-user"></i>


                            <input
                                id="register_name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                required
                                autocomplete="name"
                            >

                        </div>

                    </div>



                    {{-- =================================================
                        EMAIL
                    ================================================== --}}

                    <div class="login-form-group">

                        <label for="register_email">
                            Email
                        </label>


                        <div class="login-input-wrapper">

                            <i class="fas fa-envelope"></i>


                            <input
                                id="register_email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email Anda"
                                required
                                autocomplete="email"
                            >

                        </div>

                    </div>



                    {{-- =================================================
                        USERNAME
                    ================================================== --}}

                    <div class="login-form-group">

                        <label for="register_username">
                            Username
                        </label>


                        <div class="login-input-wrapper">

                            <i class="fas fa-at"></i>


                            <input
                                id="register_username"
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

                    <div class="login-form-group">

                        <label for="register_password">
                            Password
                        </label>


                        <div class="login-input-wrapper">

                            <i class="fas fa-lock"></i>


                            <input
                                id="register_password"
                                type="password"
                                name="password"
                                placeholder="Masukkan password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                            >


                            <button
                                type="button"
                                class="login-password-toggle"
                                id="toggleRegisterPassword"
                                title="Tampilkan password"
                            >

                                <i class="fas fa-eye"></i>

                            </button>

                        </div>


                        {{-- REALTIME PASSWORD WARNING --}}

                        <div
                            id="registerPasswordWarning"
                            class="password-warning"
                            style="display: none;"
                        >

                            <i class="fas fa-circle-exclamation"></i>

                            <span>
                                Password harus memiliki minimal 8 karakter.
                            </span>

                        </div>

                    </div>



                    {{-- =================================================
                        CONFIRM PASSWORD
                    ================================================== --}}

                    <div class="login-form-group">

                        <label for="register_password_confirmation">
                            Confirm Password
                        </label>


                        <div class="login-input-wrapper">

                            <i class="fas fa-lock"></i>


                            <input
                                id="register_password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="Ulangi password"
                                required
                                autocomplete="new-password"
                            >


                            <button
                                type="button"
                                class="login-password-toggle"
                                id="toggleRegisterConfirmPassword"
                                title="Tampilkan password"
                            >

                                <i class="fas fa-eye"></i>

                            </button>

                        </div>


                        {{-- PASSWORD MATCH WARNING --}}

                        <div
                            id="passwordMatchWarning"
                            class="password-match-warning"
                            style="display: none;"
                        >

                            <i class="fas fa-circle-exclamation"></i>

                            <span>
                                Konfirmasi password belum sama.
                            </span>

                        </div>


                        {{-- PASSWORD MATCH SUCCESS --}}

                        <div
                            id="passwordMatchSuccess"
                            class="password-match-success"
                            style="display: none;"
                        >

                            <i class="fas fa-circle-check"></i>

                            <span>
                                Password sudah cocok.
                            </span>

                        </div>

                    </div>



                    {{-- =================================================
                        TERMS
                    ================================================== --}}

                    <div class="login-remember register-terms">

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
                        REGISTER BUTTON
                    ================================================== --}}

                    <button
                        type="submit"
                        class="login-submit"
                        id="registerSubmit"
                    >

                        <span>
                            Create Account
                        </span>

                        <i class="fas fa-user-plus"></i>

                    </button>

                </form>



                {{-- LOGIN SWITCH --}}

                <div class="login-register">

                    <span>
                        Sudah memiliki akun?
                    </span>


                    <button
                        type="button"
                        class="auth-switch-button"
                        id="showLogin"
                    >
                        Sign In
                    </button>

                </div>



                {{-- BACK HOME --}}

                <a
                    href="{{ url('/') }}"
                    class="login-back-home"
                >

                    <i class="fas fa-arrow-left"></i>

                    <span>
                        Kembali ke halaman utama
                    </span>

                </a>

            </div>


        </div>

    </div>

</div>



{{-- =====================================================
    JAVASCRIPT
====================================================== --}}

<script>


    /* =====================================================
       PANEL SWITCH
    ====================================================== */

    const loginPanel =
        document.getElementById('loginPanel');

    const registerPanel =
        document.getElementById('registerPanel');

    const showRegister =
        document.getElementById('showRegister');

    const showLogin =
        document.getElementById('showLogin');


    function openRegister() {

        if (!loginPanel || !registerPanel) {
            return;
        }


        loginPanel.classList.add(
            'auth-panel-hidden'
        );

        registerPanel.classList.remove(
            'auth-panel-hidden'
        );


        setTimeout(() => {

            document
                .getElementById('register_name')
                ?.focus();

        }, 150);

    }


    function openLogin() {

        if (!loginPanel || !registerPanel) {
            return;
        }


        registerPanel.classList.add(
            'auth-panel-hidden'
        );

        loginPanel.classList.remove(
            'auth-panel-hidden'
        );


        setTimeout(() => {

            document
                .getElementById('login_email')
                ?.focus();

        }, 150);

    }


    showRegister?.addEventListener(
        'click',
        openRegister
    );


    showLogin?.addEventListener(
        'click',
        openLogin
    );



    /* =====================================================
       PASSWORD TOGGLE
    ====================================================== */

    function setupPasswordToggle(
        buttonId,
        inputId
    ) {

        const button =
            document.getElementById(buttonId);

        const input =
            document.getElementById(inputId);


        button?.addEventListener(
            'click',
            function () {

                if (!input) {
                    return;
                }


                const icon =
                    this.querySelector('i');


                if (input.type === 'password') {

                    input.type = 'text';


                    icon?.classList.remove(
                        'fa-eye'
                    );


                    icon?.classList.add(
                        'fa-eye-slash'
                    );

                } else {

                    input.type = 'password';


                    icon?.classList.remove(
                        'fa-eye-slash'
                    );


                    icon?.classList.add(
                        'fa-eye'
                    );

                }

            }
        );

    }


    setupPasswordToggle(
        'toggleLoginPassword',
        'login_password'
    );


    setupPasswordToggle(
        'toggleRegisterPassword',
        'register_password'
    );


    setupPasswordToggle(
        'toggleRegisterConfirmPassword',
        'register_password_confirmation'
    );



    /* =====================================================
       REALTIME PASSWORD VALIDATION
    ====================================================== */

    const registerPassword =
        document.getElementById(
            'register_password'
        );


    const registerPasswordWarning =
        document.getElementById(
            'registerPasswordWarning'
        );


    registerPassword?.addEventListener(
        'input',
        function () {

            const length =
                this.value.length;


            /*
            |--------------------------------------------------
            | KOSONG
            |--------------------------------------------------
            */

            if (length === 0) {

                this.classList.remove(
                    'password-invalid',
                    'password-valid'
                );


                if (registerPasswordWarning) {

                    registerPasswordWarning.style.display =
                        'none';

                }

                return;

            }


            /*
            |--------------------------------------------------
            | KURANG DARI 8
            |--------------------------------------------------
            */

            if (length < 8) {

                this.classList.add(
                    'password-invalid'
                );


                this.classList.remove(
                    'password-valid'
                );


                if (registerPasswordWarning) {

                    registerPasswordWarning.style.display =
                        'flex';

                }

                return;

            }


            /*
            |--------------------------------------------------
            | 8 ATAU LEBIH
            |--------------------------------------------------
            */

            this.classList.remove(
                'password-invalid'
            );


            this.classList.add(
                'password-valid'
            );


            if (registerPasswordWarning) {

                registerPasswordWarning.style.display =
                    'none';

            }

        }
    );



    /* =====================================================
       REALTIME CONFIRM PASSWORD
    ====================================================== */

    const registerPasswordConfirmation =
        document.getElementById(
            'register_password_confirmation'
        );


    const passwordMatchWarning =
        document.getElementById(
            'passwordMatchWarning'
        );


    const passwordMatchSuccess =
        document.getElementById(
            'passwordMatchSuccess'
        );


    function validatePasswordMatch() {

        if (
            !registerPassword ||
            !registerPasswordConfirmation
        ) {

            return;

        }


        const password =
            registerPassword.value;


        const confirmation =
            registerPasswordConfirmation.value;


        /*
        |--------------------------------------------------
        | KOSONG
        |--------------------------------------------------
        */

        if (confirmation.length === 0) {

            registerPasswordConfirmation.classList.remove(
                'password-invalid',
                'password-valid'
            );


            if (passwordMatchWarning) {

                passwordMatchWarning.style.display =
                    'none';

            }


            if (passwordMatchSuccess) {

                passwordMatchSuccess.style.display =
                    'none';

            }

            return;

        }


        /*
        |--------------------------------------------------
        | PASSWORD TIDAK SAMA
        |--------------------------------------------------
        */

        if (password !== confirmation) {

            registerPasswordConfirmation.classList.add(
                'password-invalid'
            );


            registerPasswordConfirmation.classList.remove(
                'password-valid'
            );


            if (passwordMatchWarning) {

                passwordMatchWarning.style.display =
                    'flex';

            }


            if (passwordMatchSuccess) {

                passwordMatchSuccess.style.display =
                    'none';

            }

            return;

        }


        /*
        |--------------------------------------------------
        | PASSWORD SAMA
        |--------------------------------------------------
        */

        registerPasswordConfirmation.classList.remove(
            'password-invalid'
        );


        registerPasswordConfirmation.classList.add(
            'password-valid'
        );


        if (passwordMatchWarning) {

            passwordMatchWarning.style.display =
                'none';

        }


        if (passwordMatchSuccess) {

            passwordMatchSuccess.style.display =
                'flex';

        }

    }


    registerPasswordConfirmation?.addEventListener(
        'input',
        validatePasswordMatch
    );


    registerPassword?.addEventListener(
        'input',
        validatePasswordMatch
    );



    /* =====================================================
       REGISTER FORM VALIDATION
    ====================================================== */

    const registerForm =
        document.getElementById('registerForm');


    registerForm?.addEventListener(
        'submit',
        function (event) {

            if (!registerPassword) {
                return;
            }


            const passwordLength =
                registerPassword.value.length;


            /*
            |--------------------------------------------------
            | PASSWORD < 8
            |--------------------------------------------------
            */

            if (passwordLength < 8) {

                event.preventDefault();


                registerPassword.focus();


                registerPassword.classList.add(
                    'password-invalid'
                );


                if (registerPasswordWarning) {

                    registerPasswordWarning.style.display =
                        'flex';

                }


                return;

            }


            /*
            |--------------------------------------------------
            | CONFIRM PASSWORD
            |--------------------------------------------------
            */

            if (
                registerPasswordConfirmation &&
                registerPassword.value !==
                    registerPasswordConfirmation.value
            ) {

                event.preventDefault();


                registerPasswordConfirmation.focus();


                registerPasswordConfirmation.classList.add(
                    'password-invalid'
                );


                if (passwordMatchWarning) {

                    passwordMatchWarning.style.display =
                        'flex';

                }


                if (passwordMatchSuccess) {

                    passwordMatchSuccess.style.display =
                        'none';

                }


                return;

            }

        }
    );



    /* =====================================================
       AUTOMATICALLY OPEN REGISTER AFTER VALIDATION ERROR
    ====================================================== */

    @if ($errors->any() && old('name') !== null)

        openRegister();

    @endif


</script>

</body>

</html>