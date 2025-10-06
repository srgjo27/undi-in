@extends('layouts.be')

@section('title', 'Seller Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $property->title }}</h4>
                            <p class="text-muted mb-0">
                                <i class="ri-map-pin-line"></i> {{ $property->address }}, {{ $property->city }},
                                {{ $property->province }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge {{ $property->status_badge }} fs-12">{{ $property->status_label }}</span>
                        </div>
                    </div>

                    <!-- Property Images -->
                    @if ($property->images->count() > 0)
                        <div class="mb-4">
                            <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($property->images as $index => $image)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ Storage::url($image->image_path) }}"
                                                class="d-block w-100 property-main-image"
                                                alt="{{ $image->caption ?: $property->title }}">
                                            @if ($image->caption)
                                                <div class="carousel-caption d-none d-md-block">
                                                    <p class="mb-0">{{ $image->caption }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @if ($property->images->count() > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>
                                @endif
                            </div>

                            <!-- Thumbnail Navigation -->
                            @if ($property->images->count() > 1)
                                <div class="row mt-3">
                                    @foreach ($property->images as $index => $image)
                                        <div class="col-2">
                                            <img src="{{ Storage::url($image->image_path) }}"
                                                class="img-thumbnail property-thumbnail {{ $index === 0 ? 'active' : '' }}"
                                                onclick="goToSlide({{ $index }})" style="cursor: pointer;">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Property Details -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="fw-semibold mb-3">Deskripsi Properti</h6>
                            <p class="text-muted">{{ $property->description }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3 col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="mb-1">{{ number_format($property->land_area) }}</h5>
                                <p class="text-muted mb-0">Luas Tanah (m²)</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="mb-1">{{ number_format($property->building_area) }}</h5>
                                <p class="text-muted mb-0">Luas Bangunan (m²)</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="mb-1">{{ $property->bedrooms }}</h5>
                                <p class="text-muted mb-0">Kamar Tidur</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="mb-1">{{ $property->bathrooms }}</h5>
                                <p class="text-muted mb-0">Kamar Mandi</p>
                            </div>
                        </div>
                    </div>

                    @if ($property->facilities && count($property->facilities) > 0)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Fasilitas</h6>
                            <div class="row">
                                @php
                                    $facilitiesLabels = [
                                        'garage' => 'Garasi',
                                        'swimming_pool' => 'Kolam Renang',
                                        'garden' => 'Taman',
                                        'balcony' => 'Balkon',
                                        'terrace' => 'Teras',
                                        'security' => 'Keamanan 24 Jam',
                                        'elevator' => 'Lift',
                                        'gym' => 'Gym/Fitness',
                                        'playground' => 'Playground',
                                        'mosque' => 'Musholla/Masjid',
                                    ];
                                @endphp
                                @foreach ($property->facilities as $facility)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <i class="ri-check-line text-success me-2"></i>
                                        {{ $facilitiesLabels[$facility] ?? $facility }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($property->latitude && $property->longitude)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">Lokasi</h6>
                            <div class="ratio ratio-16x9">
                                <iframe
                                    src="https://maps.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}&t=&z=15&ie=UTF8&iwloc=&output=embed"
                                    frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
                                </iframe>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Properti</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.properties.update-status', $property) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="draft" {{ $property->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ $property->status === 'active' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="pending_draw" {{ $property->status === 'pending_draw' ? 'selected' : '' }}>
                                    Menunggu Undian</option>
                                <option value="completed" {{ $property->status === 'completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="cancelled" {{ $property->status === 'cancelled' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>
                    </form>

                    <div class="text-center">
                        <span class="badge {{ $property->status_badge }} fs-14">{{ $property->status_label }}</span>
                    </div>
                </div>
            </div>

            <!-- Pricing Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Kupon</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-primary mb-1">Rp {{ number_format($property->coupon_price) }}</h4>
                        <p class="text-muted mb-0">per kupon</p>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="mb-1">{{ $property->sold_coupons }}</h6>
                            <p class="text-muted mb-0 fs-12">Terjual</p>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-1">{{ $property->max_coupons ? $property->available_coupons : '∞' }}</h6>
                            <p class="text-muted mb-0 fs-12">Tersisa</p>
                        </div>
                    </div>

                    @if ($property->max_coupons)
                        <div class="progress mt-3" style="height: 8px;">
                            @php
                                $percentage =
                                    $property->max_coupons > 0
                                        ? ($property->sold_coupons / $property->max_coupons) * 100
                                        : 0;
                            @endphp
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format($percentage, 1) }}% terjual</small>
                    @endif
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Jadwal Penjualan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-medium mb-1">Mulai Penjualan</h6>
                        <p class="text-muted mb-0">{{ $property->sale_start_date->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-medium mb-1">Selesai Penjualan</h6>
                        <p class="text-muted mb-0">{{ $property->sale_end_date->format('d M Y, H:i') }} WIB</p>
                    </div>
                    @php
                        $now = now();
                        $isActive = $now->between($property->sale_start_date, $property->sale_end_date);
                        $isUpcoming = $now->lt($property->sale_start_date);
                        $isEnded = $now->gt($property->sale_end_date);
                    @endphp
                    <div class="text-center">
                        @if ($isUpcoming)
                            <span class="badge bg-warning">Belum Dimulai</span>
                        @elseif($isActive)
                            <span class="badge bg-success">Sedang Berlangsung</span>
                        @else
                            <span class="badge bg-danger">Telah Berakhir</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('seller.properties.edit', $property) }}" class="btn btn-warning">
                            <i class="ri-edit-line me-1"></i> Edit Properti
                        </a>
                        <a href="{{ route('seller.properties.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Kembali ke Daftar
                        </a>
                        @if ($property->orders()->count() === 0)
                            <button type="button" class="btn btn-danger" onclick="deleteProperty()">
                                <i class="ri-delete-bin-line me-1"></i> Hapus Properti
                            </button>
                        @endif
                    </div>
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
                    <p>Apakah Anda yakin ingin menghapus properti <strong>{{ $property->title }}</strong>?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('seller.properties.destroy', $property) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .property-main-image {
            height: 400px;
            object-fit: cover;
        }

        .property-thumbnail {
            height: 60px;
            object-fit: cover;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .property-thumbnail:hover,
        .property-thumbnail.active {
            opacity: 1;
        }
    </style>

    <script>
        function goToSlide(index) {
            const carousel = bootstrap.Carousel.getInstance(document.getElementById('propertyCarousel'));
            carousel.to(index);

            // Update thumbnail active state
            document.querySelectorAll('.property-thumbnail').forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });
        }

        function deleteProperty() {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        // Update thumbnail active state when carousel changes
        document.getElementById('propertyCarousel').addEventListener('slide.bs.carousel', function(e) {
            document.querySelectorAll('.property-thumbnail').forEach((thumb, i) => {
                thumb.classList.toggle('active', i === e.to);
            });
        });
    </script>
@endsection
