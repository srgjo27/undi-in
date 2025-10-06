@extends('layouts.be')

@section('title', 'Seller Dashboard')

@section('content')
    <form action="{{ route('seller.properties.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
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
                                        id="title" name="title" value="{{ old('title') }}" required>
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
                                        rows="6" required>{{ old('description') }}</textarea>
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
                                        required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="city">Kota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city') }}" required>
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
                                        id="province" name="province" value="{{ old('province') }}" required>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="latitude">Latitude (Opsional)</label>
                                    <input type="number" class="form-control @error('latitude') is-invalid @enderror"
                                        id="latitude" name="latitude" value="{{ old('latitude') }}" step="any">
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="longitude">Longitude (Opsional)</label>
                                    <input type="number" class="form-control @error('longitude') is-invalid @enderror"
                                        id="longitude" name="longitude" value="{{ old('longitude') }}" step="any">
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
                                        id="land_area" name="land_area" value="{{ old('land_area') }}" required
                                        min="1">
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
                                        id="building_area" name="building_area" value="{{ old('building_area') }}"
                                        required min="1">
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
                                        id="bedrooms" name="bedrooms" value="{{ old('bedrooms') }}" required
                                        min="0">
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
                                        id="bathrooms" name="bathrooms" value="{{ old('bathrooms') }}" required
                                        min="0">
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
                                        @endphp
                                        @foreach ($facilitiesOptions as $value => $label)
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="facilities[]"
                                                        value="{{ $value }}" id="facility_{{ $value }}"
                                                        {{ in_array($value, old('facilities', [])) ? 'checked' : '' }}>
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

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Gambar Properti</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Upload Gambar <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror"
                                name="images[]" multiple accept="image/*" id="imageInput" required>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Pilih beberapa gambar sekaligus. Format yang didukung: JPG, PNG, GIF.
                                Maksimal 2MB per gambar.</div>
                        </div>

                        <div id="imagePreview" class="row"></div>

                        <div id="primaryImageSection" style="display: none;">
                            <label class="form-label">Pilih Gambar Utama <span class="text-danger">*</span></label>
                            <select name="primary_image" id="primaryImageSelect"
                                class="form-select @error('primary_image') is-invalid @enderror" required>
                                <option value="">Pilih gambar utama...</option>
                            </select>
                            @error('primary_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <label class="form-label" for="coupon_price">Harga Kupon per Unit <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('coupon_price') is-invalid @enderror"
                                    id="coupon_price" name="coupon_price" value="{{ old('coupon_price') }}" required
                                    min="0" step="1000">
                            </div>
                            @error('coupon_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="max_coupons">Jumlah Total Kupon (Opsional)</label>
                            <input type="number" class="form-control @error('max_coupons') is-invalid @enderror"
                                id="max_coupons" name="max_coupons" value="{{ old('max_coupons') }}" min="1">
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
                                name="sale_start_date" value="{{ old('sale_start_date') }}" required>
                            @error('sale_start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="sale_end_date">Tanggal Selesai Penjualan/Pengundian <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local"
                                class="form-control @error('sale_end_date') is-invalid @enderror" id="sale_end_date"
                                name="sale_end_date" value="{{ old('sale_end_date') }}" required>
                            @error('sale_end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="ri-save-line me-1"></i> Simpan Properti
                            </button>
                            <a href="{{ route('seller.properties.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Kembali
                            </a>
                        </div>
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
                primaryImageSelect.innerHTML = '<option value="">Pilih gambar utama...</option>';

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
                        option.textContent = `Gambar ${index + 1}`;
                        primaryImageSelect.appendChild(option);
                    });

                    // Auto select first image as primary
                    primaryImageSelect.value = '0';
                } else {
                    primaryImageSection.style.display = 'none';
                }
            });
        });
    </script>
@endsection
