@extends('layouts.auth.main')

@section('title', 'Verify Email')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mt-sm-5 mb-4 text-white-50">
                    <div>
                        <a href="#" class="d-inline-block auth-logo">
                            <img src="" alt="undi-in" height="20">
                        </a>
                    </div>
                    <p class="mt-3 fs-15 fw-medium">Verifikasi Email Anda</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4">
                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <div class="avatar-md mx-auto">
                                <div class="avatar-title rounded-circle bg-light">
                                    <i class="las la-envelope fs-1 text-primary"></i>
                                </div>
                            </div>
                            <div class="p-2 mt-4">
                                <h4>Verify your email</h4>
                                <p class="text-muted">We have sent you verification instructions to your email address.</p>

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="las la-check-circle me-2"></i>
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="las la-exclamation-circle me-2"></i>
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <form action="{{ route('verification.send') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="las la-paper-plane me-2"></i>
                                            Kirim Ulang Email Verifikasi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <p class="mb-0">Already verified? <a href="{{ route('login') }}"
                            class="fw-semibold text-primary text-decoration-underline">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
