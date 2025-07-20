<div class="flex-grow-1 p-4">
    <h4>Overview</h4>
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="bg-primary text-white p-3 rounded">
                <h5>👥 Members</h5>
                <p class="mb-1">Total: <strong>{{ number_format($activeMembers) }}</strong></p>
                <small>Active: {{ number_format($activeMembers) }} members</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="bg-success text-white p-3 rounded">
                <h5>📘 Books</h5>
                <p class="mb-1">Total: <strong>{{ number_format($totalBooks) }}</strong></p>
                <small>Available: {{ number_format($availableBooks) }} books</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="bg-warning text-dark p-3 rounded">
                <h5>📝 Loans</h5>
                <p class="mb-1">Active: <strong>{{ number_format($activeLoans) }}</strong></p>
                <small>Books on Loan</small>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="bg-danger text-white p-3 rounded">
                <h5>⏰ Returns</h5>
                <p class="mb-1">Overdue: <strong>{{ number_format($totalOverdue) }}</strong></p>
                <small>Overdue Books</small>
            </div>
        </div>
    </div>

    <!-- Additional Statistics (Optional) -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6>Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h5 class="text-primary">{{ $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 1) : 0 }}%</h5>
                            <small class="text-muted">Member Activity Rate</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h5 class="text-success">{{ $totalBooks > 0 ? round(($availableBooks / $totalBooks) * 100, 1) : 0 }}%</h5>
                            <small class="text-muted">Book Availability</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h5 class="text-warning">{{ $activeLoans > 0 ? round(($activeLoans / $totalBooks) * 100, 1) : 0 }}%</h5>
                            <small class="text-muted">Utilization Rate</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <h5 class="text-danger">{{ $activeLoans > 0 ? round(($totalOverdue / $activeLoans) * 100, 1) : 0 }}%</h5>
                            <small class="text-muted">Overdue Rate</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>