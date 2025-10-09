@extends('layouts.be')

@section('title', 'Seller Dashboard')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Properti</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('seller.properties.index') }}">Daftar Properti</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="{{ route('seller.properties.update', $property) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Properti</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="title">Nama/Judul Properti <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $property->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="description">Deskripsi Lengkap <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="6" required>{{ old('description', $property->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="address">Alamat Lengkap <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                        required>{{ old('address', $property->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="city">Kota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city', $property->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="province">Provinsi <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('province') is-invalid @enderror"
                                        id="province" name="province" value="{{ old('province', $property->province) }}"
                                        required>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="latitude">Latitude (Opsional)</label>
                                    <input type="number" class="form-control @error('latitude') is-invalid @enderror"
                                        id="latitude" name="latitude" value="{{ old('latitude', $property->latitude) }}"
                                        step="any">
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="longitude">Longitude (Opsional)</label>
                                    <input type="number" class="form-control @error('longitude') is-invalid @enderror"
                                        id="longitude" name="longitude"
                                        value="{{ old('longitude', $property->longitude) }}" step="any">
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-semibold mb-3 mt-4">Spesifikasi Properti</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="land_area">Luas Tanah (m²) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('land_area') is-invalid @enderror"
                                        id="land_area" name="land_area"
                                        value="{{ old('land_area', $property->land_area) }}" required min="1">
                                    @error('land_area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="building_area">Luas Bangunan (m²) <span
                                            class="text-danger">*</span></label>
                                    <input type="number"
                                        class="form-control @error('building_area') is-invalid @enderror"
                                        id="building_area" name="building_area"
                                        value="{{ old('building_area', $property->building_area) }}" required
                                        min="1">
                                    @error('building_area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="bedrooms">Kamar Tidur <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('bedrooms') is-invalid @enderror"
                                        id="bedrooms" name="bedrooms"
                                        value="{{ old('bedrooms', $property->bedrooms) }}" required min="0">
                                    @error('bedrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="bathrooms">Kamar Mandi <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('bathrooms') is-invalid @enderror"
                                        id="bathrooms" name="bathrooms"
                                        value="{{ old('bathrooms', $property->bathrooms) }}" required min="0">
                                    @error('bathrooms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Fasilitas Lainnya</label>
                                    <div class="row">
                                        @php
                                            $facilitiesOptions = [
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
                                            $selectedFacilities = old('facilities', $property->facilities ?? []);
                                        @endphp
                                        @foreach ($facilitiesOptions as $value => $label)
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="facilities[]"
                                                        value="{{ $value }}" id="facility_{{ $value }}"
                                                        {{ in_array($value, $selectedFacilities) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="facility_{{ $value }}">
                                                        {{ $label }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Images -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Gambar Properti Saat Ini</h5>
                    </div>
                    <div class="card-body">
                        @if ($property->images->count() > 0)
                            <div class="row" id="currentImages">
                                @foreach ($property->images as $image)
                                    <div class="col-md-3 mb-3" id="image-{{ $image->id }}">
                                        <div class="card">
                                            <div class="position-relative">
                                                <img src="{{ Storage::url($image->image_path) }}" class="card-img-top"
                                                    style="height: 150px; object-fit: cover;">
                                                @if ($image->is_primary)
                                                    <span
                                                        class="position-absolute top-0 start-0 badge bg-primary m-2">Utama</span>
                                                @endif
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    @if (!$image->is_primary)
                                                        <button type="button" class="btn btn-sm btn-success me-1"
                                                            onclick="setPrimaryImage({{ $image->id }})">
                                                            <i class="ri-star-line"></i>
                                                        </button>
                                                    @endif
                                                    @if ($property->images->count() > 1)
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="deleteImage({{ $image->id }})">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body p-2">
                                                <small
                                                    class="text-muted">{{ $image->caption ?: 'Tidak ada caption' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Add New Images -->
                        <div class="mb-3">
                            <label class="form-label">Tambah Gambar Baru</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror"
                                name="images[]" multiple accept="image/*" id="imageInput">
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Pilih beberapa gambar sekaligus. Format yang didukung: JPG, PNG, GIF.
                                Maksimal 2MB per gambar.</div>
                        </div>

                        <div id="imagePreview" class="row"></div>

                        <div id="primaryImageSection" style="display: none;">
                            <label class="form-label">Pilih Gambar Utama dari Gambar Baru</label>
                            <select name="primary_image" id="primaryImageSelect" class="form-select">
                                <option value="">Tetap gunakan gambar utama saat ini</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pengaturan Kupon</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="price">Harga Properti <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    id="price" name="price" value="{{ old('price', $property->price) }}" required
                                    min="0" step="1000000">
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Harga properti yang akan diperoleh pemenang undian</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="coupon_price">Harga Kupon per Unit <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('coupon_price') is-invalid @enderror"
                                    id="coupon_price" name="coupon_price"
                                    value="{{ old('coupon_price', $property->coupon_price) }}" required min="0"
                                    step="1000">
                            </div>
                            @error('coupon_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Harga untuk membeli satu kupon undian</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="max_coupons">Jumlah Total Kupon (Opsional)</label>
                            <input type="number" class="form-control @error('max_coupons') is-invalid @enderror"
                                id="max_coupons" name="max_coupons"
                                value="{{ old('max_coupons', $property->max_coupons) }}" min="1">
                            @error('max_coupons')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Kosongkan jika tidak ada batas kupon</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="sale_start_date">Tanggal Mulai Penjualan <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local"
                                class="form-control @error('sale_start_date') is-invalid @enderror" id="sale_start_date"
                                name="sale_start_date"
                                value="{{ old('sale_start_date', $property->sale_start_date->format('Y-m-d\TH:i')) }}"
                                required>
                            @error('sale_start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="sale_end_date">Tanggal Selesai Penjualan/Pengundian <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local"
                                class="form-control @error('sale_end_date') is-invalid @enderror" id="sale_end_date"
                                name="sale_end_date"
                                value="{{ old('sale_end_date', $property->sale_end_date->format('Y-m-d\TH:i')) }}"
                                required>
                            @error('sale_end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="ri-save-line me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('seller.properties.show', $property) }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Current Status Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <span class="badge {{ $property->status_badge }} fs-14">{{ $property->status_label }}</span>
                            <p class="text-muted mt-2 mb-0">Status saat ini</p>
                        </div>

                        @if ($property->sold_coupons > 0)
                            <hr>
                            <div class="text-center">
                                <h6 class="mb-1">{{ $property->sold_coupons }} kupon terjual</h6>
                                <small class="text-muted">Tidak dapat mengubah beberapa pengaturan karena sudah ada
                                    penjualan</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const primaryImageSection = document.getElementById('primaryImageSection');
            const primaryImageSelect = document.getElementById('primaryImageSelect');

            imageInput.addEventListener('change', function(e) {
                imagePreview.innerHTML = '';
                primaryImageSelect.innerHTML =
                    '<option value="">Tetap gunakan gambar utama saat ini</option>';

                const files = Array.from(e.target.files);

                if (files.length > 0) {
                    primaryImageSection.style.display = 'block';

                    files.forEach((file, index) => {
                        // Create image preview
                        const col = document.createElement('div');
                        col.className = 'col-md-3 mb-3';

                        const img = document.createElement('img');
                        img.className = 'img-thumbnail';
                        img.style.width = '100%';
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        img.file = file;

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);

                        const caption = document.createElement('input');
                        caption.type = 'text';
                        caption.className = 'form-control form-control-sm mt-2';
                        caption.placeholder = 'Caption (opsional)';
                        caption.name = `captions[${index}]`;

                        col.appendChild(img);
                        col.appendChild(caption);
                        imagePreview.appendChild(col);

                        // Add option to primary image select
                        const option = document.createElement('option');
                        option.value = index;
                        option.textContent = `Gambar Baru ${index + 1}`;
                        primaryImageSelect.appendChild(option);
                    });
                } else {
                    primaryImageSection.style.display = 'none';
                }
            });
        });

        function deleteImage(imageId) {
            if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                fetch(`/seller/property-images/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`image-${imageId}`).remove();
                            // Show success message
                            showToast('success', data.message);
                        } else {
                            showToast('error', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'Terjadi kesalahan saat menghapus gambar');
                    });
            }
        }

        function setPrimaryImage(imageId) {
            fetch(`/seller/property-images/${imageId}/set-primary`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page to update the UI
                        location.reload();
                    } else {
                        showToast('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Terjadi kesalahan saat mengatur gambar utama');
                });
        }

        function showToast(type, message) {
            // Simple toast implementation - you might want to use a proper toast library
            alert(message);
        }
    </script>
@endsection
