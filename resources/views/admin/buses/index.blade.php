@extends('layouts.app')
@section('title', 'Manage Buses')

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
    
    .bus-card {
        background: var(--bs-card-bg, white);
        border-radius: 16px;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.08));
        transition: all 0.2s;
        overflow: hidden;
    }
    
    .bus-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    
    .bus-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 1rem;
        border-bottom: 1px solid var(--bs-border-color, rgba(0,0,0,0.08));
    }
    
    .bus-plate {
        font-size: 1.2rem;
        font-weight: 800;
        font-family: monospace;
        letter-spacing: 1px;
    }
    
    .bus-details {
        padding: 1rem;
    }
    
    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px dashed var(--bs-border-color, rgba(0,0,0,0.05));
    }
    
    .detail-label {
        font-size: 0.8rem;
        color: var(--bs-secondary-color, #666);
    }
    
    .detail-value {
        font-weight: 600;
        font-size: 0.9rem;
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
    
    .status-maintenance {
        background: #fff3e0;
        color: #e65100;
    }
    
    .status-retired {
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
    
    @media (prefers-color-scheme: dark) {
        .bus-header {
            background: rgba(255,255,255,0.05);
        }
        
        .status-active {
            background: rgba(46,125,50,0.2);
        }
        
        .status-maintenance {
            background: rgba(230,81,0,0.2);
        }
        
        .status-retired {
            background: rgba(198,40,40,0.2);
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
            <h4 class="mb-1">🚍 Manage Buses</h4>
            <p class="text-muted mb-0">Manage your fleet of buses, track status, and update details</p>
        </div>
        <a href="{{ route('admin.buses.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>Add New Bus
        </a>
    </div>
    
    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stats-card">
                <div class="stats-number">{{ $buses->total() }}</div>
                <div class="stats-label">Total Buses</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #2e7d32, #1b5e20);">
                <div class="stats-number">{{ $buses->where('status', 'active')->count() }}</div>
                <div class="stats-label">Active Buses</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #e65100, #bf360c);">
                <div class="stats-number">{{ $buses->where('status', 'maintenance')->count() }}</div>
                <div class="stats-label">Maintenance</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stats-card" style="background: linear-gradient(135deg, #6c757d, #495057);">
                <div class="stats-number">{{ $buses->where('status', 'retired')->count() }}</div>
                <div class="stats-label">Retired</div>
            </div>
        </div>
    </div>
    
    <!-- Filter Bar -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.buses.index') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold small text-muted">
                        <i class="bi bi-search me-1"></i> Search
                    </label>
                    <input type="text" name="search" class="search-input" 
                           placeholder="Plate number or bus name..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted">
                        <i class="bi bi-funnel me-1"></i> Filter by Status
                    </label>
                    <select name="status" class="filter-select w-100">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.buses.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Buses Grid -->
    @if($buses->count() > 0)
        <div class="row g-4">
            @foreach($buses as $bus)
            <div class="col-lg-4 col-md-6">
                <div class="bus-card">
                    <div class="bus-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-bus-front fs-4 me-2" style="color: #1a1f5e;"></i>
                                <span class="bus-plate">{{ $bus->plate_number }}</span>
                            </div>
                            <div>
                                @if($bus->status === 'active')
                                    <span class="status-badge status-active">
                                        <i class="bi bi-check-circle-fill" style="font-size: 0.6rem;"></i> Active
                                    </span>
                                @elseif($bus->status === 'maintenance')
                                    <span class="status-badge status-maintenance">
                                        <i class="bi bi-tools"></i> Maintenance
                                    </span>
                                @else
                                    <span class="status-badge status-retired">
                                        <i class="bi bi-stop-circle"></i> Retired
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bus-details">
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="bi bi-bus-front"></i> Bus Name
                            </span>
                            <span class="detail-value">{{ $bus->name ?? 'N/A' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="bi bi-person-arms-up"></i> Capacity
                            </span>
                            <span class="detail-value">
                                <i class="bi bi-person me-1"></i> {{ $bus->capacity }} seats
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">
                                <i class="bi bi-tag"></i> Type
                            </span>
                            <span class="detail-value">
                                <span class="badge bg-secondary rounded-pill">
                                    {{ ucfirst($bus->type) }}
                                </span>
                            </span>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.buses.edit', $bus) }}" 
                               class="action-btn btn btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.buses.destroy', $bus) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this bus?')">
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
        <div class="mt-4 d-flex justify-content-center">
            {{ $buses->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-bus-front"></i>
            </div>
            <h5>No buses found</h5>
            <p class="text-muted">Try adjusting your search or filter criteria</p>
            <a href="{{ route('admin.buses.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i>Add Your First Bus
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