@extends('layouts.app')
@section('title', 'Manage Bookings')

@section('content')
<style>
    /* Stats Cards - Light/Dark Mode Compatible */
    .stats-row {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    
    .stat-box {
        background: var(--bs-card-bg, #fff);
        border: 1px solid rgba(26,31,94,0.2);
        border-radius: 16px;
        padding: 18px 24px;
        flex: 1;
        min-width: 160px;
        transition: all 0.3s ease;
    }
    
    [data-bs-theme="dark"] .stat-box {
        background: rgba(26,31,94,0.15);
        border-color: rgba(26,31,94,0.3);
    }
    
    .stat-box:hover {
        transform: translateY(-3px);
        border-color: #1a1f5e;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stat-box .stat-icon {
        font-size: 1.8rem;
        margin-bottom: 8px;
    }
    
    .stat-box .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1f5e;
    }
    
    [data-bs-theme="dark"] .stat-box .stat-number {
        color: #7eb3ff;
    }
    
    .stat-box .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    [data-bs-theme="dark"] .stat-box .stat-label {
        color: rgba(255,255,255,0.5);
    }
    
    .stat-box.confirmed .stat-icon { color: #00c864; }
    .stat-box.pending .stat-icon { color: #FF8C42; }
    .stat-box.cancelled .stat-icon { color: #dc3545; }
    .stat-box.total .stat-icon { color: #1a1f5e; }
    
    /* Filter Bar */
    .filter-bar {
        background: var(--bs-card-bg, #fff);
        border: 1px solid rgba(26,31,94,0.15);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }
    
    [data-bs-theme="dark"] .filter-bar {
        background: rgba(26,31,94,0.1);
        border-color: rgba(26,31,94,0.2);
    }
    
    .filter-group {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-select {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 6px 12px;
        color: #212529;
        font-size: 0.8rem;
        cursor: pointer;
    }
    
    [data-bs-theme="dark"] .filter-select {
        background: #1a1d2e;
        border-color: #2a2d3e;
        color: #e0e4ff;
    }
    
    .filter-select:focus {
        border-color: #1a1f5e;
        outline: none;
    }
    
    .search-input {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 6px 12px;
        color: #212529;
        width: 220px;
        font-size: 0.8rem;
    }
    
    [data-bs-theme="dark"] .search-input {
        background: #1a1d2e;
        border-color: #2a2d3e;
        color: #e0e4ff;
    }
    
    .search-input::placeholder {
        color: #6c757d;
    }
    
    [data-bs-theme="dark"] .search-input::placeholder {
        color: rgba(255,255,255,0.4);
    }
    
    /* Table Styles */
    .booking-table {
        width: 100%;
    }
    
    .booking-table th {
        background: #1a1f5e;
        padding: 12px 15px;
        font-weight: 600;
        font-size: 0.8rem;
        color: white;
        border-bottom: none;
    }
    
    .booking-table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        background: #fff;
        color: #212529;
    }
    
    [data-bs-theme="dark"] .booking-table td {
        background: #0f1117;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        color: #e0e4ff;
    }
    
    .booking-table tr:hover td {
        background: #f8f9fa;
    }
    
    [data-bs-theme="dark"] .booking-table tr:hover td {
        background: rgba(26,31,94,0.3);
    }
    
    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .status-confirmed {
        background: rgba(0,200,100,0.12);
        color: #00a854;
        border: 1px solid rgba(0,200,100,0.3);
    }
    
    [data-bs-theme="dark"] .status-confirmed {
        background: rgba(0,200,100,0.2);
        color: #00c864;
    }
    
    .status-pending {
        background: rgba(255,140,66,0.12);
        color: #e67e22;
        border: 1px solid rgba(255,140,66,0.3);
    }
    
    [data-bs-theme="dark"] .status-pending {
        background: rgba(255,140,66,0.2);
        color: #FF8C42;
    }
    
    .status-cancelled {
        background: rgba(220,53,69,0.12);
        color: #c82333;
        border: 1px solid rgba(220,53,69,0.3);
    }
    
    [data-bs-theme="dark"] .status-cancelled {
        background: rgba(220,53,69,0.2);
        color: #dc3545;
    }
    
    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 8px;
    }
    
    .action-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
    }
    
    .action-icon.view {
        background: rgba(26,31,94,0.1);
        color: #1a1f5e;
        border: 1px solid rgba(26,31,94,0.2);
    }
    
    .action-icon.view:hover {
        background: rgba(26,31,94,0.2);
        transform: scale(1.05);
    }
    
    [data-bs-theme="dark"] .action-icon.view {
        background: rgba(26,31,94,0.3);
        color: #7eb3ff;
    }
    
    .action-icon.cancel {
        background: rgba(220,53,69,0.1);
        color: #dc3545;
        border: 1px solid rgba(220,53,69,0.2);
    }
    
    .action-icon.cancel:hover {
        background: rgba(220,53,69,0.2);
        transform: scale(1.05);
    }
    
    /* Pagination */
    .custom-pagination {
        display: flex;
        justify-content: center;
        gap: 5px;
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
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }
    
    .empty-state i {
        font-size: 3.5rem;
        opacity: 0.4;
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
    
    /* Reference text */
    .reference {
        font-family: monospace;
        font-size: 0.85rem;
        color: #1a1f5e;
        font-weight: 600;
    }
    
    [data-bs-theme="dark"] .reference {
        color: #7eb3ff;
    }
    
    /* Seat badge */
    .seat-badge {
        background: #e9ecef;
        color: #495057;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 0.7rem;
        display: inline-block;
    }
    
    [data-bs-theme="dark"] .seat-badge {
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.7);
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
</style>

<!-- Stats Cards -->
<div class="stats-row">
    <div class="stat-box total">
        <div class="stat-icon"><i class="bi bi-ticket-perforated"></i></div>
        <div class="stat-number">{{ $bookings->total() }}</div>
        <div class="stat-label">Total Bookings</div>
    </div>
    <div class="stat-box confirmed">
        <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-number">{{ $bookings->where('status', 'confirmed')->count() }}</div>
        <div class="stat-label">Confirmed</div>
    </div>
    <div class="stat-box pending">
        <div class="stat-icon"><i class="bi bi-clock-fill"></i></div>
        <div class="stat-number">{{ $bookings->where('status', 'pending')->count() }}</div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-box cancelled">
        <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
        <div class="stat-number">{{ $bookings->where('status', 'cancelled')->count() }}</div>
        <div class="stat-label">Cancelled</div>
    </div>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <div class="filter-group">
        <select class="filter-select" id="statusFilter">
            <option value="all">All Status</option>
            <option value="confirmed">Confirmed</option>
            <option value="pending">Pending</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <input type="text" class="search-input" id="searchInput" placeholder="Search reference or customer...">
    </div>
    <div class="filter-group">
        <span class="text-muted small">
            <i class="bi bi-info-circle"></i> Showing {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }}
        </span>
    </div>
</div>

<!-- Bookings Table -->
<div class="card content-card">
    <div class="card-header">
        <i class="bi bi-table me-2"></i> Booking List
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table booking-table mb-0" id="bookingsTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Route</th>
                        <th>Travel Date</th>
                        <th>Seat</th>
                        <th>Status</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}</td>
                        <td>
                            <span class="reference">{{ $booking->booking_reference ?? $booking->reference }}</span>
                        </td>
                        <td>
                            <i class="bi bi-person-circle me-1 text-muted"></i>
                            {{ $booking->user->name ?? 'N/A' }}
                        </td>
                        <td>
                            <small>
                                <i class="bi bi-signpost-2 me-1 text-muted"></i>
                                {{ $booking->schedule->route->origin ?? 'N/A' }}
                                <i class="bi bi-arrow-right mx-1" style="font-size: 10px;"></i>
                                {{ $booking->schedule->route->destination ?? 'N/A' }}
                            </small>
                        </td>
                        <td>
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            {{ \Carbon\Carbon::parse($booking->departure_date)->format('d M Y') }}
                        </td>
                        <td>
                            <span class="seat-badge">
                                <i class="bi bi-chair me-1"></i>
                                {{ $booking->seat_numbers ?? $booking->seat_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusClass = match($booking->status) {
                                    'confirmed' => 'status-confirmed',
                                    'pending' => 'status-pending',
                                    'cancelled' => 'status-cancelled',
                                    default => ''
                                };
                                $statusIcon = match($booking->status) {
                                    'confirmed' => 'check-circle-fill',
                                    'pending' => 'clock-fill',
                                    'cancelled' => 'x-circle-fill',
                                    default => 'question-circle'
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                <i class="bi bi-{{ $statusIcon }}" style="font-size: 10px;"></i>
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="action-icon view" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="d-inline" onsubmit="return confirmCancel()">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-icon cancel" title="Cancel Booking">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-ticket-perforated"></i>
                                <h5 class="mt-3">No Bookings Yet</h5>
                                <p class="text-muted small">Bookings will appear here once customers make reservations</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="custom-pagination">
    {{ $bookings->links('pagination::bootstrap-5') }}
</div>

<script>
    // Filter functionality
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('bookingsTable');
    
    function filterTable() {
        if (!table) return;
        
        const status = statusFilter?.value || 'all';
        const search = (searchInput?.value || '').toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            if (row.querySelector('.empty-state')) return;
            
            let show = true;
            
            if (status !== 'all') {
                const statusCell = row.querySelector('td:nth-child(7) .status-badge');
                const rowStatus = statusCell?.innerText?.toLowerCase().trim() || '';
                if (!rowStatus.includes(status)) {
                    show = false;
                }
            }
            
            if (search && show) {
                const reference = row.querySelector('td:nth-child(2)')?.innerText?.toLowerCase() || '';
                const customer = row.querySelector('td:nth-child(3)')?.innerText?.toLowerCase() || '';
                if (!reference.includes(search) && !customer.includes(search)) {
                    show = false;
                }
            }
            
            row.style.display = show ? '' : 'none';
        });
    }
    
    statusFilter?.addEventListener('change', filterTable);
    searchInput?.addEventListener('keyup', filterTable);
    
    function confirmCancel() {
        return confirm('⚠️ Cancel this booking?\n\nThis action cannot be undone. The customer will be notified.');
    }
</script>
@endsection