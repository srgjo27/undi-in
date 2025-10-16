@extends('layouts.web')

@section('title', 'Pembayaran Order #' . $order->id)

@section('content')
    <div class="bg-slate-50 min-h-screen py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-6 space-y-6">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-slate-900">Selesaikan Pembayaran Anda</h1>
                <p class="text-slate-500 mt-2">Transfer manual ke rekening seller untuk mendapatkan kupon properti impian Anda.</p>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-800">Ringkasan Pesanan</h2>
                </div>
                <div class="p-6 flex items-start gap-4">
                    <div class="flex-shrink-0">
                        @if ($order->property->images->count() > 0)
                            <img src="{{ asset('storage/' . $order->property->images->first()->image_path) }}"
                                class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-md"
                                alt="{{ $order->property->title }}">
                        @else
                            <div class="bg-slate-100 rounded-md w-24 h-24 sm:w-32 sm:h-32 flex items-center justify-center">
                                <i class="fas fa-image text-3xl text-slate-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-semibold text-slate-800 line-clamp-2">{{ $order->property->title }}</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            {{ $order->property->city }}, {{ $order->property->province }}
                        </p>
                        <p class="text-sm text-slate-500 mt-1">
                            Penjual: {{ $order->property->seller->name }}
                        </p>
                        <p class="text-sm font-medium text-slate-700 mt-3">
                            {{ $order->quantity }} Kupon x Rp {{ number_format($order->property->coupon_price) }}
                        </p>
                    </div>
                </div>
                <div class="p-6 bg-slate-50/50 border-t border-slate-200 space-y-3 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->total_price) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Biaya Layanan</span>
                        <span>Rp 0</span>
                    </div>
                    <div
                        class="flex justify-between items-center text-base font-bold text-slate-900 pt-3 border-t border-slate-200">
                        <span>Total Pembayaran</span>
                        <span class="text-xl text-blue-600">Rp {{ number_format($order->total_price) }}</span>
                    </div>
                </div>
            </div>
            
            @php
                $seller = $order->property->seller;
            @endphp
            
            @if($seller && $seller->hasCompleteBankInfo())
            <!-- Bank Transfer Information -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-university text-blue-600 mr-2"></i>
                        Informasi Transfer Bank
                    </h2>
                </div>
                <div class="p-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-slate-600">Bank</label>
                                <p class="text-lg font-bold text-slate-800">{{ $seller->bank_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-600">Nomor Rekening</label>
                                <p class="text-lg font-bold text-slate-800 font-mono">{{ $seller->bank_account_number }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-slate-600">Nama Pemilik</label>
                                <p class="text-lg font-bold text-slate-800">{{ $seller->bank_account_name }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                            <div class="text-sm text-yellow-800">
                                <p class="font-medium mb-2">Instruksi Pembayaran:</p>
                                <ol class="list-decimal list-inside space-y-1">
                                    <li>Transfer sejumlah <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong> ke rekening di atas</li>
                                    <li>Simpan bukti transfer Anda</li>
                                    <li>Upload bukti transfer di form di bawah ini</li>
                                    <li>Seller akan memverifikasi pembayaran dalam 1x24 jam</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    @if($order->status === 'pending')
                    <!-- Upload Transfer Proof Form -->
                    <form action="{{ route('buyer.orders.upload-transfer-proof', $order) }}" method="POST" enctype="multipart/form-data" id="transfer-form">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="transfer_proof" class="block text-sm font-medium text-slate-700 mb-2">
                                    Upload Bukti Transfer <span class="text-red-500">*</span>
                                </label>
                                <input type="file" 
                                    id="transfer_proof" 
                                    name="transfer_proof" 
                                    accept="image/*,.pdf"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-300 rounded-lg cursor-pointer"
                                    required>
                                <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, PDF. Maksimal 2MB.</p>
                                @error('transfer_proof')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <button type="submit" 
                                    class="w-full flex justify-center items-center gap-2 text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-semibold rounded-lg text-base px-5 py-3 text-center transition-colors">
                                    <i class="fas fa-upload"></i>
                                    Upload Bukti Transfer
                                </button>
                            </div>
                        </div>
                    </form>
                    @elseif($order->status === 'awaiting_verification')
                    <!-- Awaiting Verification Status -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-clock text-blue-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-blue-800">Bukti transfer sudah diterima</p>
                                <p class="text-sm text-blue-600">Menunggu verifikasi dari seller. Anda akan mendapat notifikasi setelah pembayaran diverifikasi.</p>
                                @if($order->transfer_proof)
                                    <a href="{{ asset('storage/' . $order->transfer_proof) }}" target="_blank" 
                                        class="inline-flex items-center mt-2 text-xs text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye mr-1"></i> Lihat Bukti Transfer
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @elseif($order->status === 'paid')
                    <!-- Payment Verified -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-green-800">Pembayaran telah diverifikasi</p>
                                <p class="text-sm text-green-600">Transfer Anda telah dikonfirmasi oleh seller. Terima kasih!</p>
                                @if($order->verified_at)
                                    <p class="text-xs text-green-500 mt-1">Diverifikasi pada: {{ $order->verified_at->format('d M Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @elseif($order->status === 'failed')
                    <!-- Payment Failed -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-times-circle text-red-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-red-800">Pembayaran ditolak</p>
                                <p class="text-sm text-red-600">
                                    @if($order->verification_notes)
                                        {{ $order->verification_notes }}
                                    @else
                                        Transfer tidak dapat diverifikasi. Silakan hubungi seller untuk informasi lebih lanjut.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ route('buyer.orders.index') }}"
                            class="w-full inline-block text-center text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors py-2 border border-slate-300 rounded-lg">
                            Kembali ke Daftar Order
                        </a>
                    </div>
                </div>
            </div>
            @else
            <!-- Seller hasn't set up bank account -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 text-center">
                    <div class="text-yellow-500 mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 mb-2">Informasi Bank Belum Tersedia</h2>
                    <p class="text-slate-600 mb-4">
                        Seller belum mengatur informasi rekening bank. Silakan hubungi seller untuk menyelesaikan pembayaran.
                    </p>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-sm text-slate-600">
                            <strong>Seller:</strong> {{ $seller->name }}<br>
                            @if($seller->email)
                                <strong>Email:</strong> {{ $seller->email }}<br>
                            @endif
                            @if($seller->phone_number)
                                <strong>Phone:</strong> {{ $seller->phone_number }}
                            @endif
                        </p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('buyer.orders.index') }}"
                            class="w-full inline-block text-center text-sm font-medium text-slate-600 hover:text-blue-600 transition-colors py-2 border border-slate-300 rounded-lg">
                            Kembali ke Daftar Order
                        </a>
                    </div>
                </div>
            </div>
            @endif
            <div id="payment-status" class="hidden">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Upload transfer proof form handling
    const transferForm = document.getElementById('transfer-form');
    if (transferForm) {
        transferForm.addEventListener('submit', function(e) {
            const submitBtn = transferForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengupload...
            `;
            
            // Form will submit naturally, this is just for UX
        });
    }

    // File input validation
    const fileInput = document.getElementById('transfer_proof');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    e.target.value = '';
                    return;
                }
                
                // Check file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
                    e.target.value = '';
                    return;
                }
            }
        });
    }
</script>
@endpush
