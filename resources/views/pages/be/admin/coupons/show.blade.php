@extends('layouts.be')

@section('title', 'Coupon Detail')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                                    <li class="breadcrumb-item active">{{ $coupon->coupon_number }}</li>
                                </ol>
                            </nav>
                            <h1 class="page-title">Coupon Detail</h1>
                            <p class="text-muted">Detail informasi kupon dan pembeli</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                                <i class="las la-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Coupon Information -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Coupon Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Coupon Number:</th>
                                        <td>
                                            <strong class="text-primary">{{ $coupon->coupon_number }}</strong>
                                            @if ($coupon->is_winner)
                                                <span class="badge bg-warning text-dark ms-2">
                                                    <i class="las la-crown me-1"></i>WINNER
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @if ($coupon->is_winner)
                                                <span class="badge bg-success">
                                                    <i class="las la-trophy me-1"></i>Winner
                                                </span>
                                            @else
                                                <span class="badge bg-primary">
                                                    <i class="las la-ticket-alt me-1"></i>Participant
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Purchase Date:</th>
                                        <td>{{ $coupon->created_at->format('F d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time Ago:</th>
                                        <td>{{ $coupon->created_at->diffForHumans() }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Property Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if ($coupon->property->images->count() > 0)
                                    <img src="{{ asset('storage/' . $coupon->property->images->first()->image_path) }}"
                                        class="img-fluid rounded" alt="Property Image">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <i class="las la-home fs-1 text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4 class="mb-3">{{ $coupon->property->title }}</h4>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">Location:</th>
                                        <td>{{ $coupon->property->city }}, {{ $coupon->property->province }}</td>
                                    </tr>
                                    <tr>
                                        <th>Coupon Price:</th>
                                        <td><strong class="text-success">Rp
                                                {{ number_format($coupon->property->coupon_price) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Coupon Value:</th>
                                        <td><strong class="text-primary">Rp
                                                {{ number_format($coupon->property->coupon_price) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge {{ $coupon->property->status_badge }}">
                                                {{ $coupon->property->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Total Coupons Sold:</th>
                                        <td>{{ $coupon->property->coupons->count() }} coupons</td>
                                    </tr>
                                </table>
                                <div class="mt-3">
                                    <a href="{{ route('admin.properties.show', $coupon->property) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="las la-eye me-1"></i>View Property Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Order Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Order ID:</th>
                                        <td><strong>#{{ $coupon->order->id }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Quantity:</th>
                                        <td>{{ $coupon->order->quantity }} coupons</td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount:</th>
                                        <td><strong class="text-success">Rp
                                                {{ number_format($coupon->order->total_price) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Order Status:</th>
                                        <td>
                                            <span class="badge bg-{{ $coupon->order->status_badge }}">
                                                {{ $coupon->order->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Order Date:</th>
                                        <td>{{ $coupon->order->created_at->format('F d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.transactions.show', $coupon->order) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="las la-receipt me-1"></i>View Full Order Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buyer Information & Actions -->
            <div class="col-md-4">
                <!-- Buyer Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Buyer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ strtoupper(substr($coupon->buyer->name, 0, 1)) }}
                            </div>
                        </div>

                        <table class="table table-borderless table-sm">
                            <tr>
                                <th>Name:</th>
                                <td>{{ $coupon->buyer->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $coupon->buyer->email }}</td>
                            </tr>
                            @if ($coupon->buyer->phone_number)
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $coupon->buyer->phone_number }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Role:</th>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($coupon->buyer->role) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-{{ $coupon->buyer->is_active ? 'success' : 'danger' }}">
                                        {{ $coupon->buyer->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Member Since:</th>
                                <td>{{ $coupon->buyer->created_at->format('M d, Y') }}</td>
                            </tr>
                        </table>

                        <div class="mt-3">
                            <a href="{{ route('admin.users.show', $coupon->buyer) }}"
                                class="btn btn-sm btn-outline-primary w-100">
                                <i class="las la-user me-1"></i>View User Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Raffle Information -->
                @if ($coupon->property->raffles->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">Raffle Information</h5>
                        </div>
                        <div class="card-body">
                            @php $raffle = $coupon->property->raffles->first() @endphp
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th>Raffle Date:</th>
                                    <td>{{ $raffle->draw_date->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Winner Coupon:</th>
                                    <td><strong>{{ $raffle->winnerCoupon ? $raffle->winnerCoupon->coupon_number : 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Total Participants:</th>
                                    <td>{{ $coupon->property->coupons->count() }} coupons</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="las la-check me-1"></i>Completed
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            @if ($raffle->winnerCoupon && $coupon->coupon_number === $raffle->winnerCoupon->coupon_number)
                                <div class="alert alert-warning">
                                    <i class="las la-crown me-2"></i>
                                    <strong>This coupon is the WINNER!</strong>
                                </div>
                            @endif

                            <div class="mt-3">
                                <a href="{{ route('admin.coupons.raffle-detail', $coupon->property) }}"
                                    class="btn btn-sm btn-outline-primary w-100">
                                    <i class="las la-dice me-1"></i>View Raffle Details
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">Raffle Status</h5>
                        </div>
                        <div class="card-body text-center">
                            <i class="las la-hourglass-half fs-2 text-muted mb-3"></i>
                            <p class="text-muted">Raffle has not been conducted yet</p>
                            <a href="{{ route('admin.coupons.raffle-detail', $coupon->property) }}"
                                class="btn btn-sm btn-primary">
                                <i class="las la-dice me-1"></i>Manage Raffle
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.properties.show', $coupon->property) }}"
                                class="btn btn-outline-info">
                                <i class="las la-home me-2"></i>View Property
                            </a>
                            <a href="{{ route('admin.transactions.show', $coupon->order) }}"
                                class="btn btn-outline-success">
                                <i class="las la-receipt me-2"></i>View Order
                            </a>
                            <a href="{{ route('admin.users.show', $coupon->buyer) }}" class="btn btn-outline-primary">
                                <i class="las la-user me-2"></i>View Buyer Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
