@extends('layouts.be')

@section('title', 'Property Details - ' . $property->title)

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title mb-0">Property Details</h1>
                            <p class="text-muted">Detail informasi properti dan status verifikasi</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#statusModal">
                                <i class="bx bx-edit-alt me-1"></i>
                                Update Status & Notes
                            </button>
                            <div class="d-flex flex-column flex-lg-row gap-2 justify-content-lg-end">
                                <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-primary">
                                    <i class="las la-arrow-left me-1"></i>
                                    Back to Properties
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Property Information -->
            <div class="col-lg-8">
                <!-- Basic Info Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-home me-2"></i>
                            Property Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h3 class="mb-2">{{ $property->title }}</h3>
                                <p class="text-muted mb-3">{{ $property->description }}</p>

                                <!-- Status Badges -->
                                <div class="d-flex gap-2 mb-3">
                                    <span class="badge {{ $property->status_badge }} fs-6">
                                        <i
                                            class="bx bx-{{ $property->status === 'active' ? 'play-circle' : ($property->status === 'completed' ? 'check-circle' : ($property->status === 'cancelled' ? 'times-circle' : 'pause-circle')) }} me-1"></i>
                                        {{ $property->status_label }}
                                    </span>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="fw-medium">Address:</td>
                                        <td>{{ $property->address }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">City:</td>
                                        <td>{{ $property->city }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Province:</td>
                                        <td>{{ $property->province }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Land Area:</td>
                                        <td>{{ $property->land_area ? number_format($property->land_area) . ' m²' : 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Building Area:</td>
                                        <td>{{ $property->building_area ? number_format($property->building_area) . ' m²' : 'N/A' }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td class="fw-medium">Bedrooms:</td>
                                        <td>{{ $property->bedrooms ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Bathrooms:</td>
                                        <td>{{ $property->bathrooms ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Coupon Price:</td>
                                        <td><strong class="text-primary">Rp
                                                {{ number_format($property->coupon_price) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Max Coupons:</td>
                                        <td>{{ $property->max_coupons ? number_format($property->max_coupons) : 'Unlimited' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-medium">Sale Period:</td>
                                        <td>
                                            @if ($property->sale_start_date && $property->sale_end_date)
                                                {{ $property->sale_start_date->format('M d, Y') }} -
                                                {{ $property->sale_end_date->format('M d, Y') }}
                                            @else
                                                Not set
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($property->facilities && count($property->facilities))
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="fw-semibold mb-2">Facilities:</h6>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($property->facilities as $facility)
                                            <span class="badge bg-light text-dark">{{ $facility }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Property Images -->
                @if ($property->images->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-images me-2"></i>
                                Property Images ({{ $property->images->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach ($property->images as $image)
                                    <div class="col-md-4">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Property Image"
                                                class="img-fluid rounded"
                                                style="width: 100%; height: 200px; object-fit: cover;">
                                            @if ($image->is_primary)
                                                <span
                                                    class="position-absolute top-0 end-0 m-2 badge bg-primary">Primary</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Sales Statistics -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Sales Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3 border rounded">
                                    <h4 class="text-success mb-1">{{ $property->sold_coupons }}</h4>
                                    <small class="text-muted">Sold Coupons</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded">
                                    <h4 class="text-info mb-1">
                                        @if ($property->max_coupons)
                                            {{ $property->available_coupons }}
                                        @else
                                            ∞
                                        @endif
                                    </h4>
                                    <small class="text-muted">Available Coupons</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded">
                                    <h4 class="text-primary mb-1">Rp
                                        {{ number_format($property->sold_coupons * $property->coupon_price) }}</h4>
                                    <small class="text-muted">Total Revenue</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Seller Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>
                            Seller Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('template/be/dist/default/assets/images/users/avatar-1.jpg') }}"
                                    alt="Seller" class="rounded-circle" width="50">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $property->seller->name }}</h6>
                                <p class="text-muted mb-0">{{ $property->seller->email }}</p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">Member since:</small>
                            <div>{{ $property->seller->created_at->format('M d, Y') }}</div>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">Role:</small>
                            <div><span class="badge bg-info">{{ ucfirst($property->seller->role) }}</span></div>
                        </div>
                    </div>
                </div>

                <!-- Verification Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shield-alt me-2"></i>
                            Verification Status
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($property->notes)
                            <div class="mb-3">
                                <label class="form-label">Notes:</label>
                                <div class="p-3 bg-light rounded">
                                    <i class="bx bx-note me-2"></i>
                                    {{ $property->notes }}
                                </div>
                            </div>
                        @endif

                        <div class="text-muted">
                            <small>Last updated: {{ $property->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>

                <!-- Property Metadata -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Property Metadata
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted">Property ID:</td>
                                <td>{{ $property->id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Slug:</td>
                                <td><code>{{ $property->slug }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Created:</td>
                                <td>{{ $property->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Last Updated:</td>
                                <td>{{ $property->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @if ($property->latitude && $property->longitude)
                                <tr>
                                    <td class="text-muted">Coordinates:</td>
                                    <td>{{ $property->latitude }}, {{ $property->longitude }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Modals -->
    @include('pages.be.admin.properties.partials.status-modals')
@endsection

@push('scripts')
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    if (alert) {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            if (alert.parentNode) {
                                alert.parentNode.removeChild(alert);
                            }
                        }, 500);
                    }
                }, 5000);
            });
        });
    </script>
@endpush
