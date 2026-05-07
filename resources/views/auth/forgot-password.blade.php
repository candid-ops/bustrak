<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BusTrak - Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0B0F1A 0%, #1a1f5e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .forgot-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(26,77,255,0.3);
            box-shadow: 0 0 40px rgba(26,77,255,0.2);
        }
        .forgot-card h2 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }
        .forgot-card h2 span {
            color: #FF8C42;
        }
        .form-label {
            color: rgba(255,255,255,0.7);
        }
        .form-control {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 10px;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.15);
            border-color: #1A4DFF;
            box-shadow: 0 0 10px rgba(26,77,255,0.5);
            color: white;
        }
        .btn-reset {
            background: linear-gradient(135deg, #1A4DFF, #0d2baa);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            color: white;
        }
        .btn-reset:hover {
            background: linear-gradient(135deg, #3D6FFF, #1A4DFF);
            transform: translateY(-2px);
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
        }
        .back-link a:hover {
            color: white;
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="forgot-card">
        <h2>Bus<span>Trak</span></h2>
        
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="text-center mb-4">
            <i class="bi bi-key fs-1" style="color: #FF8C42;"></i>
            <p class="text-white-50 mt-2">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn-reset">Send Password Reset Link</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left me-1"></i> Back to Login
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>