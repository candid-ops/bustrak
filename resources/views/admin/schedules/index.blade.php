@extends('layouts.app')
@section('title', 'Manage Schedules')

@section('content')
<style>
    /* Pagination Styling */
    .custom-pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    
    .custom-pagination .page-item {
        list-style: none;
    }
    
    .custom-pagination .page-link {
        background: #fff;
        border: 1px solid #dee2e6;
        color: #212529;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    [data-bs-theme="dark"] .custom-pagination .page-link {
        background: #1a1d2e;
        border-color: #2a2d3e;
        color: #e0e4ff;
    }
    
    .custom-pagination .page-link:hover {
        background: #1a1f5e;
        border-color: #1a1f5e;
        color: white;
    }
    
    .custom-pagination .active .page-link {
        background: #1a1f5e;
        border-color: #1a1f5e;
        color: white;
    }
    
    /* Stats Cards */
    .stats-mini {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .stat-mini-card {
        background: #fff;
        border: 1px solid rgba(26,31,94,0.2);
        border-radius: 12px;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
    }
    
    [data-bs-theme="dark"] .stat-mini-card {
        background: rgba(26,31,94,0.15);
        border-color: rgba(26,31,94,0.3);
    }
    
    .stat-mini-card:hover {
        transform: translateY(-2px);
        border-color: #1a1f5e;
    }
    
    .stat-mini-icon {
        width: 40px;
        height: 40px;
        background: rgba(26,31,94,0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #1a1f5e;
    }
    
    [data-bs-theme="dark"] .stat-mini-icon {
        background: rgba(26,31,94,0.3);
        color: #7eb3ff;
    }
    
    .stat-mini-info h5 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #1a1f5e;
    }
    
    [data-bs-theme="dark"] .stat-mini-info h5 {
        color: #7eb3ff;
    }
    
    .stat-mini-info span {
        font-size: 0.7rem;
        color: #6c757d;
    }
    
    [data-bs-theme="dark"] .stat-mini-info span {
        color: rgba(255,255,255,0.5);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #6c757d;
    }
    
    [data-bs-theme="dark"] .empty-state i {
        color: rgba(255,255,255,0.2);
    }
    
    .empty-state h5 {
        color: #212529;
    }
    
    [data-bs-theme="dark"] .empty-state h5 {
        color: #e0e4ff;
    }
    
    .empty-state p {
        color: #6c757d;
    }
    
    [data-bs-theme="dark"] .empty-state p {
        color: rgba(255,255,255,0.5);
    }
    
    /* Status Badges */
    .badge-active {
        background: rgba(0,200,100,0.12);
        color: #00a854;
        border: 1px solid rgba(0,200,100,0.3);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    [data-bs-theme="dark"] .badge-active {
        background: rgba(0,200,100,0.2);
        color: #00c864;
    }
    
    .badge-inactive {
        background: rgba(220,53,69,0.12);
        color: #c82333;
        border: 1px solid rgba(220,53,69,0.3);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    [data-bs-theme="dark"] .badge-inactive {
        background: rgba(220,53,69,0.2);
        color: #dc3545;
    }
    
    /* Table row actions */
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        background: rgba(26,31,94,0.1);
        color: #1a1f5e;
        border: 1px solid rgba(26,31,94,0.2);
    }
    
    [data-bs-theme="dark"] .action-btn {
        background: rgba(26,31,94,0.3);
        color: #7eb3ff;
        border-color: rgba(26,31,94,0.3);
    }
    
    .action-btn:hover {
        transform: scale(1.05);
        background: rgba(26,31,94,0.2);
    }
    
    .action-btn.delete {
        background: rgba(220,53,69,0.1);
        color: #dc3545;
        border-color: rgba(220,53,69,0.2);
    }
    
    .action-btn.delete:hover {
        background: rgba(220,53,69,0.2);
    }
    
    /* Tooltip */
    [data-tooltip] {
        position: relative;
        cursor: pointer;
    }
    
    [data-tooltip]:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 4px 8px;
        background: #1a1f5e;
        color: white;
        font-size: 0.7rem;
        border-radius: 6px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
        margin-bottom: 5px;
    }
    
    [data-tooltip]:hover:before {
        opacity: 1;
    }
    
    /* Table Styles */
    .schedules-table th {
        background: #1a1f5e;
        padding: 12px 15px;
        font-weight: 600;
        font-size: 0.8rem;
        color: white;
        border-bottom: none;
    }
    
    .schedules-table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        background: #fff;
        color: #212529;
    }
    
    [data-bs-theme="dark"] .schedules-table td {
        background: #0f1117;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        color: #e0e4ff;
    }
    
    .schedules-table tr:hover td {
        background: #f8f9fa;
    }
    
    [data-bs-theme="dark"] .schedules-table tr:hover td {
        background: rgba(26,31,94,0.3);
    }
    
    /* Card header */
    .content-card .card-header {
        background: #fff;
        border-bottom: 1px solid #e9ecef;
    }
    
    [data-bs-theme="dark"] .content-card .card-header {
        background: #0f1117;
        border-bottom-color: rgba(255,255,255,0.08);
    }
    
    /* Text muted */
    .text-muted {
        color: #6c757d !important;
    }
    
    [data-bs-theme="dark"] .text-muted {
        color: rgba(255,255,255,0.5) !important;
    }
</style>

<div class="schedules-container">
    <!-- Header with Stats -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="mb-0 fw-bold" style="color:#1a1f5e;">
                <i class="bi bi-calendar3 me-2"></i>Schedules Management
            </h4>
            <p class="text-muted small mt-1">Manage bus departure schedules and routes</p>
        </div>
        <div>
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> Add Schedule
            </a>
        </div>
    </div>

    <!-- Mini Stats Cards -->
    <div class="stats-mini">
        <div class="stat-mini-card">
            <div class="stat-mini-icon">
                <i class="bi bi-calendar-week"></i>
            </div>
            <div class="stat-mini-info">
                <h5>{{ $schedules->total() }}</h5>
                <span>Total Schedules</span>
            </div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-mini-info">
                <h5>{{ $schedules->where('status', 'active')->count() }}</h5>
                <span>Active</span>
            </div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-icon">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-mini-info">
                <h5>{{ $schedules->where('status', 'inactive')->count() }}</h5>
                <span>Inactive</span>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card content-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-table me-2"></i>Schedule List</span>
            <span class="text-muted small" id="rowCount">Showing {{ $schedules->firstItem() ?? 0 }} - {{ $schedules->lastItem() ?? 0 }} of {{ $schedules->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table schedules-table mb-0" id="schedulesTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Route</th>
                            <th>Bus</th>
                            <th>Driver</th>
                            <th>Departure Time</th>
                            <th>Status</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                        <tr>
                            <td>{{ $loop->iteration + ($schedules->currentPage() - 1) * $schedules->perPage() }}</td>
                            <td class="fw-semibold">
                                <i class="bi bi-signpost-2 me-1 text-muted"></i>
                                {{ $schedule->route->origin ?? 'N/A' }} → {{ $schedule->route->destination ?? 'N/A' }}
                            </td>
                            <td>
                                <i class="bi bi-bus-front me-1 text-muted"></i>
                                {{ $schedule->bus->plate_number ?? 'N/A' }}
                            </td>
                            <td>
                                <i class="bi bi-person-badge me-1 text-muted"></i>
                                {{ $schedule->driver->user->name ?? 'N/A' }}
                            </td>
                            <td>
                                <i class="bi bi-clock me-1 text-muted"></i>
                                <span class="font-monospace">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</span>
                            </td>
                            <td>
                                @if($schedule->status === 'active')
                                    <span class="badge-active">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Active
                                    </span>
                                @else
                                    <span class="badge-inactive">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.schedules.edit', $schedule) }}"
                                       class="action-btn" data-tooltip="Edit Schedule">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('⚠️ Delete this schedule?\nThis action cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button class="action-btn delete" data-tooltip="Delete Schedule" style="border: none; cursor: pointer;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-calendar-x"></i>
                                    <h5 class="mt-3">No Schedules Found</h5>
                                    <p class="text-muted small">Get started by creating your first schedule</p>
                                    <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="bi bi-plus-lg me-1"></i> Add Schedule
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Custom Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-3">
        <div class="text-muted small">
            <i class="bi bi-info-circle me-1"></i>
            Showing page {{ $schedules->currentPage() }} of {{ $schedules->lastPage() }}
        </div>
        <div class="custom-pagination">
            {{ $schedules->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
    // Add bulk select functionality (optional)
    document.querySelectorAll('.select-row').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const anyChecked = document.querySelectorAll('.select-row:checked').length > 0;
            document.querySelector('.bulk-actions').classList.toggle('show', anyChecked);
        });
    });
</script>
@endsection