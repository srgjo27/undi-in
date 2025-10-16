@extends('layouts.web')

@section('title', 'Semua Properti')

@section('content')
    <div class="min-h-screen bg-slate-50">
        <!-- Header Section -->
        <div class="bg-slate-900 text-white py-16">
            <div class="container mx-auto px-6">
                <div class="text-center">
                    <h1 class="text-4xl font-bold mb-4">Semua Properti</h1>
                    <p class="text-xl text-slate-300">Jelajahi koleksi lengkap properti dari berbagai lokasi</p>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-6 py-8">
            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <form method="GET" action="{{ route('buyer.properties.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pencarian</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari properti..."
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu
                                </option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Harga Min</label>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Harga Max</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="10000000"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4 items-center">
                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Urutkan</label>
                            <select name="sort"
                                class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Harga
                                    Terendah</option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Harga
                                    Tertinggi</option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex gap-2 mt-6">
                            <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fa-solid fa-search mr-2"></i>Filter
                            </button>
                            <a href="{{ route('buyer.properties.index') }}"
                                class="bg-slate-500 text-white px-6 py-2 rounded-lg hover:bg-slate-600 transition-colors">
                                <i class="fa-solid fa-refresh mr-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Status Tabs -->
            <div class="mb-6">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('buyer.properties.index') }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                        Semua ({{ number_format($statusCounts['all']) }})
                    </a>
                    <a href="{{ route('buyer.properties.index', ['status' => 'draft']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('status') === 'draft' ? 'bg-green-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                        Draf ({{ number_format($statusCounts['draft']) }})
                    </a>
                    <a href="{{ route('buyer.properties.index', ['status' => 'active']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('status') === 'active' ? 'bg-green-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                        Aktif ({{ number_format($statusCounts['active']) }})
                    </a>
                    <a href="{{ route('buyer.properties.index', ['status' => 'pending_draw']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('status') === 'pending_draw' ? 'bg-yellow-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                        Menunggu Undian ({{ number_format($statusCounts['pending_draw']) }})
                    </a>
                    <a href="{{ route('buyer.properties.index', ['status' => 'completed']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('status') === 'completed' ? 'bg-gray-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                        Selesai ({{ number_format($statusCounts['completed']) }})
                    </a>
                    <a href="{{ route('buyer.properties.index', ['status' => 'cancelled']) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('status') === 'cancelled' ? 'bg-green-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                        Dibatalkan ({{ number_format($statusCounts['cancelled']) }})
                    </a>
                </div>
            </div>

            <!-- Results Info -->
            <div class="flex justify-between items-center mb-6">
                <p class="text-slate-600">
                    Menampilkan {{ $properties->firstItem() }} - {{ $properties->lastItem() }}
                    dari {{ number_format($properties->total()) }} properti
                </p>
                <div class="text-sm text-slate-500">
                    Halaman {{ $properties->currentPage() }} dari {{ $properties->lastPage() }}
                </div>
            </div>

            <!-- Properties Grid -->
            @if ($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach ($properties as $property)
                        <div
                            class="group bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col">
                            <a href="{{ route('buyer.properties.show', $property) }}" class="block">
                                <div class="relative h-48 overflow-hidden">
                                    @if ($property->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $property->images->first()->image_path) }}"
                                            alt="{{ $property->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                            <i class="fa-solid fa-image text-3xl text-slate-400"></i>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <div
                                        class="absolute top-3 right-3 px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $property->status === 'draft' ? 'bg-gray-600 text-white' : '' }}
                                         {{ $property->status === 'active' ? 'bg-blue-600 text-white' : '' }}
                                        {{ $property->status === 'completed' ? 'bg-green-600 text-white' : '' }}
                                        {{ $property->status === 'pending_draw' ? 'bg-yellow-600 text-white' : '' }}
                                        {{ $property->status === 'cancelled' ? 'bg-red-600 text-white' : '' }}">
                                        {{ $property->status_label }}
                                    </div>
                                </div>
                            </a>
                            <div class="p-4 flex flex-col flex-grow">
                                <a href="{{ route('buyer.properties.show', $property) }}" class="block">
                                    <h4
                                        class="text-lg font-bold text-slate-800 mb-1 group-hover:text-blue-600 transition-colors truncate">
                                        {{ $property->title }}
                                    </h4>
                                    <p class="text-sm text-slate-500 mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-map-marker-alt fa-fw"></i>
                                        <span>{{ $property->city }}, {{ $property->province }}</span>
                                    </p>
                                </a>
                                <div class="mt-auto space-y-3">
                                    <div>
                                        <p class="text-xs text-slate-500">Harga Kupon Mulai</p>
                                        <p class="text-lg font-bold text-blue-600">
                                            Rp {{ number_format($property->coupon_price, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    @if ($property->max_coupons)
                                        <div>
                                            @php
                                                $soldCoupons = $property->max_coupons - $property->available_coupons;
                                                $progress =
                                                    $property->max_coupons > 0
                                                        ? ($soldCoupons / $property->max_coupons) * 100
                                                        : 0;
                                            @endphp
                                            <div class="flex justify-between items-center text-xs text-slate-600 mb-1">
                                                <span class="font-semibold">Kupon Terjual</span>
                                                <span>{{ number_format($soldCoupons) }} /
                                                    {{ number_format($property->max_coupons) }}</span>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full"
                                                    style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                                        <span class="text-xs text-slate-500">Nilai Properti</span>
                                        <span class="text-sm font-semibold text-slate-700">
                                            Rp {{ number_format($property->price, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        <span>Seller: {{ $property->user->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $properties->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-search text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-700 mb-2">Tidak ada properti ditemukan</h3>
                    <p class="text-slate-500 mb-4">Coba ubah filter pencarian atau kata kunci Anda</p>
                    <a href="{{ route('buyer.properties.index') }}"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Lihat Semua Properti
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
