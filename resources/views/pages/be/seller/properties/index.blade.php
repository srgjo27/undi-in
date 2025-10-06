@extends('layouts.be')

@section('title', 'Seller Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Properti Saya</h5>
                        <div class="flex-shrink-0">
                            <a href="{{ route('seller.properties.create') }}" class="btn btn-success">
                                <i class="ri-add-line align-bottom me-1"></i> Tambah Properti
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filter and Search -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter" onchange="filterByStatus()">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="pending_draw" {{ request('status') === 'pending_draw' ? 'selected' : '' }}>
                                    Menunggu Undian</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('seller.properties.index') }}">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari properti..." name="search"
                                        value="{{ request('search') }}">
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if ($properties->count() > 0)
                        <div class="row">
                            @foreach ($properties as $property)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card property-card">
                                        <div class="card-body p-0">
                                            <div class="position-relative">
                                                @if ($property->primaryImage())
                                                    <img src="{{ Storage::url($property->primaryImage()->image_path) }}"
                                                        class="card-img-top property-image" alt="{{ $property->title }}">
                                                @else
                                                    <div
                                                        class="card-img-top property-image d-flex align-items-center justify-content-center bg-light">
                                                        <i class="ri-image-line text-muted" style="font-size: 3rem;"></i>
                                                    </div>
                                                @endif
                                                <div class="position-absolute top-0 end-0 p-2">
                                                    <span
                                                        class="badge {{ $property->status_badge }}">{{ $property->status_label }}</span>
                                                </div>
                                            </div>

                                            <div class="p-3">
                                                <h6 class="card-title mb-2">
                                                    <a href="{{ route('seller.properties.show', $property) }}"
                                                        class="text-decoration-none">
                                                        {{ $property->title }}
                                                    </a>
                                                </h6>
                                                <p class="text-muted mb-2">
                                                    <i class="ri-map-pin-line"></i> {{ $property->city }},
                                                    {{ $property->province }}
                                                </p>
                                                <div class="row text-center mb-3">
                                                    <div class="col-4">
                                                        <small class="text-muted">Luas Tanah</small>
                                                        <p class="mb-0 fw-medium">{{ number_format($property->land_area) }}
                                                            m²</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted">Kamar Tidur</small>
                                                        <p class="mb-0 fw-medium">{{ $property->bedrooms }}</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted">Kamar Mandi</small>
                                                        <p class="mb-0 fw-medium">{{ $property->bathrooms }}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <small class="text-muted">Harga Kupon</small>
                                                        <p class="mb-0 fw-bold text-primary">Rp
                                                            {{ number_format($property->coupon_price) }}</p>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-muted">Kupon Terjual</small>
                                                        <p class="mb-0 fw-medium">
                                                            {{ $property->sold_coupons }}{{ $property->max_coupons ? '/' . $property->max_coupons : '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('seller.properties.show', $property) }}"
                                                        class="btn btn-sm btn-outline-primary flex-fill">
                                                        <i class="ri-eye-line"></i> Detail
                                                    </a>
                                                    <a href="{{ route('seller.properties.edit', $property) }}"
                                                        class="btn btn-sm btn-outline-warning flex-fill">
                                                        <i class="ri-edit-line"></i> Edit
                                                    </a>
                                                    @if ($property->orders()->count() === 0)
                                                        <button type="button" class="btn btn-sm btn-outline-danger flex-fill"
                                                            onclick="deleteProperty({{ $property->id }})">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $properties->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="ri-building-line display-4 text-muted"></i>
                            </div>
                            <h5 class="text-muted">Belum ada properti</h5>
                            <p class="text-muted">Mulai tambahkan properti pertama Anda!</p>
                            <a href="{{ route('seller.properties.create') }}" class="btn btn-primary">
                                <i class="ri-add-line me-1"></i> Tambah Properti
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus properti ini?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .property-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .property-card {
            transition: transform 0.2s;
        }

        .property-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        function filterByStatus() {
            const status = document.getElementById('statusFilter').value;
            const currentUrl = new URL(window.location);

            if (status) {
                currentUrl.searchParams.set('status', status);
            } else {
                currentUrl.searchParams.delete('status');
            }

            currentUrl.searchParams.delete('page');
            window.location.href = currentUrl.toString();
        }

        function deleteProperty(propertyId) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/seller/properties/${propertyId}`;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
@endsection
