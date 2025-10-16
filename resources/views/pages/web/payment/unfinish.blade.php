@extends('layouts.web')

@section('title', 'Pembayaran Belum Selesai')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="text-warning mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x"></i>
                    </div>
                    
                    <h2 class="text-warning mb-3">Pembayaran Belum Selesai</h2>
                    
                    <p class="text-muted mb-4">
                        Anda belum menyelesaikan proses pembayaran. 
                        @if($order)
                            Untuk melanjutkan pembayaran Order #{{ $order->id }}, 
                            silakan klik tombol di bawah ini.
                        @else
                            Silakan kembali ke halaman order untuk melanjutkan pembayaran.
                        @endif
                    </p>

                    @if($order)
                    <div class="alert alert-info mb-4">
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
                    
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-question-circle me-1"></i>
                            Butuh bantuan? <a href="#" data-bs-toggle="modal" data-bs-target="#helpModal">Hubungi Support</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Butuh Bantuan?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Jika Anda mengalami kesulitan dalam proses pembayaran, silakan hubungi kami:</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope me-2"></i>Email: support@undi-in.com</li>
                    <li><i class="fas fa-phone me-2"></i>Telepon: +62 21 1234 5678</li>
                    <li><i class="fas fa-clock me-2"></i>Jam Operasional: 08:00 - 17:00 WIB</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection