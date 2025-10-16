@extends('layouts.be')

@section('title', 'Transaction Detail #' . $transaction->id)

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title mb-0">Transaction Detail #{{ $transaction->id }}</h1>
                            <p class="text-muted">Detail informasi transaksi dan order terkait</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-primary">
                                <i class="las la-arrow-left me-1"></i>
                                Back to Transactions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Transaction Info -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="las la-receipt me-2"></i>
                            Transaction Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-medium text-muted">Transaction ID:</td>
                                        <td><code>#{{ $transaction->id }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium text-muted">Amount:</td>
                                        <td class="fw-bold text-success fs-5">Rp
                                            {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium text-muted">Payment Method:</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium text-muted">Status:</td>
                                        <td>
                                            @if ($transaction->status === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($transaction->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($transaction->status === 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-medium text-muted">Created At:</td>
                                        <td>{{ $transaction->created_at->format('M d, Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium text-muted">Updated At:</td>
                                        <td>{{ $transaction->updated_at->format('M d, Y H:i:s') }}</td>
                                    </tr>
                                    @if ($transaction->gateway_response)
                                        <tr>
                                            <td class="fw-medium text-muted">Verified By:</td>
                                            <td>
                                                @if (isset($transaction->gateway_response['verified_by']))
                                                    {{ \App\Models\User::find($transaction->gateway_response['verified_by'])->name ?? 'User #' . $transaction->gateway_response['verified_by'] }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium text-muted">Verified At:</td>
                                            <td>
                                                @if (isset($transaction->gateway_response['verified_at']))
                                                    {{ \Carbon\Carbon::parse($transaction->gateway_response['verified_at'])->format('M d, Y H:i:s') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        @if ($transaction->gateway_response && isset($transaction->gateway_response['transfer_proof']))
                            <div class="mt-3">
                                <h6 class="text-muted mb-2">Transfer Proof:</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ asset('storage/' . $transaction->gateway_response['transfer_proof']) }}"
                                        target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="las la-eye me-1"></i>
                                        View Transfer Proof
                                    </a>
                                    <small
                                        class="text-muted">{{ basename($transaction->gateway_response['transfer_proof']) }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="las la-shopping-bag me-2"></i>
                            Order Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-medium text-muted">Order ID:</td>
                                        <td>
                                            <code>#{{ $transaction->order->id }}</code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium text-muted">Quantity:</td>
                                        <td>{{ $transaction->order->quantity }} coupon(s)</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium text-muted">Total Price:</td>
                                        <td class="fw-bold">Rp
                                            {{ number_format($transaction->order->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium text-muted">Order Status:</td>
                                        <td>
                                            @if ($transaction->order->status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($transaction->order->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($transaction->order->status === 'awaiting_verification')
                                                <span class="badge bg-info">Awaiting Verification</span>
                                            @elseif($transaction->order->status === 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary">{{ ucfirst($transaction->order->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-medium text-muted">Order Date:</td>
                                        <td>{{ $transaction->order->created_at->format('M d, Y H:i:s') }}</td>
                                    </tr>
                                    @if ($transaction->order->paid_at)
                                        <tr>
                                            <td class="fw-medium text-muted">Paid At:</td>
                                            <td>{{ $transaction->order->paid_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                    @endif
                                    @if ($transaction->order->verified_at)
                                        <tr>
                                            <td class="fw-medium text-muted">Verified At:</td>
                                            <td>{{ $transaction->order->verified_at->format('M d, Y H:i:s') }}</td>
                                        </tr>
                                    @endif
                                    @if ($transaction->order->verification_notes)
                                        <tr>
                                            <td class="fw-medium text-muted">Notes:</td>
                                            <td>{{ $transaction->order->verification_notes }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="las la-building me-2"></i>
                            Property Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if ($transaction->order->property->images->count() > 0)
                                    <img src="{{ asset('storage/' . $transaction->order->property->images->first()->image_path) }}"
                                        class="img-fluid rounded" alt="{{ $transaction->order->property->title }}">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <i class="las la-image fs-1 text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h5 class="mb-2">{{ $transaction->order->property->title }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="las la-map-marker-alt"></i>
                                    {{ $transaction->order->property->city }},
                                    {{ $transaction->order->property->province }}
                                </p>
                                <p class="mb-2">{{ Str::limit($transaction->order->property->description, 200) }}</p>

                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <small class="text-muted">Property Value:</small>
                                        <div class="fw-bold">Rp
                                            {{ number_format($transaction->order->property->price, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted">Coupon Price:</small>
                                        <div class="fw-bold text-primary">Rp
                                            {{ number_format($transaction->order->property->coupon_price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <a href="{{ route('admin.properties.show', $transaction->order->property) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="las la-eye me-1"></i>
                                        View Property Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Buyer Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="las la-user me-2"></i>
                            Buyer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div
                                class="avatar avatar-md bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                                {{ strtoupper(substr($transaction->order->buyer->name, 0, 2)) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $transaction->order->buyer->name }}</h6>
                                <small class="text-muted">{{ $transaction->order->buyer->email }}</small>
                            </div>
                        </div>

                        <table class="table table-borderless table-sm">
                            @if ($transaction->order->buyer->phone_number)
                                <tr>
                                    <td class="text-muted">Phone:</td>
                                    <td>{{ $transaction->order->buyer->phone_number }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-muted">Role:</td>
                                <td><span class="badge bg-info">{{ ucfirst($transaction->order->buyer->role) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Joined:</td>
                                <td>{{ $transaction->order->buyer->created_at->format('M d, Y') }}</td>
                            </tr>
                        </table>

                        <div class="mt-3">
                            <a href="{{ route('admin.users.show', $transaction->order->buyer) }}"
                                class="btn btn-sm btn-outline-primary w-100">
                                <i class="las la-user me-1"></i>
                                View Buyer Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Coupons Generated -->
                @if ($transaction->order->coupons->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="las la-ticket-alt me-2"></i>
                                Generated Coupons ({{ $transaction->order->coupons->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach ($transaction->order->coupons as $coupon)
                                    <div class="list-group-item px-0 py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <code class="fs-6">{{ $coupon->coupon_number }}</code>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $coupon->created_at->format('M d, Y H:i') }}</small>
                                            </div>
                                            <div>
                                                @if ($coupon->is_winner)
                                                    <span class="badge bg-warning">Winner</span>
                                                @else
                                                    <span class="badge bg-light text-dark">Active</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="las la-cogs me-2"></i>
                            Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($transaction->status === 'pending')
                            <form action="{{ route('admin.transactions.update-status', $transaction) }}" method="POST"
                                class="mb-2">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success btn-sm w-100 mb-2">
                                    <i class="las la-check me-1"></i>
                                    Mark as Completed
                                </button>
                            </form>

                            <form action="{{ route('admin.transactions.update-status', $transaction) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="failed">
                                <button type="submit" class="btn btn-danger btn-sm w-100"
                                    onclick="return confirm('Are you sure you want to mark this transaction as failed?')">
                                    <i class="las la-times me-1"></i>
                                    Mark as Failed
                                </button>
                            </form>
                        @else
                            <div class="text-center text-muted">
                                <i class="las la-info-circle"></i>
                                Transaction status: <strong>{{ ucfirst($transaction->status) }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar {
            width: 40px;
            height: 40px;
            font-size: 16px;
            font-weight: 600;
        }
    </style>
@endpush
