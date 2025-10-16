@extends('layouts.be')

@section('title', 'Orders Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Orders List</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('seller.orders.awaiting-verification') }}" class="btn btn-warning btn-sm">
                                <i class="las la-clock me-1"></i>
                                Awaiting Verification
                                @php
                                    $awaitingCount = App\Models\Order::bySeller(Auth::id())
                                        ->awaitingVerification()
                                        ->count();
                                @endphp
                                @if ($awaitingCount > 0)
                                    <span class="badge bg-danger ms-1">{{ $awaitingCount }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status_order" class="form-label">Status</label>
                                <select name="status" id="status_order" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="awaiting_verification"
                                        {{ request('status') === 'awaiting_verification' ? 'selected' : '' }}>Awaiting
                                        Verification</option>
                                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed
                                    </option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2"><i
                                        class="las la-filter me-1"></i>Filter</button>
                                <a href="{{ route('seller.orders.index') }}" class="btn btn-secondary"><i
                                        class="las la-redo me-1"></i>Reset</a>
                            </div>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="alert alert-success alert-border-left alert-dismissible fade show" role="alert">
                            <i class="ri-check-line me-3 align-middle fs-16"></i><strong>Success!</strong>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                            <i class="ri-alert-line me-3 align-middle fs-16"></i><strong>Error!</strong>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Property</th>
                                    <th>Buyer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Transfer Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">#{{ $order->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($order->property->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $order->property->images->first()->image_path) }}"
                                                        alt="{{ $order->property->title }}" class="avatar-xs rounded me-2">
                                                @else
                                                    <div
                                                        class="avatar-xs bg-light rounded me-2 d-flex align-items-center justify-content-center">
                                                        <i class="las la-home text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <span
                                                        class="fw-medium">{{ Str::limit($order->property->title, 30) }}</span>
                                                    <br><small class="text-muted">Qty: {{ $order->quantity }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-medium">{{ $order->buyer->name }}</span>
                                                <br><small class="text-muted">{{ $order->buyer->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">Rp
                                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $order->status_badge }}">{{ $order->status_label }}</span>
                                        </td>
                                        <td>
                                            @if ($order->hasTransferProof())
                                                <span class="badge bg-success"><i class="las la-check"></i> Transfer Uploaded</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="las la-clock"></i> No Transfer</span>
                                            @endif

                                            @if ($order->status === 'awaiting_verification' && $order->hasTransferProof())
                                                <br><small class="text-warning"><i class="las la-exclamation-triangle"></i>
                                                    Needs Review</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $order->created_at->format('d M Y') }}</span>
                                            <br><small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle btn btn-sm btn-light"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="las la-ellipsis-v"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('seller.orders.show', $order) }}">
                                                            <i class="las la-eye me-2"></i>View Details
                                                        </a>
                                                    </li>
                                                    @if ($order->status === 'awaiting_verification' && $order->hasTransferProof())
                                                        <li>
                                                            <a class="dropdown-item text-warning"
                                                                href="{{ route('seller.orders.show', $order) }}">
                                                                <i class="las la-clipboard-check me-2"></i>Verify Transfer
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if ($order->transfer_proof)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('seller.orders.transfer-proof', $order) }}"
                                                                target="_blank">
                                                                <i class="las la-file-image me-2"></i>View Transfer Proof
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="las la-inbox fs-48 d-block mb-2"></i>
                                                No orders found
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
