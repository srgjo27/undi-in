@extends('layouts.be')

@section('title', 'Coupon Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">Coupon Management</h1>
                        <p class="text-muted">Kelola semua kupon yang terjual</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.coupons.raffles') }}" class="btn btn-outline-primary">
                            <i class="fas fa-dice me-2"></i>Manage Raffles
                        </a>
                        <a href="{{ route('admin.coupons.report') }}" class="btn btn-outline-success">
                            <i class="fas fa-chart-line me-2"></i>Generate Report
                        </a>
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
                    <form method="GET" action="{{ route('admin.coupons.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="property_id" class="form-label">Property</label>
                                <select name="property_id" id="property_id" class="form-select">
                                    <option value="">All Properties</option>
                                    @foreach($properties as $property)
                                    <option value="{{ $property->id }}" {{ request('property_id') == $property->id ? 'selected' : '' }}>
                                        {{ Str::limit($property->title, 30) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="is_winner" class="form-label">Status</label>
                                <select name="is_winner" id="is_winner" class="form-select">
                                    <option value="">All Coupons</option>
                                    <option value="0" {{ request('is_winner') === '0' ? 'selected' : '' }}>Participants</option>
                                    <option value="1" {{ request('is_winner') === '1' ? 'selected' : '' }}>Winners</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" name="search" id="search" class="form-control" 
                                       placeholder="Search by coupon number or buyer name..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
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

    <!-- Coupons Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">All Coupons</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Coupon Number</th>
                                    <th>Property</th>
                                    <th>Buyer</th>
                                    <th>Order Info</th>
                                    <th>Status</th>
                                    <th>Purchase Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                <tr class="{{ $coupon->is_winner ? 'table-warning' : '' }}">
                                    <td>
                                        <strong>{{ $coupon->coupon_number }}</strong>
                                        @if($coupon->is_winner)
                                        <br><span class="badge bg-warning text-dark">
                                            <i class="fas fa-crown me-1"></i>WINNER
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ Str::limit($coupon->property->title, 25) }}</h6>
                                            <small class="text-muted">{{ $coupon->property->city }}, {{ $coupon->property->province }}</small>
                                            <br><small class="text-success">Rp {{ number_format($coupon->property->coupon_price) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $coupon->buyer->name }}</h6>
                                            <small class="text-muted">{{ $coupon->buyer->email }}</small>
                                            @if($coupon->buyer->phone_number)
                                            <br><small class="text-muted">{{ $coupon->buyer->phone_number }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Order #{{ $coupon->order->id }}</strong>
                                            <br><small class="text-muted">Qty: {{ $coupon->order->quantity }}</small>
                                            <br><small class="text-success">Rp {{ number_format($coupon->order->total_price) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($coupon->is_winner)
                                        <span class="badge bg-success">
                                            <i class="fas fa-trophy me-1"></i>Winner
                                        </span>
                                        @else
                                        <span class="badge bg-primary">
                                            <i class="fas fa-ticket-alt me-1"></i>Participant
                                        </span>
                                        @endif
                                        
                                        <br><span class="badge bg-{{ $coupon->order->status_badge }}">
                                            {{ $coupon->order->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $coupon->created_at->format('M d, Y') }}</small>
                                        <br><small class="text-muted">{{ $coupon->created_at->format('H:i') }}</small>
                                        <br><small class="text-muted">{{ $coupon->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.coupons.raffle-detail', $coupon->property) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-dice"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-ticket-alt fa-3x mb-3"></i>
                                            <p>No coupons found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($coupons->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            <small>
                                Showing {{ $coupons->firstItem() }} to {{ $coupons->lastItem() }} 
                                of {{ number_format($coupons->total()) }} results
                            </small>
                        </div>
                        <nav aria-label="Coupons pagination">
                            {{ $coupons->withQueryString()->links('pagination.custom') }}
                        </nav>
                    </div>
                    @endif
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
                            <h5 class="card-title text-white">Total Coupons</h5>
                            <h3 class="mb-0">{{ number_format($totalCoupons) }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-ticket-alt fa-2x opacity-75"></i>
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
                            <h5 class="card-title text-white">Winners</h5>
                            <h3 class="mb-0">{{ number_format($totalWinners) }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-crown fa-2x opacity-75"></i>
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
                            <h5 class="card-title text-white">Properties</h5>
                            <h3 class="mb-0">{{ number_format($totalProperties) }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-home fa-2x opacity-75"></i>
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
                            <h5 class="card-title text-white">Active Raffles</h5>
                            <h3 class="mb-0">{{ number_format($activeRaffles) }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-dice fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection