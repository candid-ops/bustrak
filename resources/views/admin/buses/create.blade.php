@extends('layouts.app')
@section('title', 'Add Bus')

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
                        <i class="bi bi-bus-front fs-3"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">Add New Bus</h4>
                        <p class="mb-0">Register a new vehicle to your fleet</p>
                    </div>
                </div>
            </div>
            
            <div class="form-body">
                <form action="{{ route('admin.buses.store') }}" method="POST">
                    @csrf
                    
                    <!-- Plate Number -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-card-text"></i> Plate Number
                            <span class="info-badge">Required</span>
                        </label>
                        <input type="text" name="plate_number"
                               class="form-control @error('plate_number') is-invalid @enderror"
                               value="{{ old('plate_number') }}"
                               placeholder="e.g., KAA 123A, KCD 456B"
                               required>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            <i class="bi bi-info-circle"></i> Enter the official vehicle registration number
                        </small>
                        @error('plate_number')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Bus Name -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-bus-front"></i> Bus Name
                        </label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g., Luxury Express, City Shuttle">
                        <small class="text-muted" style="font-size: 0.7rem;">
                            Optional - Give your bus a friendly name
                        </small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <!-- Capacity -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-people"></i> Capacity
                                <span class="info-badge">Required</span>
                            </label>
                            <input type="number" name="capacity"
                                   class="form-control @error('capacity') is-invalid @enderror"
                                   value="{{ old('capacity') }}"
                                   placeholder="e.g., 50"
                                   min="1"
                                   max="100"
                                   required>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Type -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bi bi-tag"></i> Bus Type
                            </label>
                            <select name="type"
                                    class="form-select @error('type') is-invalid @enderror">
                                <option value="">-- Select Type --</option>
                                <option value="city" {{ old('type')=='city' ? 'selected' : '' }}>
                                    🏙️ City Bus
                                </option>
                                <option value="intercity" {{ old('type')=='intercity' ? 'selected' : '' }}>
                                    🛣️ Intercity Bus
                                </option>
                                <option value="luxury" {{ old('type')=='luxury' ? 'selected' : '' }}>
                                    ✨ Luxury Bus
                                </option>
                                <option value="minibus" {{ old('type')=='minibus' ? 'selected' : '' }}>
                                    🚐 Minibus
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="bi bi-circle-fill"></i> Status
                        </label>
                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                ✅ Active - Ready for operation
                            </option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>
                                🔧 Maintenance - Under repair
                            </option>
                            <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>
                                📦 Retired - Permanently decommissioned
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="divider"></div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-shield-check" style="color: #4caf50;"></i>
                                <span class="small text-muted">All fields marked with <span class="text-danger">*</span> are required</span>
                            </div>
                            <div>
                                <i class="bi bi-database"></i>
                                <span class="small text-muted">Data will be saved securely</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-save flex-grow-1">
                            <i class="bi bi-check-lg me-2"></i> Save Bus
                        </button>
                        <a href="{{ route('admin.buses.index') }}" class="btn-cancel px-4">
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
    // Auto-uppercase plate number
    document.querySelector('input[name="plate_number"]')?.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Validate capacity min/max
    document.querySelector('input[name="capacity"]')?.addEventListener('change', function() {
        let val = parseInt(this.value);
        if (val < 1) this.value = 1;
        if (val > 100) this.value = 100;
    });
</script>
@endpush
@endsection