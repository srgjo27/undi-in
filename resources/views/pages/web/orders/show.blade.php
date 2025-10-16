@extends('layouts.web')

@section('title', 'Detail Order #' . $order->id)

@section('content')
    <div class="bg-slate-50 min-h-screen">
        <div class="container mx-auto px-6 py-12">

            <div class="mb-6">
                <a href="{{ route('buyer.orders.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Daftar Order
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 lg:gap-8">

                {{-- Kolom Kiri: Informasi Utama --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- 1. Card Informasi Order --}}
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
                        <div
                            class="p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                            <h1 class="text-xl font-bold text-slate-900">Detail Order #{{ $order->id }}</h1>
                            <span
                                class="inline-block px-3 py-1 text-xs font-semibold tracking-wider uppercase rounded-full {{ $statusBadgeClasses }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <div class="p-6 flex flex-col md:flex-row gap-6">
                            <div class="flex-shrink-0 w-full md:w-48">
                                @if ($order->property->images->count() > 0)
                                    <img src="{{ asset('storage/' . $order->property->images->first()->image_path) }}"
                                        class="w-full h-auto object-cover rounded-md" alt="{{ $order->property->title }}">
                                @else
                                    <div
                                        class="bg-slate-100 rounded-md w-full aspect-square flex items-center justify-center">
                                        <i class="fas fa-image text-4xl text-slate-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h2 class="text-2xl font-bold text-slate-800">{{ $order->property->title }}</h2>
                                <p class="text-slate-500 mt-1">{{ $order->property->city }},
                                    {{ $order->property->province }}</p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 mt-4 text-sm">
                                    <div>
                                        <p class="text-slate-500">Tanggal Order</p>
                                        <p class="font-semibold text-slate-700">
                                            {{ $order->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-slate-500">Penjual</p>
                                        <p class="font-semibold text-slate-700">{{ $order->property->seller->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-slate-500">Jumlah Kupon</p>
                                        <p class="font-semibold text-slate-700">{{ $order->quantity }} Kupon</p>
                                    </div>
                                    <div>
                                        <p class="text-slate-500">Harga per Kupon</p>
                                        <p class="font-semibold text-slate-700">Rp
                                            {{ number_format($order->property->coupon_price) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Card Kupon (jika ada) --}}
                    @if ($order->coupons->count() > 0)
                        <div class="bg-white rounded-lg shadow-md">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-900">Kupon Anda ({{ $order->coupons->count() }})
                                </h2>
                            </div>
                            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($order->coupons as $coupon)
                                    <div
                                        class="rounded-lg p-4 text-center border-2 
                                {{ $coupon->is_winner ? 'border-amber-400 bg-amber-50' : 'border-dashed border-slate-300 bg-white' }}">
                                        @if ($coupon->is_winner)
                                            <i class="fa-solid fa-crown text-3xl text-amber-500"></i>
                                            <p class="mt-1 text-sm font-bold uppercase text-amber-600">Pemenang!</p>
                                        @else
                                            <i class="fa-solid fa-ticket-alt text-3xl text-blue-500"></i>
                                        @endif
                                        <p class="mt-2 text-2xl font-mono font-bold tracking-widest text-slate-800">
                                            {{ $coupon->coupon_number }}</p>
                                        <p class="text-xs text-slate-500">Dibuat:
                                            {{ $coupon->created_at->format('d M Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 3. Card Riwayat Transaksi (jika ada) --}}
                    @if ($order->transactions->count() > 0)
                        <div class="bg-white rounded-lg shadow-md">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-900">Riwayat Transaksi</h2>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-slate-600">
                                    <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Waktu</th>
                                            <th scope="col" class="px-6 py-3">Metode Pembayaran</th>
                                            <th scope="col" class="px-6 py-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->transactions as $transaction)
                                            <tr class="bg-white border-b">
                                                <td class="px-6 py-4">{{ $transaction->created_at->format('d M Y, H:i') }}
                                                </td>
                                                <td class="px-6 py-4 font-medium">
                                                    {{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}</td>
                                                <td class="px-6 py-4">
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-full 
                                            {{ $transaction->status === 'success' ? 'bg-green-100 text-green-800' : ($transaction->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Ringkasan & Aksi --}}
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <div class="sticky top-24 space-y-6">
                        <div class="bg-white rounded-lg shadow-md">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-900">Ringkasan & Aksi</h2>
                            </div>
                            <div class="p-6 space-y-4">
                                {{-- Aksi Kontekstual --}}
                                @if ($order->status === 'pending')
                                    <a href="{{ route('buyer.orders.payment', $order) }}"
                                        class="w-full inline-flex justify-center items-center gap-2 text-white bg-green-600 hover:bg-green-700 font-semibold rounded-lg text-base px-5 py-3 text-center">
                                        <i class="fa-solid fa-credit-card"></i> Lanjutkan Pembayaran
                                    </a>
                                    <form action="{{ route('buyer.orders.cancel', $order) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="w-full text-sm font-semibold text-red-600 hover:text-red-800"
                                            onclick="return confirm('Yakin ingin membatalkan order ini?')">
                                            Batalkan Order
                                        </button>
                                    </form>
                                @elseif(in_array($order->status, ['failed', 'cancelled']))
                                    <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 text-center">
                                        <i class="fa-solid fa-times-circle mr-2"></i>
                                        Order dibatalkan atau gagal. Silakan buat order baru.
                                    </div>
                                @elseif($order->status === 'paid')
                                    <div class="p-4 text-sm text-green-800 rounded-lg bg-green-50 text-center"
                                        role="alert">
                                        <i class="fa-solid fa-check-circle mr-2"></i>
                                        Order sudah berhasil dibayar.
                                    </div>
                                @endif

                                <div class="border-t border-slate-200"></div>

                                {{-- Ringkasan Pembayaran --}}
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between text-slate-600">
                                        <span>Subtotal</span>
                                        <span>Rp {{ number_format($order->total_price) }}</span>
                                    </div>
                                    <div class="flex justify-between text-slate-600">
                                        <span>Biaya Layanan</span>
                                        <span>Rp 0</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center text-base font-bold text-slate-900 pt-2 border-t border-slate-200">
                                        <span>Total</span>
                                        <span class="text-xl text-blue-600">Rp
                                            {{ number_format($order->total_price) }}</span>
                                    </div>
                                </div>

                                @if ($order->paid_at)
                                    <div class="pt-2 text-center">
                                        <p class="text-xs text-green-600">
                                            <i class="fa-solid fa-check-circle"></i>
                                            Dibayar pada {{ $order->paid_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
