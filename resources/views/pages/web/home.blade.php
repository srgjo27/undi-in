@extends('layouts.web')

@section('title', 'Temukan Properti Impian Anda')

@section('content')
    <div class="relative h-[600px] bg-slate-900 flex items-center text-white overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1568605114967-8130f3a36994?q=80&w=2070&auto=format&fit=crop"
                alt="Rumah Modern" class="w-full h-full object-cover opacity-40">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/70 to-transparent"></div>
        <div class="relative container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
                Temukan Properti Impian <br> Anda Bersama Kami
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">
                Jelajahi ribuan listing properti terbaik di lokasi strategis dengan penawaran eksklusif.
            </p>
            <form action="#" method="GET"
                class="max-w-xl mx-auto bg-white/10 backdrop-blur-md p-4 rounded-full flex items-center gap-2 border border-slate-600">
                <i class="fa-solid fa-search text-slate-300 ml-4"></i>
                <input type="text" placeholder="Cari berdasarkan lokasi, tipe, atau nama properti..."
                    class="w-full bg-transparent text-white placeholder-slate-400 focus:outline-none py-2">
                <button type="submit"
                    class="bg-blue-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-blue-700 transition-colors">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <div class="container mx-auto px-6 py-16">
        <section id="featured" class="mb-20">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-slate-900">Properti Baru</h2>
                <p class="text-lg text-slate-600 mt-2">Jelajahi properti terbaru yang kami tawarkan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($featuredProperties as $property)
                    <div
                        class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col">
                        <a href="{{ route('buyer.properties.show', $property) }}" class="block">
                            <div class="relative h-56 overflow-hidden">
                                @if ($property->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $property->images->first()->image_path) }}"
                                        alt="{{ $property->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                        <i class="fa-solid fa-image text-4xl text-slate-400"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div
                                    class="absolute top-3 right-3 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $property->status_label }}
                                </div>
                            </div>
                        </a>
                        <div class="p-5 flex flex-col flex-grow">
                            <a href="{{ route('buyer.properties.show', $property) }}" class="block">
                                <h4
                                    class="text-lg font-bold text-slate-800 mb-1 group-hover:text-blue-600 transition-colors truncate">
                                    {{ $property->title }}
                                </h4>
                                <p class="text-sm text-slate-500 mb-4 flex items-center gap-2">
                                    <i class="fa-solid fa-map-marker-alt fa-fw"></i>
                                    <span>{{ $property->city }}, {{ $property->province }}</span>
                                </p>
                            </a>
                            <div class="mt-auto space-y-3">
                                <div>
                                    <p class="text-sm text-slate-500">Harga Kupon Mulai</p>
                                    <p class="text-xl font-bold text-blue-600">
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
                                            <span>{{ number_format($soldCoupons) }} dari
                                                {{ number_format($property->max_coupons) }}</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                                    <span class="text-xs text-slate-500">Nilai Properti</span>
                                    <span class="text-sm font-semibold text-slate-700">Rp
                                        {{ number_format($property->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('buyer.properties.index') }}"
                    class="bg-slate-800 text-white px-8 py-3 rounded-full font-semibold hover:bg-slate-900 transition-colors transform hover:scale-105">
                    Lihat Semua Properti
                </a>
            </div>
        </section>

        <section id="keunggulan" class="bg-slate-100 rounded-2xl p-12">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-slate-900">Mengapa Memilih Kami?</h2>
                <p class="text-lg text-slate-600 mt-2">Kami memberikan pelayanan terbaik untuk kebutuhan properti Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div
                        class="w-20 h-20 bg-blue-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl shadow-lg">
                        <i class="fa-solid fa-medal"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Listing Terverifikasi</h3>
                    <p class="text-slate-600">Semua properti telah melewati proses verifikasi yang ketat untuk menjamin kualitas dan legalitas.</p>
                </div>
                <div class="text-center p-6">
                    <div
                        class="w-20 h-20 bg-blue-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl shadow-lg">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Agen Profesional</h3>
                    <p class="text-slate-600">Tim agen kami siap membantu Anda di setiap langkah, dari pencarian hingga transaksi selesai.</p>
                </div>
                <div class="text-center p-6">
                    <div
                        class="w-20 h-20 bg-blue-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl shadow-lg">
                        <i class="fa-solid fa-handshake-simple"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Transaksi Aman</h3>
                    <p class="text-slate-600">Kami menjamin keamanan dan transparansi dalam setiap proses transaksi properti Anda.</p>
                </div>
            </div>
        </section>
    </div>
@endsection
