@extends('layouts.be')

@section('title', 'Raffle Detail - ' . $property->title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">Raffle Detail</h1>
                        <p class="text-muted">{{ $property->title }}</p>
                    </div>
                    <a href="{{ route('admin.coupons.raffles') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Raffles
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Property Information -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Property Information</h5>
                </div>
                <div class="card-body">
                    @if($property->primaryImage())
                    <img src="{{ asset('storage/' . $property->primaryImage()->image_path) }}" 
                         alt="{{ $property->title }}" class="img-fluid rounded mb-3">
                    @endif
                    
                    <h6>{{ $property->title }}</h6>
                    <p class="text-muted">{{ $property->address }}, {{ $property->city }}, {{ $property->province }}</p>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h5>{{ $coupons->count() }}</h5>
                            <small class="text-muted">Total Coupons</small>
                        </div>
                        <div class="col-6">
                            <h5>Rp {{ number_format($property->coupon_price) }}</h5>
                            <small class="text-muted">Price per Coupon</small>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <strong>Seller:</strong> {{ $property->seller->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Total Revenue:</strong> Rp {{ number_format($coupons->count() * $property->coupon_price) }}
                    </div>
                    <div>
                        <strong>Status:</strong> 
                        <span class="badge {{ $property->status_badge }}">{{ $property->status_label }}</span>
                    </div>
                </div>
            </div>

            <!-- Raffle Status -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Raffle Status</h6>
                </div>
                <div class="card-body">
                    @if($property->raffles->count() > 0)
                        @php $raffle = $property->raffles->first(); @endphp
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Raffle Completed!</strong>
                        </div>
                        
                        <div class="mb-2">
                            <strong>Draw Date:</strong> {{ $raffle->draw_date->format('M d, Y H:i') }}
                        </div>
                        
                        @if($raffle->winnerCoupon)
                        <div class="mb-2">
                            <strong>Winner:</strong> {{ $raffle->winnerCoupon->buyer->name }}
                        </div>
                        <div class="mb-2">
                            <strong>Winning Coupon:</strong> {{ $raffle->winnerCoupon->coupon_number }}
                        </div>
                        @endif
                        
                        @if($raffle->drawnBy)
                        <div class="mb-2">
                            <strong>Conducted By:</strong> {{ $raffle->drawnBy->name }}
                        </div>
                        @endif
                        
                        @if($raffle->notes)
                        <div>
                            <strong>Notes:</strong>
                            <p class="text-muted mb-0">{{ $raffle->notes }}</p>
                        </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Raffle Not Conducted</strong>
                        </div>
                        
                        @if($coupons->count() > 0)
                        <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#raffleModal">
                            <i class="fas fa-dice me-2"></i>Conduct Raffle Now
                        </button>
                        @else
                        <p class="text-muted">No coupons available for raffle.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Coupons List -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Participating Coupons</h5>
                </div>
                <div class="card-body">
                    @if($coupons->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Coupon Number</th>
                                    <th>Buyer</th>
                                    <th>Purchase Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coupons as $coupon)
                                <tr class="{{ $coupon->is_winner ? 'table-success' : '' }}">
                                    <td>
                                        <strong>{{ $coupon->coupon_number }}</strong>
                                        @if($coupon->is_winner)
                                        <span class="badge bg-warning ms-2">
                                            <i class="fas fa-crown me-1"></i>WINNER
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $coupon->buyer->name }}</h6>
                                            <small class="text-muted">{{ $coupon->buyer->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $coupon->created_at->format('M d, Y H:i') }}</small>
                                        <br><small class="text-muted">{{ $coupon->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($coupon->is_winner)
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
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No coupons sold for this property yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Raffle Modal -->
@if($property->raffles->count() === 0 && $coupons->count() > 0)
<div class="modal fade" id="raffleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.coupons.conduct-raffle', $property) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Conduct Raffle: {{ $property->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>{{ $coupons->count() }}</strong> coupons will participate in this raffle.
                        The system will randomly select one winner.
                    </div>
                    
                    <div class="mb-3">
                        <label for="draw_date" class="form-label">Draw Date & Time</label>
                        <input type="datetime-local" name="draw_date" id="draw_date" 
                               class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <h6>All Participating Coupons:</h6>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            <div class="row g-2">
                                @foreach($coupons as $coupon)
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center py-2 px-3 bg-light rounded">
                                        <div>
                                            <strong>{{ $coupon->coupon_number }}</strong>
                                            <br><small class="text-muted">{{ $coupon->buyer->name }}</small>
                                        </div>
                                        <span class="badge bg-primary">Ready</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> Once conducted, this raffle cannot be undone. 
                        Make sure all coupons are valid before proceeding.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" 
                            onclick="return confirm('Are you sure you want to conduct this raffle? This action cannot be undone.')">
                        <i class="fas fa-dice me-1"></i>Conduct Raffle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection