@extends('layouts.app')
@section('title', 'Manage Routes')

@section('content')
<style>
    /* Search and Filter Bar */
    .filter-card {
        background: var(--bs-card-bg, white);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--bs-border-color, rgba(0,0,0,0.08));
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    
    .search-input {
        border-radius: 12px;
        border: 1px solid var(--bs-border-color, #e0e0e0);
        padding: 0.7rem 1rem;
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
        padding: 0.7rem 1rem;
        background: var(--bs-card-bg, white);
        cursor: pointer;
    }
    
    /* Stats Cards */
    .stats-mini {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .stat-mini-card {
        background: linear-gradient(135deg, #1a1f5e 0%, #2d3494 100%);
        border-radius: 16px;
        padding: 1rem 1.5rem;
        flex: 1;
        min-width: 120px;
        color: white;
    }
    
    .stat-mini-card.success {
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
    }
    
    .stat-mini-card.danger {
        background: linear-gradient(135deg, #6c757d, #495057);
    }
    
    .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.7rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.25rem;
    }
    
    /* Empty State */
    .empty-state-modern {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bs-card-bg, white);
        border-radius: 24px;
        border: 2px dashed var(--bs-border-color, #e0e0e0);
    }
    
    .empty-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(26,31,94,0.1), rgba(26,31,94,0.05));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .empty-icon i {
        font-size: 3rem;
        color: #1a1f5e;
        opacity: 0.5;
    }
    
    .empty-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--bs-body-color, #1a1f5e);
    }
    
    .empty-text {
        color: var(--bs-secondary-color, #6c757d);
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    
    [data-bs-theme="dark"] .empty-icon {
        background: linear-gradient(135deg, rgba(126,179,255,0.1), rgba(126,179,255,0.05));
    }
    
    [data-bs-theme="dark"] .empty-icon i {
        color: #7eb3ff;
    }
    
    /* Table Styles */
    .routes-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .routes-table th {
        background: #1a1f5e;
        padding: 1rem;
        font-weight: 600;
        font-size: 0.8rem;
        color: white;
        text-align: left;
    }
    
    .routes-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--bs-border-color, #e9ecef);
        background: var(--bs-card-bg, white);
        color: var(--bs-body-color, #212529);
    }
    
    .routes-table tr:hover td {
        background: rgba(26,31,94,0.03);
    }
    
    /* Type badges */
    .badge-type {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .badge-intercity {
        background: rgba(26,77,255,0.12);
        color: #1A4DFF;
        border: 1px solid rgba(26,77,255,0.3);
    }
    
    .badge-city {
        background: rgba(0,200,100,0.12);
        color: #00a854;
        border: 1px solid rgba(0,200,100,0.3);
    }
    
    /* Status badges */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .badge-active {
        background: rgba(0,200,100,0.12);
        color: #00a854;
    }
    
    .badge-inactive {
        background: rgba(220,53,69,0.12);
        color: #c82333;
    }
    
    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        background: rgba(26,31,94,0.08);
        color: #1a1f5e;
    }
    
    .action-btn.delete {
        background: rgba(220,53,69,0.08);
        color: #dc3545;
    }
    
    .action-btn:hover {
        transform: scale(1.05);
    }
    
    /* Pagination */
    .custom-pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 1.5rem;
    }
    
    [data-bs-theme="dark"] .custom-pagination .page-link {
        background: #1a1d2e;
        border-color: #2a2d3e;
        color: #e0e4ff;
    }
    
    .custom-pagination .page-link {
        background: var(--bs-card-bg, white);
        border: 1px solid var(--bs-border-color, #dee2e6);
        color: var(--bs-body-color, #212529);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        text-decoration: none;
    }
    
    .custom-pagination .page-link:hover,
    .custom-pagination .active .page-link {
        background: #1a1f5e;
        border-color: #1a1f5e;
        color: white;
    }
    
    /* Card header */
    .card-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--bs-border-color, #e9ecef);
    }
</style>

<div class="container-fluid px-0">
    <!-- Header -->
    <div class="routes-header">
        <div>
            <h4 class="mb-0 fw-bold" style="color:#1a1f5e;">
                <i class="bi bi-signpost-2 me-2"></i>Routes Management
            </h4>
            <p class="text-muted small mt-1 mb-0">Manage bus routes, fares, and schedules</p>
        </div>
        <a href="{{ route('admin.routes.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-1"></i> Add Route
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.routes.index') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        <i class="bi bi-search me-1"></i> Search
                    </label>
                    <input type="text" name="search" class="search-input" 
                           placeholder="Search by origin or destination..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        <i class="bi bi-funnel me-1"></i> Filter by Type
                    </label>
                    <select name="type" class="filter-select w-100">
                        <option value="">All Types</option>
                        <option value="city" {{ request('type') == 'city' ? 'selected' : '' }}>🏙️ City</option>
                        <option value="intercity" {{ request('type') == 'intercity' ? 'selected' : '' }}>🛣️ Intercity</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1 invisible">Action</label>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted mb-1 invisible">Reset</label>
                    <a href="{{ route('admin.routes.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="stats-mini">
        <div class="stat-mini-card">
            <div class="stat-number">{{ $routes->total() }}</div>
            <div class="stat-label">Total Routes</div>
        </div>
        <div class="stat-mini-card success">
            <div class="stat-number">{{ $routes->where('is_active', true)->count() }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-mini-card danger">
            <div class="stat-number">{{ $routes->where('is_active', false)->count() }}</div>
            <div class="stat-label">Inactive</div>
        </div>
    </div>

    <!-- Routes Table -->
    @if($routes->count() > 0)
    <div class="card content-card">
        <div class="card-header-custom px-0">
            <span class="fw-semibold"><i class="bi bi-table me-2"></i>Route List</span>
            <span class="text-muted small">Showing {{ $routes->firstItem() }} - {{ $routes->lastItem() }} of {{ $routes->total() }}</span>
        </div>
        <div class="table-responsive">
            <table class="routes-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Fare (KES)</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($routes as $route)
                    <tr>
                        <td>{{ $loop->iteration + ($routes->currentPage() - 1) * $routes->perPage() }}</td>
                        <td class="fw-semibold">{{ $route->origin }}</td>
                        <td class="fw-semibold">{{ $route->destination }}</td>
                        <td>KSh {{ number_format($route->fare, 0) }}</td>
                        <td>
                            <span class="badge-type {{ $route->type === 'intercity' ? 'badge-intercity' : 'badge-city' }}">
                                {{ ucfirst($route->type) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-status {{ $route->is_active ? 'badge-active' : 'badge-inactive' }}">
                                <i class="bi bi-circle-fill" style="font-size: 6px;"></i>
                                {{ $route->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.routes.edit', $route) }}" class="action-btn" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.routes.destroy', $route) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this route? This action cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button class="action-btn delete" title="Delete" style="border: none; cursor: pointer;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="custom-pagination">
        {{ $routes->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @else
    <!-- Beautiful Empty State -->
    <div class="empty-state-modern">
        <div class="empty-icon">
            <i class="bi bi-signpost-2"></i>
        </div>
        <div class="empty-title">No Routes Found</div>
        <div class="empty-text">
            @if(request('search') || request('type'))
                No routes match your search criteria. Try adjusting your filters.
            @else
                You haven't created any routes yet. Get started by adding your first route.
            @endif
        </div>
        <a href="{{ route('admin.routes.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-1"></i> Add Your First Route
        </a>
    </div>
    @endif
</div>

<script>
    // Auto-submit filter on select change
    document.querySelector('.filter-select')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    // Debounced search
    let searchTimeout;
    document.querySelector('.search-input')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });
</script>
@endsection