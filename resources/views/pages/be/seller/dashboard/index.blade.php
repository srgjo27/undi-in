@extends('layouts.be')

@section('title', 'Seller Dashboard')

@section('content')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="flex-grow-1">
                                <h4 class="fs-16 mb-1">Selamat datang, {{ Auth::user()->name }}!</h4>
                                <p class="text-muted mb-0">Berikut adalah ringkasan aktivitas properti Anda hari ini.</p>
                            </div>
                            <div class="mt-3 mt-lg-0">
                                <a href="{{ route('seller.properties.create') }}" class="btn btn-success">
                                    <i class="ri-add-circle-line align-middle me-1"></i> Tambah Properti Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Properti</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-success fs-14 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            {{ $stats['total_properties'] }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            <span class="counter-value"
                                                data-target="{{ $stats['total_properties'] }}">0</span>
                                        </h4>
                                        <a href="{{ route('seller.properties.index') }}"
                                            class="text-decoration-underline">Lihat semua properti</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-success rounded fs-3">
                                            <i class="ri-building-line text-success"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Properti Aktif</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-success fs-14 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            {{ $stats['active_properties'] }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            <span class="counter-value"
                                                data-target="{{ $stats['active_properties'] }}">0</span>
                                        </h4>
                                        <a href="{{ route('seller.properties.index', ['status' => 'active']) }}"
                                            class="text-decoration-underline">Lihat properti aktif</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info rounded fs-3">
                                            <i class="ri-check-line text-info"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Kupon Terjual</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-success fs-14 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            {{ $stats['total_coupons_sold'] }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            <span class="counter-value"
                                                data-target="{{ $stats['total_coupons_sold'] }}">0</span>
                                        </h4>
                                        <a href="#" class="text-decoration-underline">Lihat penjualan</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-warning rounded fs-3">
                                            <i class="ri-coupon-line text-warning"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Pendapatan
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <h5 class="text-success fs-14 mb-0">
                                            <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                            +{{ number_format($stats['total_earnings']) }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end justify-content-between mt-4">
                                    <div>
                                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                            Rp <span class="counter-value"
                                                data-target="{{ $stats['total_earnings'] }}">0</span>
                                        </h4>
                                        <a href="#" class="text-decoration-underline">Lihat detail pendapatan</a>
                                    </div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-primary rounded fs-3">
                                            <i class="ri-money-dollar-circle-line text-primary"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Properties -->
                @if ($recent_properties->count() > 0)
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Properti Terbaru</h4>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('seller.properties.index') }}"
                                            class="btn btn-soft-primary btn-sm">
                                            Lihat Semua Properti
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive table-card">
                                        <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Properti</th>
                                                    <th>Status</th>
                                                    <th>Harga Kupon</th>
                                                    <th>Kupon Terjual</th>
                                                    <th>Pendapatan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($recent_properties as $property)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0 me-3">
                                                                    @if ($property->primaryImage())
                                                                        <img src="{{ Storage::url($property->primaryImage()->image_path) }}"
                                                                            alt="{{ $property->title }}"
                                                                            class="avatar-sm rounded">
                                                                    @else
                                                                        <div
                                                                            class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                                                            <i class="ri-image-line text-muted"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1">
                                                                        <a href="{{ route('seller.properties.show', $property) }}"
                                                                            class="text-reset">
                                                                            {{ $property->title }}
                                                                        </a>
                                                                    </h6>
                                                                    <p class="text-muted mb-0">{{ $property->city }},
                                                                        {{ $property->province }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge {{ $property->status_badge }}">{{ $property->status_label }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="fw-medium">Rp
                                                                {{ number_format($property->coupon_price) }}</span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="text-success">{{ $property->sold_coupons }}</span>
                                                            @if ($property->max_coupons)
                                                                <small class="text-muted">/
                                                                    {{ $property->max_coupons }}</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="fw-medium text-success">
                                                                Rp
                                                                {{ number_format($property->sold_coupons * $property->coupon_price) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a href="#"
                                                                    class="btn btn-soft-secondary btn-sm dropdown-toggle"
                                                                    data-bs-toggle="dropdown">
                                                                    <i class="ri-more-fill"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('seller.properties.show', $property) }}">
                                                                        <i
                                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                                        Detail
                                                                    </a>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('seller.properties.edit', $property) }}">
                                                                        <i
                                                                            class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                                        Edit
                                                                    </a>
                                                                </div>
                                                            </div>
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
                @else
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center py-5">
                                        <div class="mb-3">
                                            <i class="ri-building-line display-4 text-muted"></i>
                                        </div>
                                        <h5 class="text-muted">Belum ada properti</h5>
                                        <p class="text-muted">Mulai tambahkan properti pertama Anda untuk memulai bisnis
                                            undian!</p>
                                        <a href="{{ route('seller.properties.create') }}" class="btn btn-primary">
                                            <i class="ri-add-line me-1"></i> Tambah Properti
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quick Stats -->
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Status Properti</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="p-2">
                                            <h5 class="mb-1">{{ $stats['draft_properties'] }}</h5>
                                            <p class="text-muted mb-0">Draft</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2">
                                            <h5 class="mb-1">{{ $stats['active_properties'] }}</h5>
                                            <p class="text-muted mb-0">Aktif</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2">
                                            <h5 class="mb-1">{{ $stats['completed_properties'] }}</h5>
                                            <p class="text-muted mb-0">Selesai</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Aksi Cepat</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('seller.properties.create') }}" class="btn btn-primary">
                                        <i class="ri-add-line me-1"></i> Tambah Properti Baru
                                    </a>
                                    <a href="{{ route('seller.properties.index') }}" class="btn btn-outline-secondary">
                                        <i class="ri-list-check me-1"></i> Kelola Properti
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
