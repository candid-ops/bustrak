@extends('layouts.app')
@section('title', 'Manage Drivers')

@section('content')
<style>
    .stats-card {
        background: linear-gradient(135deg, #1a1f5e 0%, #2d3494 100%);
        border-radius: 16px;
        padding: 1.2rem;
        color: white;
        transition: transform 0.2s;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
    }
    
    .stats-number {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1;
    }
    
    .stats-label {
        font-size: 0.75rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .filter-card {
        background: var(--bs-card-bg, white);
        border-radius: 16px;
        padding: 1.2rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.08));
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .search-input {
        border-radius: 12px;
        border: 1px solid var(--bs-border-color, #e0e0e0);
        padding: 0.6rem 1rem;
        width: 100%;
        transition: all 0.2s;
    }
    
    .search-input:focus {
        border-color: #1a1f5e;
        box-shadow: 0 0 0 3px rgba(26,31,94,0.1);
        outline: none;
    }
    
    .filter-select {
        border-radius: 12px;
        border: 1px solid var(--bs-border-color, #e0e0e0);
        padding: 0.6rem 1rem;
        background: var(--bs-card-bg, white);
        cursor: pointer;
    }
    
    .driver-card {
        background: var(--bs-card-bg, white);
        border-radius: 16px;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.08));
        transition: all 0.2s;
        overflow: hidden;
    }
    
    .driver-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    
    .driver-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 1rem;
        border-bottom: 1px solid var(--bs-border-color, rgba(0,0,0,0.08));
    }
    
    .driver-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    
    .driver-details {
        padding: 1rem;
    }
    
    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px dashed var(--bs-border-color, rgba(0,0,0,0.05));
    }
    
    .detail-label {
        font-size: 0.75rem;
        color: var(--bs-secondary-color, #666);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .detail-value {
        font-weight: 600;
        font-size: 0.85rem;
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
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
    }
    
    .empty-icon {
        font-size: 4rem;
        color: var(--bs-secondary-color, #ccc);
        margin-bottom: 1rem;
    }
    
    .assigned-badge {
        background: #e3f2fd;
        color: #1565c0;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        display: inline-block;
    }
    
    @media (prefers-color-scheme: dark) {
        .driver-header {
            background: rgba(255,255,255,0.05);
        }
        
        .status-active {
            background: rgba(46,125,50,0.2);
        }
        
        .status-inactive {
            background: rgba(198,40,40,0.2);
        }
        
        .assigned-badge {
            background: rgba(21,101,192,0.2);
        }
    }
    
    @media (max-width: 768px) {
        .stats-number {
            font-size: 1.2rem;
        }
    }
</style>

<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-1">👨‍✈️ Manage Drivers</h4>
            <p class="text-muted mb-0">Manage your fleet drivers, assign buses, and track performance</p>
        </div>
        <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>Add New Driver
        </a>
    </div>
    
    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-6">
            <div class="stats-card">
                <div class="stats-number">{{ $drivers->total() }}</div>
                <div class="stats-label">Total Drivers</div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #2e7d32, #1b5e20);">
                <div class="stats-number">{{ $drivers->where('status', 'active')->count() }}</div>
                <div class="stats-label">Active Drivers</div>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #6c757d, #495057);">
                <div class="stats-number">{{ $drivers->where('status', 'inactive')->count() }}</div>
                <div class="stats-label">Inactive Drivers</div>
            </div>
        </div>
    </div>
    
    <!-- Filter Bar -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.drivers.index') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold small text-muted">
                        <i class="bi bi-search me-1"></i> Search
                    </label>
                    <input type="text" name="search" class="search-input" 
                           placeholder="Driver name, phone, or licence number..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted">
                        <i class="bi bi-funnel me-1"></i> Filter by Status
                    </label>
                    <select name="status" class="filter-select w-100">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Drivers Grid -->
    @if($drivers->count() > 0)
        <div class="row g-4">
            @foreach($drivers as $driver)
            <div class="col-lg-4 col-md-6">
                <div class="driver-card">
                    <div class="driver-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="driver-name">
                                    <i class="bi bi-person-circle me-2" style="color: #1a1f5e;"></i>
                                    {{ $driver->user->name }}
                                </div>
                                <div class="mt-1">
                                    @if($driver->status === 'active')
                                        <span class="status-badge status-active">
                                            <i class="bi bi-check-circle-fill" style="font-size: 0.6rem;"></i> Active
                                        </span>
                                    @else
                                        <span class="status-badge status-inactive">
                                            <i class="bi bi-x-circle-fill" style="font-size: 0.6rem;"></i> Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">Driver ID</small>
                                <div class="fw-bold" style="font-family: monospace;">#{{ $driver->id }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="driver-details">
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="bi bi-telephone"></i> Phone
                            </span>
                            <span class="detail-value">{{ $driver->user->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="bi bi-card-text"></i> Licence Number
                            </span>
                            <span class="detail-value">{{ $driver->licence_number ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="bi bi-bus-front"></i> Assigned Bus
                            </span>
                            <span class="detail-value">
                                @if($driver->bus)
                                    <span class="assigned-badge">
                                        <i class="bi bi-bus-front me-1"></i> {{ $driver->bus->plate_number }}
                                    </span>
                                @else
                                    <span class="text-muted">— Not assigned —</span>
                                @endif
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="bi bi-calendar3"></i> Joined
                            </span>
                            <span class="detail-value">{{ $driver->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.drivers.edit', $driver) }}" 
                               class="action-btn btn btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.drivers.destroy', $driver) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this driver?')">
                                @csrf
                                @method('DELETE')
                                <button class="action-btn btn btn-outline-danger" style="border: none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
    {{ $drivers->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <h5>No drivers found</h5>
            <p class="text-muted">Try adjusting your search or filter criteria</p>
            <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i>Add Your First Driver
            </a>
        </div>
    @endif
</div>

<script>
    // Auto-submit filter form on select change
    document.querySelector('.filter-select')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    // Search with debounce
    let searchTimeout;
    document.querySelector('.search-input')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });
</script>
@endsection