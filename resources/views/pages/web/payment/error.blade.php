@extends('layouts.web')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="text-danger mb-4">
                        <i class="fas fa-times-circle fa-4x"></i>
                    </div>
                    
                    <h2 class="text-danger mb-3">Pembayaran Gagal</h2>
                    
                    <p class="text-muted mb-4">
                        Maaf, terjadi kesalahan dalam proses pembayaran. 
                        @if($order)
                            Pembayaran untuk Order #{{ $order->id }} tidak dapat diproses.
                        @endif
                        Silakan coba lagi atau gunakan metode pembayaran lain.
                    </p>

                    @if($order)
                    <div class="alert alert-light mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-8 text-start">
                                <strong>{{ $order->property->title }}</strong><br>
                                <small class="text-muted">
                                    {{ $order->quantity }} kupon × Rp {{ number_format($order->property->coupon_price) }}
                                </small>
                            </div>
                            <div class="col-md-4 text-end">
                                <strong class="text-primary">Rp {{ number_format($order->total_price) }}</strong>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-lightbulb me-2"></i>Kemungkinan Penyebab:</h6>
                        <ul class="mb-0 text-start">
                            <li>Saldo kartu tidak mencukupi</li>
                            <li>Kartu tidak aktif untuk transaksi online</li>
                            <li>Koneksi internet terputus</li>
                            <li>Batas waktu pembayaran habis</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        @if($order && $order->status === 'pending')
                            <a href="{{ route('buyer.orders.payment', $order) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>Lanjutkan Pembayaran
                            </a>
                        @endif
                        
                        <a href="{{ route('buyer.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>Lihat Daftar Order
                        </a>
                        
                        <a href="{{ route('buyer.home') }}" class="btn btn-link">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                    </div>

                    <hr class="my-4">
                    
                    <div class="row text-center">
                        <div class="col-12">
                            <h6>Butuh Bantuan?</h6>
                            <p class="small text-muted mb-2">Tim support kami siap membantu Anda</p>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#supportModal">
                                <i class="fas fa-headset me-1"></i>Hubungi Support
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Support Modal -->
<div class="modal fade" id="supportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hubungi Support</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-grid">
                            <a href="mailto:support@undi-in.com" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>Email Support
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-grid">
                            <a href="tel:+622112345678" class="btn btn-outline-success">
                                <i class="fas fa-phone me-2"></i>Telepon
                            </a>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <h6>Informasi yang Diperlukan:</h6>
                <ul class="small">
                    @if($order)
                    <li>Order ID: <code>#{{ $order->id }}</code></li>
                    <li>Waktu Transaksi: {{ now()->format('d M Y H:i') }}</li>
                    @endif
                    <li>Metode Pembayaran yang Digunakan</li>
                    <li>Pesan Error (jika ada)</li>
                </ul>
                
                <div class="alert alert-info small">
                    <i class="fas fa-clock me-1"></i>
                    Jam Operasional Support: Senin-Jumat 08:00-17:00 WIB
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection