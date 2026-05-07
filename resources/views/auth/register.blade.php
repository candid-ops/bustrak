<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BusTrak - Register</title>
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
            padding: 20px;
        }
        .register-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            border: 1px solid rgba(26,77,255,0.3);
            box-shadow: 0 0 40px rgba(26,77,255,0.2);
        }
        .register-card h2 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }
        .register-card h2 span {
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
        .form-control::placeholder {
            color: rgba(255,255,255,0.4);
        }
        .btn-register {
            background: linear-gradient(135deg, #1A4DFF, #0d2baa);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #3D6FFF, #1A4DFF);
            transform: translateY(-2px);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: rgba(255,255,255,0.6);
        }
        .login-link a {
            color: #FF8C42;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .back-home {
            text-align: center;
            margin-top: 15px;
        }
        .back-home a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 14px;
        }
        .back-home a:hover {
            color: white;
        }
        .alert {
            border-radius: 10px;
        }
        hr {
            border-color: rgba(255,255,255,0.1);
            margin: 25px 0 15px;
        }
        .demo-text {
            font-size: 12px;
            color: rgba(255,255,255,0.4);
            text-align: center;
        }
        .demo-text p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <h2>Bus<span>Trak</span></h2>
        
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-register">Register</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
        <div class="back-home">
            <a href="/">← Back to Home</a>
        </div>

        <hr>
        <div class="demo-text">
            <p class="mb-1">Demo Accounts:</p>
            <p class="mb-0">Admin: super@admin.com / password123</p>
            <p class="mb-0">Customer: customer@example.com / customer123</p>
            <p class="mb-0">Driver: driver_new@example.com / driver123</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>