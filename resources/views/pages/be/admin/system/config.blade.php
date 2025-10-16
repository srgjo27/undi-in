@extends('layouts.be')

@section('title', 'System Configuration')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">System Configuration</h1>
                            <p class="text-muted">Konfigurasi pengaturan sistem dan aplikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Form -->
        <form action="{{ route('admin.system.config.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Application Settings -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Application Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="app_name" class="form-label">Application Name</label>
                                <input type="text" name="app_name" id="app_name"
                                    class="form-control @error('app_name') is-invalid @enderror"
                                    value="{{ old('app_name', $config['app']['name'] ?? '') }}" required>
                                @error('app_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="app_description" class="form-label">Description</label>
                                <textarea name="app_description" id="app_description"
                                    class="form-control @error('app_description') is-invalid @enderror" rows="3">{{ old('app_description', $config['app']['description'] ?? '') }}</textarea>
                                @error('app_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" id="contact_email"
                                    class="form-control @error('contact_email') is-invalid @enderror"
                                    value="{{ old('contact_email', $config['contact']['email'] ?? '') }}" required>
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Phone Number</label>
                                <input type="text" name="contact_phone" id="contact_phone"
                                    class="form-control @error('contact_phone') is-invalid @enderror"
                                    value="{{ old('contact_phone', $config['contact']['phone'] ?? '') }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $config['contact']['address'] ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Commission & Pricing -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Commission & Pricing</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="admin_commission_percentage" class="form-label">Admin Commission (%)</label>
                                <input type="number" name="admin_commission_percentage" id="admin_commission_percentage"
                                    class="form-control @error('admin_commission_percentage') is-invalid @enderror"
                                    value="{{ old('admin_commission_percentage', $config['commission']['admin_percentage'] ?? 10) }}"
                                    min="0" max="100" step="0.01" required>
                                @error('admin_commission_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_coupon_price" class="form-label">Min Coupon Price</label>
                                        <input type="number" name="min_coupon_price" id="min_coupon_price"
                                            class="form-control @error('min_coupon_price') is-invalid @enderror"
                                            value="{{ old('min_coupon_price', $config['commission']['min_coupon_price'] ?? 100000) }}"
                                            min="0" step="1000" required>
                                        @error('min_coupon_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_coupon_price" class="form-label">Max Coupon Price</label>
                                        <input type="number" name="max_coupon_price" id="max_coupon_price"
                                            class="form-control @error('max_coupon_price') is-invalid @enderror"
                                            value="{{ old('max_coupon_price', $config['commission']['max_coupon_price'] ?? 1000000) }}"
                                            min="0" step="1000" required>
                                        @error('max_coupon_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Raffle Settings -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Raffle Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="min_coupons_for_raffle" class="form-label">Minimum Coupons for Raffle</label>
                                <input type="number" name="min_coupons_for_raffle" id="min_coupons_for_raffle"
                                    class="form-control @error('min_coupons_for_raffle') is-invalid @enderror"
                                    value="{{ old('min_coupons_for_raffle', $config['raffle']['min_coupons'] ?? 10) }}"
                                    min="1" required>
                                @error('min_coupons_for_raffle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="raffle_duration_days" class="form-label">Raffle Duration (Days)</label>
                                <input type="number" name="raffle_duration_days" id="raffle_duration_days"
                                    class="form-control @error('raffle_duration_days') is-invalid @enderror"
                                    value="{{ old('raffle_duration_days', $config['raffle']['duration_days'] ?? 30) }}"
                                    min="1" required>
                                @error('raffle_duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="las la-save me-2"></i>Save Configuration
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="las la-undo me-2"></i>Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection
