@extends('layouts.be')

@section('title', 'Transaction Report')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title mb-0">Transaction Report</h1>
                            <p class="text-muted">Laporan transaksi berdasarkan periode tanggal</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-soft-primary waves-effect waves-light"
                                onclick="printReport()">
                                <i class="bx bx-printer me-1"></i>
                                Print Report
                            </button>
                            <button type="button" class="btn btn-success" onclick="exportReport()">
                                <i class="las la-file-excel me-1"></i>
                                Export Excel
                            </button>
                            <div class="d-flex flex-column flex-lg-row gap-2 justify-content-lg-end">
                                <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-primary">
                                    <i class="las la-arrow-left me-1"></i>
                                    Back to Properties
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Filters -->
        <div class="row mb-4 mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-filter me-2"></i>
                            Report Parameters
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.transactions.report') }}" id="reportForm">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="date_from" class="form-label">From Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="date_from" id="date_from" class="form-control"
                                        value="{{ request('date_from') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="date_to" class="form-label">To Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="date_to" id="date_to" class="form-control"
                                        value="{{ request('date_to') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-select">
                                        <option value="">All Methods</option>
                                        <option value="bank_transfer"
                                            {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>
                                            Bank Transfer
                                        </option>
                                        <option value="credit_card"
                                            {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>
                                            Credit Card
                                        </option>
                                        <option value="e_wallet"
                                            {{ request('payment_method') === 'e_wallet' ? 'selected' : '' }}>
                                            E-Wallet
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-label waves-effect waves-light"><i
                                                class="bx bx-chart label-icon align-middle fs-16 me-2"></i>
                                            Generate</button>
                                        <a href="{{ route('admin.transactions.report') }}"
                                            class="btn btn-outline-secondary">
                                            Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (isset($transactions))
            <!-- Report Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Report Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Period Information</h6>
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td class="fw-medium">Report Period:</td>
                                            <td>{{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }} -
                                                {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">Total Days:</td>
                                            <td>{{ \Carbon\Carbon::parse(request('date_from'))->diffInDays(\Carbon\Carbon::parse(request('date_to'))) + 1 }}
                                                days</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">Generated At:</td>
                                            <td>{{ now()->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium">Status Filter:</td>
                                            <td><span class="badge bg-success">Success Only</span></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Financial Summary</h6>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="p-3 border rounded">
                                                <h4 class="text-primary mb-1">
                                                    {{ number_format($summary['total_transactions']) }}</h4>
                                                <small class="text-muted">Total Transactions</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 border rounded">
                                                <h4 class="text-success mb-1">Rp
                                                    {{ number_format($summary['total_amount']) }}</h4>
                                                <small class="text-muted">Total Revenue</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method Breakdown -->
            @if ($summary['by_payment_method']->count() > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Payment Method Breakdown
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($summary['by_payment_method'] as $method => $data)
                                        <div class="col-md-4 mb-3">
                                            <div class="p-3 border rounded">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">{{ ucwords(str_replace('_', ' ', $method)) }}</h6>
                                                    <span
                                                        class="badge bg-light text-dark">{{ number_format($data['count']) }}
                                                        tx</span>
                                                </div>
                                                <h5 class="text-success mb-0">Rp {{ number_format($data['amount']) }}</h5>
                                                <small
                                                    class="text-muted">{{ number_format(($data['amount'] / $summary['total_amount']) * 100, 1) }}%
                                                    of total</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Detailed Transactions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2"></i>
                                Detailed Transaction List
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" id="reportTable">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Transaction ID</th>
                                            <th>Buyer</th>
                                            <th>Property</th>
                                            <th>Payment Method</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $transaction)
                                            <tr>
                                                <td>
                                                    <small>{{ $transaction->created_at->format('M d, Y') }}</small>
                                                    <br><small
                                                        class="text-muted">{{ $transaction->created_at->format('H:i:s') }}</small>
                                                </td>
                                                <td>
                                                    <code>#{{ $transaction->id }}</code>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0 fs-6">{{ $transaction->order->buyer->name }}</h6>
                                                        <small
                                                            class="text-muted">{{ $transaction->order->buyer->email }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-0 fs-6">
                                                            {{ Str::limit($transaction->order->property->title, 30) }}</h6>
                                                        <small class="text-muted">{{ $transaction->order->quantity }}
                                                            coupon(s)</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        {{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong class="text-success">Rp
                                                        {{ number_format($transaction->amount) }}</strong>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-success">{{ ucfirst($transaction->status) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                                                        <p>No transactions found for the selected period</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($transactions->count() > 0)
                                        <tfoot>
                                            <tr class="table-secondary">
                                                <th colspan="5" class="text-end">Total:</th>
                                                <th><strong>Rp {{ number_format($summary['total_amount']) }}</strong></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Generate Transaction Report</h5>
                            <p class="text-muted">Please select a date range and click "Generate Report" to view
                                transaction data.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Print Styles -->
    <style>
        @media print {

            .page-header,
            .btn,
            .card:first-child,
            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .table {
                font-size: 12px;
            }

            body {
                background: white !important;
            }
        }
    </style>
@endsection

@push('scripts')
    <script>
        // Print Report Function
        function printReport() {
            if (!document.querySelector('#reportTable')) {
                alert('Please generate a report first before printing.');
                return;
            }

            // Add print title
            const originalTitle = document.title;
            document.title = 'Transaction Report - ' + document.querySelector('input[name="date_from"]').value + ' to ' +
                document.querySelector('input[name="date_to"]').value;

            window.print();

            // Restore original title
            document.title = originalTitle;
        }

        // Export Excel Function (placeholder - would need actual implementation)
        function exportReport() {
            if (!document.querySelector('#reportTable')) {
                alert('Please generate a report first before exporting.');
                return;
            }

            // This would typically call a backend endpoint to generate Excel
            // For now, we'll show a message
            alert(
                'Excel export functionality will be implemented. For now, you can use the print function and save as PDF.'
                );
        }

        // Auto-set date_to when date_from is changed
        document.getElementById('date_from').addEventListener('change', function() {
            const dateToInput = document.getElementById('date_to');
            if (!dateToInput.value) {
                dateToInput.value = this.value;
            }
        });

        // Validate date range
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            const dateFrom = new Date(document.getElementById('date_from').value);
            const dateTo = new Date(document.getElementById('date_to').value);

            if (dateFrom > dateTo) {
                e.preventDefault();
                alert('Start date cannot be later than end date.');
                return false;
            }

            // Check if date range is too large (more than 1 year)
            const daysDiff = (dateTo - dateFrom) / (1000 * 60 * 60 * 24);
            if (daysDiff > 365) {
                if (!confirm(
                        'You are generating a report for more than 1 year. This may take some time. Continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert) {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            if (alert.parentNode) {
                                alert.parentNode.removeChild(alert);
                            }
                        }, 500);
                    }
                }, 5000);
            });
        });
    </script>
@endpush
