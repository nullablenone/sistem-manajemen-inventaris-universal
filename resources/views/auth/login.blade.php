<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Cleopatra Dashboard</title>
    <!-- Font Awesome untuk Icon -->
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
    <!-- CSS Template Cleopatra -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <style>
        .login-card {
            max-width: 950px;
            min-height: 580px;
        }

        .bg-brand {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        }

        /* Penyesuaian Icon agar tetap presisi */
        .input-icon-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6366f1;
            z-index: 10;
        }

        .icon-field {
            padding-left: 45px !important;
        }

        /* Perbaikan responsive untuk layar kecil */
        @media (max-width: 767px) {
            .login-card {
                min-height: auto;
                margin: 10px;
            }
        }


        .form-section {
            width: 50%;
            padding: 40px 50px !important;
            box-sizing: border-box !important;
        }

        .form-section input:not([type="checkbox"]),
        .form-section button {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        @media (max-width: 767px) {
            .form-section {
                width: 100%;
                padding: 30px 20px !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center p-4">

        <!-- Main Card Container -->
        <div class="card login-card w-full shadow-xl flex flex-row overflow-hidden bg-white">

            <!-- md:hidden di template ini berarti sembunyi di layar <= 767px -->
            <div class="bg-brand w-1/2 md:hidden flex flex-col items-center justify-center p-10 text-white text-center">
                <img src="{{ asset('assets/img/happy.svg') }}" alt="Illustration" class="w-64 mb-8">
                <h2 class="h3 font-extrabold mb-4">Welcome Back!</h2>
                <p class="text-sm opacity-75 leading-relaxed">
                    Elevate your workflow with Cleopatra Dashboard. <br>
                    Manage your data with elegance and precision.
                </p>
                <div class="mt-8 flex">
                    <div class="w-2 h-2 bg-white rounded-full mx-1 opacity-100"></div>
                    <div class="w-2 h-2 bg-white rounded-full mx-1 opacity-50"></div>
                    <div class="w-2 h-2 bg-white rounded-full mx-1 opacity-50"></div>
                </div>
            </div>

            <!-- SISI KANAN: Form Login -->
            <div class="form-section flex flex-col justify-center">

                <!-- Logo & Brand -->
                <div class="mb-10">
                    <div class="flex items-center mb-6">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-8 h-8 mr-2">
                        <span class="h5 font-black text-gray-800 tracking-tighter m-0">CLEOPATRA</span>
                    </div>
                    <h3 class="h4 font-bold text-gray-800 mb-1">Account Login</h3>
                    <p class="text-xs text-gray-500">Enter your credentials to access your dashboard.</p>
                </div>

                @error('error')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror

                <!-- Form Login -->
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Email Input -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Email Address</label>
                        <div class="input-icon-wrapper">
                            <i class="fad fa-envelope input-icon"></i>
                            <input type="email" name="email"
                                class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none bg-gray-50 icon-field"
                                placeholder="name@company.com" required>
                        </div>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-bold text-gray-600 uppercase">Password</label>
                        </div>
                        <div class="input-icon-wrapper">
                            <i class="fad fa-lock input-icon"></i>
                            <input type="password" name="password"
                                class="w-full p-3 border border-gray-200 rounded text-sm focus:outline-none bg-gray-50 icon-field"
                                placeholder="••••••••" required>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me -->

                    <div class="mb-8" style="margin-bottom: 15px;">

                        <label class="flex items-center cursor-pointer m-0">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-200"
                                value="true">
                            <span class="ml-2 text-xs text-gray-500">Remember this device</span>
                        </label>

                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="btn-bs-primary w-full py-4 rounded font-bold uppercase tracking-widest shadow-lg">
                        Sign In to Dashboard
                    </button>
                </form>


            </div>
        </div>
    </div>

    <!-- Script dari public/assets -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>

</html>
