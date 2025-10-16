@extends('layouts.web')

@section('title', 'Daftar Order Saya')

@section('content')
    <div class="bg-slate-50 min-h-screen">
        <div class="container mx-auto px-6 py-12">

            {{-- Header Halaman --}}
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
                <h1 class="text-3xl font-bold text-slate-900">Order Saya</h1>
                <a href="{{ route('buyer.home') }}"
                    class="inline-flex justify-center items-center gap-2 w-full sm:w-auto text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                    <i class="fa-solid fa-plus"></i>
                    Beli Kupon Baru
                </a>
            </div>

            {{-- Filter --}}
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form method="GET" action="{{ route('buyer.orders.index') }}"
                    class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <div class="flex-grow">
                        <label for="status" class="sr-only">Filter by status</label>
                        <div class="relative">
                            <select name="status" id="status"
                                class="appearance-none block w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 py-2.5 pl-3 pr-10">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu
                                    Pembayaran</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Sudah Dibayar
                                </option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <button type="submit"
                            class="inline-flex items-center justify-center w-1/2 sm:w-auto gap-2 text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg text-sm px-4 py-2.5">
                            <i class="fa-solid fa-filter"></i>
                            Filter
                        </button>
                        <a href="{{ route('buyer.orders.index') }}"
                            class="inline-flex items-center justify-center w-1/2 sm:w-auto gap-2 text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 font-semibold rounded-lg text-sm px-4 py-2.5">
                            <i class="fa-solid fa-redo"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Daftar Order --}}
            <div class="space-y-4">
                @forelse($orders as $order)
                    @php
                        // Logika untuk menentukan warna status badge
                        $statusBadgeClasses = match ($order->status) {
                            'paid' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'failed' => 'bg-red-100 text-red-800',
                            'cancelled' => 'bg-slate-100 text-slate-800',
                            default => 'bg-slate-100 text-slate-800',
                        };
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden transition hover:shadow-md">
                        <div class="p-4 flex flex-col md:flex-row items-start md:items-center gap-4">

                            {{-- Gambar Properti --}}
                            <div class="flex-shrink-0 w-full md:w-32">
                                @if ($order->property->images->count() > 0)
                                    <img src="{{ asset('storage/' . $order->property->images->first()->image_path) }}"
                                        class="w-full h-32 md:w-32 md:h-24 object-cover rounded-md"
                                        alt="{{ $order->property->title }}">
                                @else
                                    <div
                                        class="bg-slate-100 rounded-md w-full h-32 md:w-32 md:h-24 flex items-center justify-center">
                                        <i class="fas fa-image text-3xl text-slate-400"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Info Order --}}
                            <div class="flex-grow">
                                <a href="{{ route('buyer.orders.show', $order) }}"
                                    class="font-bold text-slate-800 hover:text-blue-600 text-lg leading-tight">
                                    {{ $order->property->title }}
                                </a>
                                <p class="text-sm text-slate-500 mt-1">Order #{{ $order->id }} &bull;
                                    {{ $order->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-sm text-slate-600 font-medium mt-1">{{ $order->quantity }} Kupon</p>
                                @if ($order->status == 'paid' && $order->coupons->count() > 0)
                                    <p class="text-sm text-green-600 font-semibold mt-1 flex items-center gap-1.5">
                                        <i class="fa-solid fa-ticket-alt"></i>
                                        {{ $order->coupons->count() }} kupon telah dibuat
                                    </p>
                                @endif
                            </div>

                            {{-- Status & Harga --}}
                            <div class="w-full md:w-48 text-left md:text-center flex-shrink-0">
                                <span
                                    class="inline-block px-3 py-1 text-xs font-semibold tracking-wider uppercase rounded-full {{ $statusBadgeClasses }}">
                                    {{ $order->status_label }}
                                </span>
                                <p class="text-lg font-bold text-blue-600 mt-2">Rp {{ number_format($order->total_price) }}
                                </p>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="w-full md:w-36 flex-shrink-0">
                                <div class="flex flex-row md:flex-col items-stretch gap-2">
                                    <a href="{{ route('buyer.orders.show', $order) }}"
                                        class="flex-1 text-center text-sm font-semibold py-2 px-3 rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100">Detail</a>

                                    @if ($order->status === 'pending')
                                        <a href="{{ route('buyer.orders.payment', $order) }}"
                                            class="flex-1 text-center text-sm font-semibold py-2 px-3 rounded-md text-white bg-green-600 hover:bg-green-700">Bayar</a>
                                    @elseif(in_array($order->status, ['failed', 'cancelled']))
                                        <span class="flex-1 text-center text-sm font-medium py-2 px-3 rounded-md text-red-700 bg-red-100">
                                            Gagal/Dibatalkan
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm text-center py-16 px-6">
                        <div class="text-5xl text-slate-300">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-slate-800">Anda Belum Memiliki Order</h3>
                        <p class="mt-2 text-slate-500">Semua order yang Anda buat akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
