@extends('layouts.be')

@section('title', 'Raffle Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">Raffle Management</h1>
                            <p class="text-muted">Kelola pengundian properti</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-soft-secondary waves-effect waves-light">
                                <i class="las la-ticket-alt me-2"></i>View All Coupons
                            </a>
                            <a href="{{ route('admin.coupons.report') }}" class="btn btn-soft-warning waves-effect waves-light">
                                <i class="las la-chart-bar me-2"></i>Coupon Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4 mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.coupons.raffles') }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="status_raffle" class="form-label">Raffle Status</label>
                                    <select name="status" id="status_raffle" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                            Pending (No Raffle)</option>
                                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" name="search" id="search" class="form-control"
                                        placeholder="Search by property title..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="las la-search me-1"></i>Filter
                                        </button>
                                        <a href="{{ route('admin.coupons.raffles') }}" class="btn btn-outline-secondary">
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

        <!-- Properties with Coupons -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Properties Eligible for Raffle</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Property</th>
                                        <th>Seller</th>
                                        <th>Verification</th>
                                        <th>Coupons Sold</th>
                                        <th>Raffle Status</th>
                                        <th>Winner</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($properties as $property)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($property->primaryImage())
                                                        <img src="{{ asset('storage/' . $property->primaryImage()->image_path) }}"
                                                            alt="{{ $property->title }}" class="me-2"
                                                            style="width: 50px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light me-2 d-flex align-items-center justify-content-center"
                                                            style="width: 50px; height: 40px;">
                                                            <i class="las la-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ Str::limit($property->title, 30) }}</h6>
                                                        <small class="text-muted">{{ $property->city }},
                                                            {{ $property->province }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $property->seller->name }}</h6>
                                                    <small class="text-muted">{{ $property->seller->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $property->status_badge }}">
                                                    {{ $property->status_label }}
                                                </span>
                                                @if ($property->notes)
                                                    <br><small class="text-muted">{{ Str::limit($property->notes, 30) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $property->coupons->count() }}</strong> coupons
                                                <br><small class="text-muted">
                                                    Revenue: Rp
                                                    {{ number_format($property->coupons->count() * $property->coupon_price) }}
                                                </small>
                                            </td>
                                            <td>
                                                @if ($property->raffles->count() > 0)
                                                    <span class="badge bg-success">Completed</span>
                                                    <br><small class="text-muted">
                                                        {{ $property->raffles->first()->draw_date->format('M d, Y') }}
                                                    </small>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                    <br><small class="text-muted">No raffle conducted</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($property->raffles->count() > 0 && $property->raffles->first()->winnerCoupon)
                                                    @php $winner = $property->raffles->first()->winnerCoupon @endphp
                                                    <div>
                                                        <strong>{{ $winner->buyer->name }}</strong>
                                                        <br><small class="text-muted">Coupon:
                                                            {{ $winner->coupon_number }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.coupons.raffle-detail', $property) }}"
                                                        class="btn btn-sm btn-outline-info">
                                                        <i class="las la-eye me-1"></i>Detail
                                                    </a>
                                                    @if ($property->raffles->count() === 0 && $property->coupons->count() > 0)
                                                        @if ($property->status === 'pending_draw')
                                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                                onclick="showRaffleModal({{ $property->id }})">
                                                                <i class="las la-dice me-1"></i>Conduct Raffle
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-outline-danger" disabled
                                                                title="Property must be in pending_draw status before conducting raffle">
                                                                <i class="las la-exclamation-triangle me-1"></i>
                                                                {{ ucfirst($property->status) }}
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Raffle Modal -->
                                        @if ($property->raffles->count() === 0 && $property->coupons->count() > 0 && $property->status === 'pending_draw')
                                            <div class="modal fade" id="raffleModal{{ $property->id }}" tabindex="-1" 
                                                 aria-labelledby="raffleModalLabel{{ $property->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('admin.coupons.conduct-raffle', $property) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="raffleModalLabel{{ $property->id }}">Conduct Raffle:
                                                                    {{ $property->title }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="alert alert-info">
                                                                    <i class="las la-info-circle me-2"></i>
                                                                    <strong>{{ $property->coupons->count() }}</strong>
                                                                    coupons will participate in this raffle.
                                                                    The system will randomly select one winner.
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="draw_date{{ $property->id }}"
                                                                        class="form-label">Draw Date</label>
                                                                    <input type="datetime-local" name="draw_date"
                                                                        id="draw_date{{ $property->id }}"
                                                                        class="form-control"
                                                                        value="{{ now()->format('Y-m-d\TH:i') }}"
                                                                        required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <h6>Participating Coupons:</h6>
                                                                    <div class="border rounded p-2"
                                                                        style="max-height: 200px; overflow-y: auto;">
                                                                        @foreach ($property->coupons as $coupon)
                                                                            <div
                                                                                class="d-flex justify-content-between align-items-center py-1">
                                                                                <span>{{ $coupon->coupon_number }}</span>
                                                                                <small
                                                                                    class="text-muted">{{ $coupon->buyer->name }}</small>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-success"
                                                                    onclick="return confirm('Are you sure you want to conduct this raffle? This action cannot be undone.')">
                                                                    <i class="las la-dice me-1"></i>Conduct Raffle
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="las la-dice fs-2 mb-3"></i>
                                                    <p>No properties with coupons found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($properties->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $properties->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showRaffleModal(propertyId) {
        const modalId = 'raffleModal' + propertyId;
        const modalElement = document.getElementById(modalId);
        
        if (!modalElement) {
            alert('Modal not found for property ID: ' + propertyId);
            return;
        }

        // Simple approach - just add Bootstrap classes and show
        modalElement.classList.add('show');
        modalElement.style.display = 'block';
        modalElement.setAttribute('aria-modal', 'true');
        modalElement.setAttribute('role', 'dialog');
        
        // Add backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'backdrop-' + propertyId;
        document.body.appendChild(backdrop);
        
        // Add body class for modal-open
        document.body.classList.add('modal-open');
        
        // Close handlers
        const closeButtons = modalElement.querySelectorAll('[data-bs-dismiss="modal"], .btn-close');
        closeButtons.forEach(function(btn) {
            btn.onclick = function() {
                closeRaffleModal(propertyId);
            };
        });
        
        // Close on backdrop click
        backdrop.onclick = function() {
            closeRaffleModal(propertyId);
        };
    }

    function closeRaffleModal(propertyId) {
        const modalId = 'raffleModal' + propertyId;
        const modalElement = document.getElementById(modalId);
        const backdrop = document.getElementById('backdrop-' + propertyId);
        
        if (modalElement) {
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            modalElement.removeAttribute('aria-modal');
            modalElement.removeAttribute('role');
        }
        
        if (backdrop) {
            backdrop.remove();
        }
        
        document.body.classList.remove('modal-open');
    }

    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(function(modal) {
                const id = modal.id.replace('raffleModal', '');
                closeRaffleModal(id);
            });
        }
    });
</script>
@endpush
