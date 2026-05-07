@extends('layouts.app')
@section('title', 'Add Driver')

@section('content')
<style>
    .form-card {
        background: var(--bs-card-bg, white);
        border-radius: 24px;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.08));
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    
    .form-header {
        background: linear-gradient(135deg, #1a1f5e 0%, #2d3494 100%);
        padding: 1.5rem 2rem;
        color: white;
    }
    
    .form-header h4 {
        margin-bottom: 0.25rem;
    }
    
    .form-header p {
        opacity: 0.85;
        font-size: 0.85rem;
        margin-bottom: 0;
    }
    
    .form-body {
        padding: 2rem;
    }
    
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        color: var(--bs-body-color, #333);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-label i {
        color: #1a1f5e;
        width: 20px;
    }
    
    .form-control, .form-select {
        border-radius: 12px;
        border: 1px solid var(--bs-border-color, #e0e0e0);
        padding: 0.75rem 1rem;
        transition: all 0.2s;
        background: var(--bs-card-bg, white);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #1a1f5e;
        box-shadow: 0 0 0 3px rgba(26,31,94,0.1);
        outline: none;
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .invalid-feedback {
        font-size: 0.75rem;
        margin-top: 0.25rem;
        color: #dc3545;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #2196f3, #1976d2);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(33,150,243,0.3);
        background: linear-gradient(135deg, #42a5f5, #2196f3);
    }
    
    .btn-cancel {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        background: #6c757d;
        border: none;
        color: white;
    }
    
    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }
    
    .info-badge {
        background: #e8f5e9;
        border-radius: 50px;
        padding: 0.25rem 0.75rem;
        font-size: 0.7rem;
        color: #2e7d32;
        margin-left: 8px;
    }
    
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--bs-border-color, #ddd), transparent);
        margin: 1.5rem 0;
    }
    
    .password-hint {
        font-size: 0.7rem;
        margin-top: 0.25rem;
    }
    
    @media (prefers-color-scheme: dark) {
        .info-badge {
            background: rgba(46,125,50,0.2);
        }
        
        .btn-save {
            background: linear-gradient(135deg, #42a5f5, #2196f3);
        }
        
        .btn-cancel {
            background: #6c757d;
        }
    }
    
    @media (max-width: 768px) {
        .form-header {
            padding: 1rem 1.5rem;
        }
        
        .form-body {
            padding: 1.5rem;
        }
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="form-card">
            <div class="form-header">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-plus-fill fs-3"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">Add New Driver</h4>
                        <p class="mb-0">Register a driver to your fleet</p>
                    </div>
                </div>
            </div>
            
            <div class="form-body">
                <form action="{{ route('admin.drivers.store') }}" method="POST">
                    @csrf
                    
                    <!-- Full Name -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-person"></i> Full Name
                            <span class="info-badge">Required</span>
                        </label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g., John Mwangi"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <!-- Email -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-envelope"></i> Email Address
                                <span class="info-badge">Required</span>
                            </label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="driver@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-telephone"></i> Phone Number
                                <span class="info-badge">Required</span>
                            </label>
                            <input type="tel" name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}"
                                   placeholder="0712345678"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-key"></i> Password
                                <span class="info-badge">Required</span>
                            </label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min 6 characters"
                                   required>
                            <div class="password-hint text-muted">
                                <i class="bi bi-info-circle"></i> Minimum 6 characters
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Password Confirmation -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-key-fill"></i> Confirm Password
                                <span class="info-badge">Required</span>
                            </label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Re-enter password"
                                   required>
                            <div class="password-hint text-muted">
                                <i class="bi bi-shield-check"></i> Must match the password above
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- License Number -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-card-text"></i> Licence Number
                                <span class="info-badge">Required</span>
                            </label>
                            <input type="text" name="licence_number"
                                   class="form-control @error('licence_number') is-invalid @enderror"
                                   value="{{ old('licence_number') }}"
                                   placeholder="e.g., DL123456"
                                   required>
                            @error('licence_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Assign Bus -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-bus-front"></i> Assign Bus
                            </label>
                            <select name="bus_id" class="form-select">
                                <option value="">-- No Bus (Will be assigned later) --</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                        🚍 {{ $bus->plate_number }} — {{ $bus->name ?? 'Bus' }} ({{ $bus->capacity }} seats)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted" style="font-size: 0.7rem;">
                                <i class="bi bi-info-circle"></i> You can assign a bus later
                            </small>
                        </div>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="divider"></div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-shield-check" style="color: #4caf50;"></i>
                                <span class="small text-muted">Driver will receive login credentials via email</span>
                            </div>
                            <div>
                                <i class="bi bi-person-badge"></i>
                                <span class="small text-muted">Auto-assigns 'driver' role</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-save flex-grow-1">
                            <i class="bi bi-check-lg me-2"></i> Save Driver
                        </button>
                        <a href="{{ route('admin.drivers.index') }}" class="btn-cancel px-4">
                            <i class="bi bi-x-lg me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-format phone number
    document.querySelector('input[name="phone"]')?.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.startsWith('0') && value.length > 10) {
            value = value.slice(0, 10);
        }
        this.value = value;
    });
    
    // Real-time password confirmation check
    const password = document.querySelector('input[name="password"]');
    const confirmPassword = document.querySelector('input[name="password_confirmation"]');
    
    function validatePasswordMatch() {
        if (password && confirmPassword) {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
                confirmPassword.style.borderColor = '#dc3545';
            } else {
                confirmPassword.setCustomValidity('');
                confirmPassword.style.borderColor = '';
            }
        }
    }
    
    password?.addEventListener('input', validatePasswordMatch);
    confirmPassword?.addEventListener('input', validatePasswordMatch);
</script>
@endpush
@endsection