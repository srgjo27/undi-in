@extends('layouts.be')

@section('title', 'Transfers Awaiting Verification')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Transfers Awaiting Verification</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('seller.orders.index') }}">Orders</a></li>
                    <li class="breadcrumb-item active">Awaiting Verification</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mb-0">
                            <i class="las la-clock text-warning me-2"></i>
                            Transfers Awaiting Your Verification
                        </h4>
                        <p class="text-muted mb-0">Review and verify manual bank transfers from buyers</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('seller.orders.index') }}" class="btn btn-secondary btn-sm">
                            <i class="las la-arrow-left me-1"></i>Back to All Orders
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
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

                @if($orders->count() > 0)
                    <div class="row">
                        @foreach($orders as $order)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-warning bg-opacity-10">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="card-title mb-0">Order #{{ $order->id }}</h6>
                                            <span class="badge bg-warning">Awaiting Verification</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- Property Info -->
                                        <div class="d-flex align-items-center mb-3">
                                            @if($order->property->images->count() > 0)
                                                <img src="{{ asset('storage/' . $order->property->images->first()->image_path) }}" 
                                                     alt="{{ $order->property->title }}" 
                                                     class="avatar-sm rounded me-2">
                                            @else
                                                <div class="avatar-sm bg-light rounded me-2 d-flex align-items-center justify-content-center">
                                                    <i class="las la-home text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ Str::limit($order->property->title, 25) }}</h6>
                                                <small class="text-muted">Qty: {{ $order->quantity }}</small>
                                            </div>
                                        </div>

                                        <!-- Buyer Info -->
                                        <div class="mb-3">
                                            <small class="text-muted">Buyer:</small>
                                            <p class="mb-1 fw-medium">{{ $order->buyer->name }}</p>
                                            <small class="text-muted">{{ $order->buyer->email }}</small>
                                        </div>

                                        <!-- Amount -->
                                        <div class="mb-3">
                                            <small class="text-muted">Amount:</small>
                                            <p class="mb-0 fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        </div>

                                        <!-- Transfer Date -->
                                        <div class="mb-3">
                                            <small class="text-muted">Transfer Uploaded:</small>
                                            <p class="mb-0">{{ $order->updated_at->format('d M Y H:i') }}</p>
                                        </div>

                                        <!-- Actions -->
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-primary btn-sm">
                                                <i class="las la-eye me-1"></i>Review & Verify
                                            </a>
                                            @if($order->transfer_proof)
                                                <a href="{{ route('seller.orders.transfer-proof', $order) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                                    <i class="las la-file-image me-1"></i>View Transfer Proof
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer bg-light">
                                        <small class="text-muted">
                                            <i class="las la-clock me-1"></i>
                                            Order created: {{ $order->created_at->format('d M Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="las la-clipboard-check fs-48 d-block mb-3"></i>
                            <h5>No transfers awaiting verification</h5>
                            <p>All transfers have been processed or there are no pending transfers.</p>
                            <a href="{{ route('seller.orders.index') }}" class="btn btn-primary">
                                <i class="las la-list me-1"></i>View All Orders
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection