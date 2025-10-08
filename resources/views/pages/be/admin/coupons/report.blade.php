@extends('layouts.be')

@section('title', 'Coupon Sales Report')

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
                                    <li class="breadcrumb-item active">Sales Report</li>
                                </ol>
                            </nav>
                            <h1 class="page-title">Coupon Sales Report</h1>
                            <p class="text-muted">Laporan penjualan kupon dan statistik</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-soft-primary waves-effect waves-light" onclick="window.print()">
                                <i class="bx bx-printer me-2"></i>Print Report
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-2"></i>Back to Coupons
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.coupons.report') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="date_from" class="form-label">Date From</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="date_to" class="form-label">Date To</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control"
                                        value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="property_id" class="form-label">Property</label>
                                    <select name="property_id" id="property_id" class="form-select">
                                        <option value="">All Properties</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}"
                                                {{ request('property_id') == $property->id ? 'selected' : '' }}>
                                                {{ Str::limit($property->title, 30) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-filter me-1"></i>Filter
                                        </button>
                                        <a href="{{ route('admin.coupons.report') }}" class="btn btn-outline-secondary">
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

        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title text-white">Total Coupons Sold</h5>
                                <h3 class="mb-0 text-white">{{ number_format($totalCoupons) }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="las la-ticket-alt fs-2 opacity-75"></i>
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
                                <h5 class="card-title text-white">Total Revenue</h5>
                                <h3 class="mb-0 text-white">Rp {{ number_format($totalRevenue) }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="las la-money-bill-wave fs-2 opacity-75"></i>
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
                                <h5 class="card-title text-white">Active Properties</h5>
                                <h3 class="mb-0 text-white">{{ number_format($activeProperties) }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ri-building-line fs-2 opacity-75"></i>
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
                                <h5 class="card-title text-white">Winners</h5>
                                <h3 class="mb-0 text-white">{{ number_format($totalWinners) }}</h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="las la-crown fs-2 opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales by Property -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Sales by Property</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Property</th>
                                        <th>Coupons Sold</th>
                                        <th>Revenue</th>
                                        <th>Avg. Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesByProperty as $sale)
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ Str::limit($sale->title, 25) }}</h6>
                                                    <small class="text-muted">{{ $sale->city }},
                                                        {{ $sale->province }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ number_format($sale->coupons_count) }}</strong>
                                            </td>
                                            <td>
                                                <strong class="text-success">Rp
                                                    {{ number_format($sale->total_revenue) }}</strong>
                                            </td>
                                            <td>
                                                <span class="text-muted">Rp
                                                    {{ number_format($sale->coupon_price) }}</span>
                                            </td>
                                            <td>
                                                @if ($sale->raffles_count > 0)
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($sale->coupons_count > 0)
                                                    <span class="badge bg-warning">Pending Raffle</span>
                                                @else
                                                    <span class="badge bg-secondary">No Sales</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-dark">
                                        <th>Total</th>
                                        <th>{{ number_format($salesByProperty->sum('coupons_count')) }}</th>
                                        <th>Rp {{ number_format($salesByProperty->sum('total_revenue')) }}</th>
                                        <th>-</th>
                                        <th>-</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Top Buyers -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Top Buyers</h5>
                    </div>
                    <div class="card-body">
                        @if ($topBuyers->count() > 0)
                            @foreach ($topBuyers as $buyer)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0">{{ $buyer->name }}</h6>
                                        <small class="text-muted">{{ $buyer->email }}</small>
                                    </div>
                                    <div class="text-end">
                                        <strong>{{ $buyer->coupons_count }} coupons</strong>
                                        <br><small class="text-success">Rp
                                            {{ number_format($buyer->total_spent) }}</small>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        @else
                            <p class="text-muted text-center">No buyers found</p>
                        @endif
                    </div>
                </div>

                <!-- Monthly Trends -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Monthly Sales Trend</h5>
                    </div>
                    <div class="card-body">
                        @if ($monthlyTrends->count() > 0)
                            @foreach ($monthlyTrends as $trend)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ $trend->month_name }}</strong>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary">{{ $trend->coupons_count }} coupons</span>
                                        <br><small class="text-success">Rp
                                            {{ number_format($trend->total_revenue) }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center">No data available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Recent Coupon Sales</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Coupon Number</th>
                                        <th>Property</th>
                                        <th>Buyer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentSales as $sale)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $sale->created_at->format('M d, Y') }}</strong>
                                                    <br><small
                                                        class="text-muted">{{ $sale->created_at->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $sale->coupon_number }}</strong>
                                                @if ($sale->is_winner)
                                                    <span class="badge bg-warning text-dark ms-1">
                                                        <i class="fas fa-crown"></i>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ Str::limit($sale->property->title, 20) }}</h6>
                                                    <small class="text-muted">{{ $sale->property->city }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $sale->buyer->name }}</h6>
                                                    <small class="text-muted">{{ $sale->buyer->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong class="text-success">Rp
                                                    {{ number_format($sale->property->coupon_price) }}</strong>
                                            </td>
                                            <td>
                                                @if ($sale->is_winner)
                                                    <span class="badge bg-success">Winner</span>
                                                @else
                                                    <span class="badge bg-primary">Participant</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Footer -->
        <div class="row mt-4 d-print-block">
            <div class="col-12">
                <div class="text-center text-muted">
                    <p>Report generated on {{ now()->format('F d, Y H:i') }}</p>
                    <p>© {{ date('Y') }} Undi-In Property Raffle Platform</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn,
            .breadcrumb,
            .card-header .btn {
                display: none !important;
            }

            .page-header {
                border-bottom: 2px solid #000;
                margin-bottom: 20px;
            }

            .card {
                border: 1px solid #000 !important;
                page-break-inside: avoid;
            }
        }
    </style>
@endsection
