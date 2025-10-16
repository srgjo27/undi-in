@extends('layouts.be')

@section('title', 'Order Details #' . $order->id)

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <!-- Order Information -->
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Order Information</h5>
                        </div>
                        <div class="col-auto">
                            <span class="badge {{ $order->status_badge }} fs-12">{{ $order->status_label }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Order ID</label>
                                <p class="fw-semibold">#{{ $order->id }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Order Date</label>
                                <p>{{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Payment Method</label>
                                <p>
                                    <span class="badge bg-info"><i class="las la-university me-1"></i>Manual Bank
                                        Transfer</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Total Amount</label>
                                <p class="fw-bold text-primary fs-18">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Quantity</label>
                                <p>{{ $order->quantity }} items</p>
                            </div>
                            @if ($order->paid_at)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Paid At</label>
                                    <p>{{ $order->paid_at->format('d M Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Property Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if ($order->property->images->count() > 0)
                                <img src="{{ asset('storage/' . $order->property->images->first()->image_path) }}"
                                    alt="{{ $order->property->title }}" class="img-fluid rounded">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <i class="las la-home fs-48 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h5>{{ $order->property->title }}</h5>
                            <p class="text-muted">{{ Str::limit($order->property->description, 200) }}</p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p><strong>Location:</strong> {{ $order->property->location }}</p>
                                    <p><strong>Price per Item:</strong> Rp
                                        {{ number_format($order->property->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong>Status:</strong> {{ ucfirst($order->property->status) }}</p>
                                    @if ($order->property->images->count() > 1)
                                        <p><strong>Images:</strong> {{ $order->property->images->count() }} photos</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buyer Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Buyer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Name</label>
                                <p class="fw-semibold">{{ $order->buyer->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <p>{{ $order->buyer->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if ($order->buyer->phone_number)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Phone</label>
                                    <p>{{ $order->buyer->phone_number }}</p>
                                </div>
                            @endif
                            @if ($order->buyer->address)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Address</label>
                                    <p>{{ $order->buyer->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @if ($order->status === 'awaiting_verification' && $order->hasTransferProof())
                <!-- Verification Panel -->
                <div class="card border-warning">
                    <div class="card-header bg-warning bg-opacity-10">
                        <h5 class="card-title text-warning mb-0">
                            <i class="las la-exclamation-triangle me-2"></i>
                            Transfer Verification Required
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-border-left alert-dismissible fade show" role="alert">
                                <i class="ri-check-line me-3 align-middle fs-16"></i><strong>Success!</strong>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                                <i class="ri-alert-line me-3 align-middle fs-16"></i><strong>Error!</strong>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <p class="text-muted">The buyer has uploaded transfer proof. Please review and verify the payment.
                        </p>

                        <div class="mb-3">
                            <label class="form-label">Transfer Proof:</label>
                            <div class="d-grid">
                                <a href="{{ route('seller.orders.transfer-proof', $order) }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="las la-external-link-alt me-1"></i>View Transfer Proof
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('seller.orders.verify-transfer', $order) }}" method="POST"
                            id="verificationForm">
                            @csrf
                            <div class="mb-3">
                                <label for="verification_notes" class="form-label">Verification Notes (Optional)</label>
                                <textarea name="verification_notes" id="verification_notes" class="form-control" rows="3"
                                    placeholder="Add any notes about this verification..."></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success" onclick="submitVerification('approve')">
                                    <i class="las la-check me-1"></i>Approve Transfer
                                </button>
                                <button type="button" class="btn btn-danger" onclick="submitVerification('reject')">
                                    <i class="las la-times me-1"></i>Reject Transfer
                                </button>
                            </div>
                            <input type="hidden" name="action" id="verification_action">
                        </form>
                    </div>
                </div>
            @endif

            @if ($order->isVerified())
                <!-- Verification Details -->
                <div class="card border-success">
                    <div class="card-header bg-success bg-opacity-10">
                        <h5 class="card-title text-success mb-0">
                            <i class="las la-check-circle me-2"></i>
                            Payment Verified
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Verified By</label>
                            <p>{{ $order->verifier->name ?? 'Unknown' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Verification Date</label>
                            <p>{{ $order->verified_at->format('d M Y H:i') }}</p>
                        </div>
                        @if ($order->verification_notes)
                            <div class="mb-3">
                                <label class="form-label text-muted">Notes</label>
                                <p>{{ $order->verification_notes }}</p>
                            </div>
                        @endif
                        @if ($order->transfer_proof)
                            <div class="d-grid">
                                <a href="{{ route('seller.orders.transfer-proof', $order) }}" target="_blank"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="las la-file-image me-1"></i>View Transfer Proof
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if ($order->status === 'failed' && $order->isVerified())
                <!-- Rejection Details -->
                <div class="card border-danger">
                    <div class="card-header bg-danger bg-opacity-10">
                        <h5 class="card-title text-danger mb-0">
                            <i class="las la-times-circle me-2"></i>
                            Payment Rejected
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Rejected By</label>
                            <p>{{ $order->verifier->name ?? 'Unknown' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Rejection Date</label>
                            <p>{{ $order->verified_at->format('d M Y H:i') }}</p>
                        </div>
                        @if ($order->verification_notes)
                            <div class="mb-3">
                                <label class="form-label text-muted">Rejection Reason</label>
                                <p>{{ $order->verification_notes }}</p>
                            </div>
                        @endif
                        @if ($order->transfer_proof)
                            <div class="d-grid">
                                <a href="{{ route('seller.orders.transfer-proof', $order) }}" target="_blank"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="las la-file-image me-1"></i>View Transfer Proof
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('seller.orders.index') }}" class="btn btn-secondary">
                            <i class="las la-arrow-left me-1"></i>Back to Orders
                        </a>
                        @if ($order->status === 'awaiting_verification')
                            <a href="{{ route('seller.orders.awaiting-verification') }}" class="btn btn-warning">
                                <i class="las la-clock me-1"></i>Awaiting Verification
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function submitVerification(action) {
                if (confirm('Are you sure you want to ' + action + ' this transfer?')) {
                    document.getElementById('verification_action').value = action;
                    document.getElementById('verificationForm').submit();
                }
            }
        </script>
    @endpush
@endsection
