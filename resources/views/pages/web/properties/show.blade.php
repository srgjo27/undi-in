@extends('layouts.web')

@section('title', 'Detail Properti - ' . $property->title)

@section('content')
    <div class="bg-slate-50">
        <div class="container mx-auto px-6 py-12">
            <div class="mb-6">
                <a href="{{ route('buyer.home') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Daftar Properti
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 lg:gap-8">
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        @if ($property->images->count() > 0)
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="{{ asset('storage/' . $property->images->first()->image_path) }}"
                                    alt="{{ $property->title }}" class="w-full h-full object-cover">
                            </div>
                            @if ($property->images->count() > 1)
                                <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 p-4">
                                    @foreach ($property->images as $image)
                                        <a href="{{ asset('storage/' . $image->image_path) }}"
                                            class="block border-2 border-transparent hover:border-blue-500 rounded-md overflow-hidden transition">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumbnail"
                                                class="w-full h-20 object-cover">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="flex items-center justify-center h-80 bg-slate-100 text-slate-400">
                                <div class="text-center">
                                    <i class="fa-solid fa-image text-5xl"></i>
                                    <p class="mt-2 font-medium">Gambar tidak tersedia</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 space-y-8">
                        <div>
                            <span
                                class="inline-block px-3 py-1 text-xs font-semibold tracking-wider uppercase rounded-full bg-blue-100 text-blue-800">
                                {{ $property->status_label }}
                            </span>
                            <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ $property->title }}</h1>
                            <p class="text-slate-500 flex items-start gap-2 mt-2">
                                <i class="fa-solid fa-map-marker-alt text-slate-400 mt-1"></i>
                                <span>{{ $property->address }}, {{ $property->city }}, {{ $property->province }}</span>
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Harga Properti</h3>
                            <p class="text-4xl font-bold text-blue-600">Rp {{ number_format($property->price) }}</p>
                        </div>
                        <div class="border-t border-slate-200"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 mb-4">Spesifikasi</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                                <div class="bg-slate-50 p-4 rounded-lg">
                                    <i class="fa-solid fa-ruler-combined text-2xl text-blue-500"></i>
                                    <p class="text-sm text-slate-500 mt-2">Luas Tanah</p>
                                    <p class="font-bold text-slate-800">{{ $property->land_area }} m²</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-lg">
                                    <i class="fa-solid fa-home text-2xl text-blue-500"></i>
                                    <p class="text-sm text-slate-500 mt-2">Luas Bangunan</p>
                                    <p class="font-bold text-slate-800">{{ $property->building_area }} m²</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-lg">
                                    <i class="fa-solid fa-bed text-2xl text-blue-500"></i>
                                    <p class="text-sm text-slate-500 mt-2">Kamar Tidur</p>
                                    <p class="font-bold text-slate-800">{{ $property->bedrooms }}</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-lg">
                                    <i class="fa-solid fa-bath text-2xl text-blue-500"></i>
                                    <p class="text-sm text-slate-500 mt-2">Kamar Mandi</p>
                                    <p class="font-bold text-slate-800">{{ $property->bathrooms }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 mb-2">Deskripsi</h3>
                            <div class="prose max-w-none text-slate-600">
                                {!! nl2br(e($property->description)) !!}
                            </div>
                        </div>
                        @if (!empty($property->facilities))
                            <div class="border-t border-slate-200 pt-8">
                                <h3 class="text-lg font-semibold text-slate-800 mb-4">Fasilitas</h3>
                                <div class="flex flex-wrap gap-3">
                                    @foreach ($property->facilities as $facility)
                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-full">
                                            <i class="fa-solid fa-check text-green-500"></i>
                                            {{ $facility }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <div class="sticky top-24 space-y-6">
                        <div class="bg-white rounded-lg shadow-md">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-900">Beli Kupon Undian</h2>
                            </div>
                            <div class="p-6">
                                @if ($property->status === 'active')
                                    <div class="mb-5 text-center bg-blue-50 p-4 rounded-lg">
                                        <h4 class="text-sm font-semibold text-blue-800">Penjualan Berakhir Dalam</h4>
                                        <div id="countdown"
                                            data-end-date="{{ $property->sale_end_date->toIso8601String() }}"
                                            class="text-2xl font-bold text-blue-600 tracking-wider mt-1">
                                            <span>00d</span> : <span>00h</span> : <span>00m</span> : <span>00s</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-sm font-medium text-slate-500">Harga per Kupon</label>
                                        <p class="text-3xl font-bold text-blue-600">Rp
                                            {{ number_format($property->coupon_price) }}</p>
                                    </div>

                                    @if ($property->max_coupons)
                                        <div class="mb-6">
                                            <div class="flex justify-between items-center text-sm mb-1">
                                                <span class="font-medium text-slate-600">Ketersediaan</span>
                                                <span
                                                    class="font-semibold text-slate-800">{{ number_format($property->available_coupons) }}
                                                    / {{ number_format($property->max_coupons) }}</span>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full"
                                                    style="width: {{ $property->max_coupons > 0 ? (($property->max_coupons - $property->available_coupons) / $property->max_coupons) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (!$property->max_coupons || $property->available_coupons > 0)
                                        <form action="{{ route('buyer.orders.store', $property) }}" method="POST"
                                            class="space-y-4">
                                            @csrf
                                            <div>
                                                <label for="quantity"
                                                    class="block mb-2 text-sm font-medium text-slate-700">Jumlah
                                                    Kupon</label>
                                                <div class="relative">
                                                    <select name="quantity" id="quantity"
                                                        class="appearance-none block w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 py-2.5 pl-3 pr-10">
                                                        @for ($i = 1; $i <= min(10, $property->available_coupons ?? 10); $i++)
                                                            <option value="{{ $i }}"
                                                                {{ old('quantity', 1) == $i ? 'selected' : '' }}>
                                                                {{ $i }} Kupon - (Rp
                                                                {{ number_format($property->coupon_price * $i) }})
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                @error('quantity')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <button type="submit"
                                                class="w-full flex justify-center items-center gap-2 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg text-base px-5 py-3 text-center transition-colors">
                                                <i class="fa-solid fa-shopping-cart"></i>
                                                Beli Sekarang
                                            </button>
                                        </form>
                                    @else
                                        <div class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 text-center"
                                            role="alert">
                                            <i class="fa-solid fa-exclamation-triangle mr-2"></i>
                                            <strong>Mohon maaf,</strong> kupon sudah habis terjual.
                                        </div>
                                    @endif
                                @else
                                    <div class="p-4 text-sm text-sky-800 rounded-lg bg-sky-50 text-center" role="alert">
                                        <i class="fa-solid fa-info-circle mr-2"></i>
                                        Penjualan untuk properti ini belum atau sudah tidak aktif.
                                    </div>
                                @endif

                                <div class="text-center mt-6">
                                    <p class="text-xs text-slate-400 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-shield-alt"></i>
                                        Pembayaran aman dan terjamin
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="font-semibold text-slate-800 mb-3">Bagaimana Cara Kerjanya?</h3>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-slate-600">
                                <li>Pilih jumlah kupon yang ingin dibeli.</li>
                                <li>Lakukan pembayaran melalui gateway yang aman.</li>
                                <li>Kupon Anda akan dibuat setelah pembayaran berhasil.</li>
                                <li>Tunggu pengundian untuk memenangkan properti impian!</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            const endDate = new Date(countdownElement.dataset.endDate).getTime();

            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance < 0) {
                    clearInterval(timer);
                    countdownElement.innerHTML = "Waktu Penjualan Habis";
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const pad = (num) => num.toString().padStart(2, '0');

                countdownElement.innerHTML = `
                <span>${pad(days)}d</span> : 
                <span>${pad(hours)}h</span> : 
                <span>${pad(minutes)}m</span> : 
                <span>${pad(seconds)}s</span>`;
            }, 1000);
        }
    </script>
@endpush
