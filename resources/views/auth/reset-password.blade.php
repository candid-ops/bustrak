<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BusTrak - Reset Password</title>
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
        .reset-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(26,77,255,0.3);
            box-shadow: 0 0 40px rgba(26,77,255,0.2);
        }
        .reset-card h2 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }
        .reset-card h2 span {
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
            transition: all 0.3s;
            cursor: pointer;
        }
        .btn-reset:hover:not(:disabled) {
            background: linear-gradient(135deg, #3D6FFF, #1A4DFF);
            transform: translateY(-2px);
        }
        .btn-reset:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }
        .alert-success {
            background: rgba(0, 200, 100, 0.15);
            border: 1px solid rgba(0, 200, 100, 0.3);
            color: #00c864;
        }
        .alert-danger {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff6b6b;
        }
        .text-danger {
            color: #ff6b6b;
            font-size: 0.75rem;
            margin-top: 5px;
            display: block;
        }
        .success-check {
            text-align: center;
            margin-bottom: 20px;
        }
        .success-check i {
            font-size: 4rem;
            color: #00c864;
        }
    </style>
</head>
<body>
    <div class="reset-card">
        <h2>Bus<span>Trak</span></h2>
        
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
            <div class="success-check">
                <i class="bi bi-check-circle-fill"></i>
                <p class="text-white-50 mt-2">Your password has been reset successfully!</p>
                <a href="{{ route('login') }}" class="btn-reset mt-3" style="display: inline-block; text-decoration: none; text-align: center;">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Go to Login
                </a>
            </div>
        @else
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock fs-1" style="color: #1A4DFF;"></i>
                <p class="text-white-50 mt-2">Enter your new password below.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('custom.password.update') }}" id="resetForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token ?? '' }}">

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="8">
                    <small class="text-muted" style="color: rgba(255,255,255,0.4); font-size: 0.7rem;">Minimum 8 characters</small>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn-reset" id="resetBtn">
                    <i class="bi bi-arrow-repeat me-2"></i>Reset Password
                </button>
            </form>

            <div class="back-link">
                <a href="{{ route('login') }}">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const resetForm = document.getElementById('resetForm');
        if (resetForm) {
            resetForm.addEventListener('submit', function(e) {
                const btn = document.getElementById('resetBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Resetting...';
            });
        }
    </script>
</body>
</html>