@extends('layouts.web')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body text-center py-5">
                    @if(isset($error))
                        <div class="text-danger mb-4">
                            <i class="fas fa-exclamation-circle fa-4x"></i>
                        </div>
                        <h2 class="text-danger mb-3">Terjadi Kesalahan</h2>
                        <p class="text-muted mb-4">{{ $error }}</p>
                    @elseif($order && isset($status))
                        @if(in_array($status->transaction_status ?? '', ['settlement', 'capture']))
                            <div class="text-success mb-4">
                                <i class="fas fa-check-circle fa-4x"></i>
                            </div>
                            <h2 class="text-success mb-3">Pembayaran Berhasil!</h2>
                            <p class="text-muted mb-4">
                                Terima kasih! Pembayaran untuk Order #{{ $order->id }} telah berhasil diproses.
                                Kupon Anda akan segera dibuat.
                            </p>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Kupon untuk <strong>{{ $order->property->title }}</strong> 
                                ({{ $order->quantity }} kupon) telah berhasil dibuat.
                            </div>
                        @elseif($status->transaction_status === 'pending')
                            <div class="text-warning mb-4">
                                <i class="fas fa-clock fa-4x"></i>
                            </div>
                            <h2 class="text-warning mb-3">Pembayaran Tertunda</h2>
                            <p class="text-muted mb-4">
                                Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi lebih lanjut.
                            </p>
                        @else
                            <div class="text-danger mb-4">
                                <i class="fas fa-times-circle fa-4x"></i>
                            </div>
                            <h2 class="text-danger mb-3">Pembayaran Gagal</h2>
                            <p class="text-muted mb-4">
                                Pembayaran tidak dapat diproses. Silakan coba lagi.
                            </p>
                        @endif
                        
                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('buyer.orders.show', $order) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>Lihat Detail Order
                            </a>
                            <a href="{{ route('buyer.orders.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>Daftar Order Saya
                            </a>
                        </div>
                    @else
                        <div class="text-muted mb-4">
                            <i class="fas fa-question-circle fa-4x"></i>
                        </div>
                        <h2 class="text-muted mb-3">Status Tidak Diketahui</h2>
                        <p class="text-muted mb-4">
                            Tidak dapat mengidentifikasi status pembayaran.
                        </p>
                        
                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('buyer.orders.index') }}" class="btn btn-primary">
                                <i class="fas fa-list me-2"></i>Lihat Daftar Order
                            </a>
                        </div>
                    @endif
                    
                    <hr class="my-4">
                    
                    <div class="row text-center">
                        <div class="col-md-6">
                            <a href="{{ route('buyer.home') }}" class="btn btn-link">
                                <i class="fas fa-home me-1"></i>Kembali ke Beranda
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('buyer.coupons.index') }}" class="btn btn-link">
                                <i class="fas fa-ticket-alt me-1"></i>Lihat Kupon Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto refresh status if payment is still pending
    @if($order && isset($status) && $status->transaction_status === 'pending')
    setTimeout(function() {
        window.location.reload();
    }, 5000);
    @endif
</script>
@endpush