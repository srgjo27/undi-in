@extends('layouts.auth.main')

@section('title', 'Login')

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
                    <p class="mt-3 fs-15 fw-medium">Lorem ipsum dolor sit amet</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4">
                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <h5 class="text-primary">Welcome Back</h5>
                        </div>
                        <div class="p-2 mt-4">

                            @if (session('error'))
                                @if (str_contains(session('error'), 'blocked by administrator'))
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <i class="las la-user-lock fs-20 text-warning"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="alert-heading mb-2">Account Blocked</h6>
                                                <p class="mb-2">{{ session('error') }}</p>
                                                <hr class="my-2">
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#contactAdminModal">
                                                        <i class="las la-envelope me-1"></i>
                                                        Contact Admin
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#contactMethodsModal">
                                                        <i class="las la-phone me-1"></i>
                                                        Other Contact Methods
                                                    </button>
                                                </div>
                                                <small class="text-muted d-block mt-2">
                                                    <i class="las la-info-circle me-1"></i>
                                                    Admin typically responds within 24-48 hours
                                                </small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @else
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="las la-exclamation-circle me-2"></i>
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="las la-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form class="" novalidate action="/login" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter email address" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-2">
                                    <label for="password" class="form-label">Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter password">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="auth-remember-check"
                                        name="remember">
                                    <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-success w-100" type="submit">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <p class="mb-0">Don't have an account ? <a href="{{ route('register') }}"
                            class="fw-semibold text-primary text-decoration-underline">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
