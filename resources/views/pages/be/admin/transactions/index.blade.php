@extends('layouts.be')

@section('title', 'Transaction Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">Transaction Management</h1>
                            <p class="text-muted">Monitor dan kelola semua transaksi</p>
                        </div>
                        <a href="{{ route('admin.transactions.report') }}"
                            class="btn btn-soft-primary waves-effect waves-light" title="Generate Transaction Report">
                            <i class="las la-chart-bar me-2"></i>Generate Report
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mt-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title text-white">Total Transactions</h5>
                                <h3 class="mb-0 text-white">{{ number_format($stats['total_transactions']) }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="las la-exchange-alt fs-3 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title text-white">Success</h5>
                                <h3 class="mb-0 text-white">{{ number_format($stats['success_transactions']) }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="las la-check-circle fs-3 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title text-white">Pending</h5>
                                <h3 class="mb-0 text-white">{{ number_format($stats['pending_transactions']) }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="las la-clock fs-3 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title text-white">Total Revenue</h5>
                                <h3 class="mb-0 text-white">Rp {{ number_format($stats['total_amount']) }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="las la-money-bill-wave fs-3 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.transactions.index') }}">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label for="status_transaction" class="form-label">Status</label>
                                    <select name="status" id="status_transaction" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="processing"
                                            {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>
                                            Success</option>
                                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed
                                        </option>
                                        <option value="cancelled"
                                            {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-select">
                                        <option value="">All Methods</option>
                                        <option value="bank_transfer"
                                            {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank
                                            Transfer</option>
                                        <option value="credit_card"
                                            {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit Card
                                        </option>
                                        <option value="e_wallet"
                                            {{ request('payment_method') === 'e_wallet' ? 'selected' : '' }}>E-Wallet
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="date_from" class="form-label">From Date</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="date_to" class="form-label">To Date</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control"
                                        value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" name="search" id="search" class="form-control"
                                        placeholder="Transaction ID or buyer name..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-1">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="las la-search"></i>
                                        </button>
                                        <a href="{{ route('admin.transactions.index') }}"
                                            class="btn btn-outline-secondary">
                                            <i class="las la-redo"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Buyer</th>
                                        <th>Property</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $transaction)
                                        <tr>
                                            <td>
                                                <code>#{{ $transaction->id }}</code>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $transaction->order->buyer->name }}</h6>
                                                    <small
                                                        class="text-muted">{{ $transaction->order->buyer->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">
                                                        {{ Str::limit($transaction->order->property->title, 25) }}</h6>
                                                    <small class="text-muted">{{ $transaction->order->quantity }}
                                                        coupon(s)</small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>Rp {{ number_format($transaction->amount) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $transaction->status === 'success'
                                                        ? 'success'
                                                        : ($transaction->status === 'failed'
                                                            ? 'danger'
                                                            : ($transaction->status === 'cancelled'
                                                                ? 'secondary'
                                                                : 'warning')) }}">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $transaction->created_at->format('M d, Y H:i') }}</small>
                                                <br><small
                                                    class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.transactions.show', $transaction) }}"
                                                        class="btn btn-sm btn-outline-info">
                                                        <i class="las la-eye"></i>
                                                    </a>
                                                    @if (in_array($transaction->status, ['pending', 'processing']))
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#statusModal{{ $transaction->id }}">
                                                            <i class="las la-edit"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Status Update Modal -->
                                        @if (in_array($transaction->status, ['pending', 'processing']))
                                            <div class="modal fade" id="statusModal{{ $transaction->id }}"
                                                tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('admin.transactions.update-status', $transaction) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Update Transaction Status</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="status{{ $transaction->id }}"
                                                                        class="form-label">Status</label>
                                                                    <select name="status"
                                                                        id="status{{ $transaction->id }}"
                                                                        class="form-select" required>
                                                                        <option value="pending"
                                                                            {{ $transaction->status === 'pending' ? 'selected' : '' }}>
                                                                            Pending</option>
                                                                        <option value="processing"
                                                                            {{ $transaction->status === 'processing' ? 'selected' : '' }}>
                                                                            Processing</option>
                                                                        <option value="success"
                                                                            {{ $transaction->status === 'success' ? 'selected' : '' }}>
                                                                            Success</option>
                                                                        <option value="failed"
                                                                            {{ $transaction->status === 'failed' ? 'selected' : '' }}>
                                                                            Failed</option>
                                                                        <option value="cancelled"
                                                                            {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>
                                                                            Cancelled</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="notes{{ $transaction->id }}"
                                                                        class="form-label">Notes</label>
                                                                    <textarea name="notes" id="notes{{ $transaction->id }}" class="form-control" rows="3"
                                                                        placeholder="Add notes about the status change..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Update
                                                                    Status</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="las la-exchange-alt fs-3 mb-3"></i>
                                                    <p>No transactions found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($transactions->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
