@extends('layouts.app')
@section('title', 'Edit Driver')

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
        background: linear-gradient(135deg, #ff9800, #e65100);
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
        color: #ff9800;
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
        border-color: #ff9800;
        box-shadow: 0 0 0 3px rgba(255,152,0,0.1);
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
    
    .btn-update {
        background: linear-gradient(135deg, #ff9800, #e65100);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
    }
    
    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255,152,0,0.3);
        background: linear-gradient(135deg, #ffb74d, #ff9800);
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
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .status-active {
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .status-inactive {
        background: #ffebee;
        color: #c62828;
    }
    
    @media (prefers-color-scheme: dark) {
        .info-badge {
            background: rgba(46,125,50,0.2);
        }
        
        .btn-update {
            background: linear-gradient(135deg, #ffb74d, #ff9800);
        }
        
        .btn-cancel {
            background: #6c757d;
        }
        
        .status-active {
            background: rgba(46,125,50,0.2);
        }
        
        .status-inactive {
            background: rgba(198,40,40,0.2);
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
                        <i class="bi bi-pencil-square fs-3"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">Edit Driver</h4>
                        <p class="mb-0">Update driver details for {{ $driver->user->name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="form-body">
                <form action="{{ route('admin.drivers.update', $driver) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Current Status Display -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center p-3" style="background: var(--bs-tertiary-bg, #f8f9fa); border-radius: 12px;">
                            <div>
                                <small class="text-muted">Current Status</small>
                                <div>
                                    @if($driver->status === 'active')
                                        <span class="status-badge status-active">
                                            <i class="bi bi-check-circle-fill"></i> Active
                                        </span>
                                    @else
                                        <span class="status-badge status-inactive">
                                            <i class="bi bi-x-circle-fill"></i> Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <i class="bi bi-info-circle text-muted"></i>
                                <small class="text-muted">You can change status below</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Full Name -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-person"></i> Full Name
                            <span class="info-badge">Required</span>
                        </label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $driver->user->name) }}"
                               placeholder="e.g., John Mwangi"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <!-- Email (readonly) -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control" 
                                   value="{{ $driver->user->email }}" 
                                   readonly disabled
                                   style="background: var(--bs-tertiary-bg, #f8f9fa);">
                            <small class="text-muted" style="font-size: 0.7rem;">
                                Email cannot be changed
                            </small>
                        </div>
                        
                        <!-- Phone -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-telephone"></i> Phone Number
                                <span class="info-badge">Required</span>
                            </label>
                            <input type="text" name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $driver->user->phone) }}"
                                   placeholder="0712345678"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                   value="{{ old('licence_number', $driver->licence_number) }}"
                                   placeholder="e.g., DL123456"
                                   required>
                            @error('licence_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-circle-fill"></i> Status
                            </label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', $driver->status) == 'active' ? 'selected' : '' }}>
                                    ✅ Active - Available for duty
                                </option>
                                <option value="inactive" {{ old('status', $driver->status) == 'inactive' ? 'selected' : '' }}>
                                    ❌ Inactive - Not available
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Assign Bus -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-bus-front"></i> Assign Bus
                        </label>
                        <select name="bus_id" class="form-select">
                            <option value="">-- No Bus --</option>
                            @foreach($buses as $bus)
                                <option value="{{ $bus->id }}"
                                    {{ old('bus_id', $driver->bus_id) == $bus->id ? 'selected' : '' }}>
                                    🚍 {{ $bus->plate_number }} — {{ $bus->name ?? 'Bus' }} ({{ $bus->capacity }} seats)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            <i class="bi bi-info-circle"></i> Assign a bus to this driver
                        </small>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="divider"></div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-clock-history"></i>
                                <span class="small text-muted">Driver since: {{ $driver->created_at->format('d M Y') }}</span>
                            </div>
                            <div>
                                <i class="bi bi-person-badge"></i>
                                <span class="small text-muted">Role: Driver</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-update flex-grow-1">
                            <i class="bi bi-check-lg me-2"></i> Update Driver
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
    
    // Warn if changing status to inactive
    const statusSelect = document.querySelector('select[name="status"]');
    const originalStatus = "{{ $driver->status }}";
    
    statusSelect?.addEventListener('change', function() {
        if (this.value === 'inactive' && originalStatus === 'active') {
            if (!confirm('Warning: Changing status to "Inactive" will prevent this driver from being assigned to trips. Continue?')) {
                this.value = originalStatus;
            }
        }
    });
</script>
@endpush
@endsection