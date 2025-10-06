@extends('layouts.be')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">Admin Dashboard</h1>
                    <p class="text-muted">Selamat datang di panel admin Undi In</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Users Stats -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total Users</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-success fs-14 mb-0">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                    @php
                                        $totalUsers = $stats['total_users'];
                                        $growthPercentage =
                                            $totalUsers > 0 ? min(round(($totalUsers / 10) * 2.5, 1), 25) : 0;
                                    @endphp
                                    +{{ $growthPercentage }}%
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value"
                                        data-target="{{ number_format($stats['total_users']) }}">{{ number_format($stats['total_users']) }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-primary rounded fs-3">
                                    <i class="bx bx-user text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Properties Stats -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total Properties</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-warning fs-14 mb-0">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                    @php
                                        $totalProperties = $stats['total_properties'];
                                        $growthPercentage =
                                            $totalProperties > 0 ? min(round(($totalProperties / 10) * 2.5, 1), 25) : 0;

                                    @endphp
                                    +{{ $growthPercentage }}%
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value"
                                        data-target="{{ number_format($stats['total_properties']) }}">{{ number_format($stats['total_properties']) }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning rounded fs-3">
                                    <i class="bx bx-store-alt text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Stats -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total Revenue</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-success fs-14 mb-0">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                    @php
                                        $totalRevenue = $stats['total_revenue'];
                                        $growthPercentage =
                                            $totalRevenue > 0 ? min(round(($totalRevenue / 10) * 2.5, 1), 25) : 0;

                                    @endphp
                                    +{{ $growthPercentage }}%
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value"
                                        data-target="{{ number_format($stats['total_revenue']) }}">{{ number_format($stats['total_revenue']) }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success rounded fs-3">
                                    <i class="bx bx-dollar-circle text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Stats -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total Transactions</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-info fs-14 mb-0">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                    @php
                                        $totalTransactions = $stats['total_transactions'];
                                        $growthPercentage =
                                            $totalTransactions > 0 ? min(round(($totalTransactions / 10) * 2.5, 1), 25) : 0;

                                    @endphp
                                    +{{ $growthPercentage }}%
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary"><span class="counter-value"
                                        data-target="{{ number_format($stats['total_transactions']) }}">{{ number_format($stats['total_transactions']) }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded fs-3">
                                    <i class="bx bx-wallet text-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Detailed Stats -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">System Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>User Management</h6>
                                <ul class="list-unstyled">
                                    <li>Sellers: <strong>{{ number_format($stats['total_sellers']) }}</strong></li>
                                    <li>Buyers: <strong>{{ number_format($stats['total_buyers']) }}</strong></li>
                                    <li>Active Users: <strong>{{ number_format($stats['active_users']) }}</strong></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Property Management</h6>
                                <ul class="list-unstyled">
                                    <li>Pending Verification:
                                        <strong>{{ number_format($stats['pending_properties']) }}</strong>
                                    </li>
                                    <li>Approved: <strong>{{ number_format($stats['approved_properties']) }}</strong></li>
                                    <li>Active Properties:
                                        <strong>{{ number_format($stats['active_properties']) }}</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6>Order Management</h6>
                                <ul class="list-unstyled">
                                    <li>Completed Orders: <strong>{{ number_format($stats['completed_orders']) }}</strong>
                                    </li>
                                    <li>Pending Orders: <strong>{{ number_format($stats['pending_orders']) }}</strong></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Coupon & Raffle</h6>
                                <ul class="list-unstyled">
                                    <li>Total Coupons: <strong>{{ number_format($stats['total_coupons']) }}</strong></li>
                                    <li>Winner Coupons: <strong>{{ number_format($stats['winner_coupons']) }}</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                            <a href="{{ route('admin.properties.index') }}" class="btn btn-success">
                                <i class="fas fa-home me-2"></i>Manage Properties
                            </a>
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-warning">
                                <i class="fas fa-exchange-alt me-2"></i>View Transactions
                            </a>
                            <a href="{{ route('admin.coupons.raffles') }}" class="btn btn-info">
                                <i class="fas fa-dice me-2"></i>Manage Raffles
                            </a>
                            <a href="{{ route('admin.system.config') }}" class="btn btn-secondary">
                                <i class="fas fa-cog me-2"></i>System Config
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row mt-4">
            <!-- Recent Users -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Recent Users</h6>
                    </div>
                    <div class="card-body">
                        @forelse($recent_users as $user)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->role }} •
                                        {{ $user->created_at->diffForHumans() }}</small>
                                </div>
                                <span
                                    class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'seller' ? 'warning' : 'primary') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted">No recent users</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Properties -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Recent Properties</h6>
                    </div>
                    <div class="card-body">
                        @forelse($recent_properties as $property)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($property->title, 30) }}</h6>
                                    <small class="text-muted">By {{ $property->seller->name }} •
                                        {{ $property->created_at->diffForHumans() }}</small>
                                </div>
                                <span
                                    class="badge bg-{{ $property->verification_status === 'approved' ? 'success' : ($property->verification_status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($property->verification_status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted">No recent properties</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Recent Transactions</h6>
                    </div>
                    <div class="card-body">
                        @forelse($recent_transactions as $transaction)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Rp {{ number_format($transaction->amount) }}</h6>
                                    <small class="text-muted">{{ $transaction->order->buyer->name }} •
                                        {{ $transaction->created_at->diffForHumans() }}</small>
                                </div>
                                <span
                                    class="badge bg-{{ $transaction->status === 'success' ? 'success' : ($transaction->status === 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted">No recent transactions</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
