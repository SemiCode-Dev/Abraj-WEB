<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Admin Login') }} - ABRAJ STAY</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700,800,900" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #1e3a8a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #1e3a8a 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .login-logo {
            max-width: 200px;
            max-height: 80px;
            width: auto;
            height: auto;
            object-fit: contain;
            margin: 0 auto;
            display: block;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
        }

        .form-group input:focus {
            outline: none;
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .error-message i {
            font-size: 12px;
        }

        .form-group.is-invalid input {
            border-color: #dc2626;
            background-color: #fef2f2;
        }

        .remember-group {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 8px;
            transition: all 0.3s ease;
            background: white;
        }

        .checkbox:checked {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            border-color: #f97316;
            position: relative;
        }

        .checkbox:checked::after {
            content: '✓';
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
        }

        .remember-text {
            color: #666;
            font-size: 14px;
            margin: 0;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(249, 115, 22, 0.4);
            background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .alert-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            font-size: 14px;
        }

        .alert-box i {
            font-size: 16px;
            flex-shrink: 0;
        }

        .help-text {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #666;
        }

        .help-text a {
            color: #f97316;
            text-decoration: none;
            font-weight: 600;
        }

        .help-text a:hover {
            color: #ea580c;
        }

        .help-text a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 15px;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .login-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                @if(file_exists(public_path('images/abraj-stay-logo.png')))
                    <img src="{{ asset('images/abraj-stay-logo.png') }}" alt="ABRAJ STAY" class="login-logo">
                @else
                    <h1>{{ __('Abraj Stay') }}</h1>
                @endif
            </div>

            <div class="login-body">
                @if ($errors->any())
                    <div class="alert-box">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-0">
                    @csrf

                    <div class="form-group @error('email') is-invalid @enderror">
                        <label for="email">{{ __('Email Address') }}</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="{{ __('Enter your email') }}"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-times-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="pb-4 pt-4 form-group @error('password') is-invalid @enderror">
                        <label for="password">{{ __('Password') }}</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="{{ __('Enter your password') }}"
                            required
                        >
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-times-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        {{ __('Login') }}
                    </button>
                </form>

                <div class="help-text">
                    {{ __('Admin Access Only') }} |
                    <a href="{{ route('home') }}">{{ __('Back to Home') }}</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
