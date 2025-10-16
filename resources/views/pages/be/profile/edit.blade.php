@extends('layouts.be')

@section('title', 'Profile Edit')

@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ asset('template/be/dist/default/assets/images/profile-bg.jpg') }}" class="profile-wid-img"
                alt="">
            <div class="overlay-content">
                <div class="text-end p-3">
                    <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                        <input id="profile-foreground-img-file-input" type="file"
                            class="profile-foreground-img-file-input">
                        <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">
                            <i class="ri-image-edit-line align-bottom me-1"></i> Change Cover
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="{{ asset('template/be/dist/default/assets/images/users/avatar-1.jpg') }}"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">{{ Auth::user()->name }}</h5>
                        <p class="text-muted mb-0">{{ Auth::user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="las la-home"></i>
                                Personal Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="las la-user"></i>
                                Change Password
                            </a>
                        </li>
                        @if(Auth::user()->isSeller())
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#bankAccount" role="tab">
                                <i class="las la-university"></i>
                                Bank Account
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            @if (session('success'))
                                <div class="alert alert-success alert-border-left alert-dismissible fade show"
                                    role="alert">
                                    <i class="ri-check-line me-3 align-middle fs-16"></i><strong>Success!</strong>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-border-left alert-dismissible fade show"
                                    role="alert">
                                    <i class="ri-alert-line me-3 align-middle fs-16"></i><strong>Error!</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="post">
                                @csrf
                                @method('patch')
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="alert alert-warning alert-border-left alert-dismissible fade show"
                                            role="alert">
                                            <i class="ri-alert-line me-3 align-middle fs-16"></i><strong>Warning</strong>
                                            : Your email <strong>{{ Auth::user()->email }}</strong> not verified
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" placeholder="Enter your name"
                                                value="{{ old('name', Auth::user()->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" placeholder="Enter your email"
                                                value="{{ old('email', Auth::user()->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label">Phone Number</label>
                                            <input type="text"
                                                class="form-control @error('phone_number') is-invalid @enderror"
                                                id="phone_number" name="phone_number"
                                                placeholder="Enter your phone number"
                                                value="{{ old('phone_number', Auth::user()->phone_number) }}">
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <input type="text" class="form-control" id="role" placeholder="Role"
                                                value="{{ ucfirst(Auth::user()->role) }}" disabled readonly>
                                            <small class="text-muted">Role cannot be changed</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                                placeholder="Enter your address">{{ old('address', Auth::user()->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-save-line align-bottom me-1"></i> Update Profile
                                            </button>
                                            <a href="{{ route('profile') }}" class="btn btn-soft-secondary">
                                                <i class="ri-close-line align-bottom me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            @if (session('password_success'))
                                <div class="alert alert-success alert-border-left alert-dismissible fade show"
                                    role="alert">
                                    <i class="ri-check-line me-3 align-middle fs-16"></i><strong>Success!</strong>
                                    {{ session('password_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('profile.password.update') }}" method="post">
                                @csrf
                                @method('patch')
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="current_password" class="form-label">Current Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                id="current_password" name="current_password"
                                                placeholder="Enter current password" required>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="password" class="form-label">New Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password" name="password" placeholder="Enter new password" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="password_confirmation" class="form-label">Confirm Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Confirm new password" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="alert alert-info alert-border-left">
                                            <i class="ri-information-line align-middle fs-16"></i>
                                            <strong>Password Requirements:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li>Minimum 8 characters</li>
                                                <li>Make sure to use a strong password</li>
                                                <li>Don't reuse your current password</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-success">
                                                <i class="ri-lock-password-line align-bottom me-1"></i> Change Password
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if(Auth::user()->isSeller())
                        <div class="tab-pane" id="bankAccount" role="tabpanel">
                            @if (session('bank_success'))
                                <div class="alert alert-success alert-border-left alert-dismissible fade show"
                                    role="alert">
                                    <i class="ri-check-line me-3 align-middle fs-16"></i><strong>Success!</strong>
                                    {{ session('bank_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="alert alert-info alert-border-left">
                                <i class="ri-information-line align-middle fs-16"></i>
                                <strong>Bank Account Information</strong>
                                <p class="mb-0 mt-2">
                                    Masukkan informasi rekening bank Anda untuk menerima pembayaran dari pembeli. 
                                    Informasi ini akan ditampilkan kepada pembeli saat melakukan transfer manual.
                                </p>
                            </div>

                            <form action="{{ route('profile.bank.update') }}" method="post">
                                @csrf
                                @method('patch')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="bank_name" class="form-label">Nama Bank <span class="text-danger">*</span></label>
                                            <select class="form-select @error('bank_name') is-invalid @enderror" 
                                                    id="bank_name" name="bank_name" required>
                                                <option value="">Pilih Bank</option>
                                                <option value="BCA" {{ old('bank_name', Auth::user()->bank_name) == 'BCA' ? 'selected' : '' }}>BCA</option>
                                                <option value="BNI" {{ old('bank_name', Auth::user()->bank_name) == 'BNI' ? 'selected' : '' }}>BNI</option>
                                                <option value="BRI" {{ old('bank_name', Auth::user()->bank_name) == 'BRI' ? 'selected' : '' }}>BRI</option>
                                                <option value="Mandiri" {{ old('bank_name', Auth::user()->bank_name) == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                                <option value="CIMB Niaga" {{ old('bank_name', Auth::user()->bank_name) == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                                                <option value="Danamon" {{ old('bank_name', Auth::user()->bank_name) == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                                                <option value="Permata" {{ old('bank_name', Auth::user()->bank_name) == 'Permata' ? 'selected' : '' }}>Permata</option>
                                                <option value="BTPN" {{ old('bank_name', Auth::user()->bank_name) == 'BTPN' ? 'selected' : '' }}>BTPN</option>
                                                <option value="BSI" {{ old('bank_name', Auth::user()->bank_name) == 'BSI' ? 'selected' : '' }}>BSI</option>
                                                <option value="Lainnya" {{ old('bank_name', Auth::user()->bank_name) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('bank_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="bank_account_number" class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                class="form-control @error('bank_account_number') is-invalid @enderror"
                                                id="bank_account_number" name="bank_account_number" 
                                                placeholder="Contoh: 1234567890"
                                                value="{{ old('bank_account_number', Auth::user()->bank_account_number) }}" 
                                                required>
                                            @error('bank_account_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="bank_account_name" class="form-label">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                class="form-control @error('bank_account_name') is-invalid @enderror"
                                                id="bank_account_name" name="bank_account_name" 
                                                placeholder="Contoh: John Doe (sesuai rekening bank)"
                                                value="{{ old('bank_account_name', Auth::user()->bank_account_name) }}" 
                                                required>
                                            @error('bank_account_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Pastikan nama sesuai dengan rekening bank Anda</small>
                                        </div>
                                    </div>
                                    
                                    @if(Auth::user()->hasCompleteBankInfo())
                                    <div class="col-lg-12">
                                        <div class="alert alert-success">
                                            <i class="ri-check-circle-line align-middle fs-16"></i>
                                            <strong>Informasi Bank Saat Ini:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li><strong>Bank:</strong> {{ Auth::user()->bank_name }}</li>
                                                <li><strong>No. Rekening:</strong> {{ Auth::user()->bank_account_number }}</li>
                                                <li><strong>Nama Pemilik:</strong> {{ Auth::user()->bank_account_name }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-save-line align-bottom me-1"></i> 
                                                {{ Auth::user()->hasCompleteBankInfo() ? 'Update Bank Account' : 'Save Bank Account' }}
                                            </button>
                                            <a href="{{ route('profile') }}" class="btn btn-soft-secondary">
                                                <i class="ri-close-line align-bottom me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            @if ($errors->has('current_password') || $errors->has('password') || session('password_success'))
                document.addEventListener('DOMContentLoaded', function() {
                    const passwordTab = document.querySelector('a[href="#changePassword"]');
                    const personalTab = document.querySelector('a[href="#personalDetails"]');
                    const passwordPane = document.querySelector('#changePassword');
                    const personalPane = document.querySelector('#personalDetails');

                    if (passwordTab && personalTab && passwordPane && personalPane) {
                        personalTab.classList.remove('active');
                        passwordTab.classList.add('active');
                        personalPane.classList.remove('show', 'active');
                        passwordPane.classList.add('show', 'active');
                    }
                });
            @endif
        </script>
    @endpush
@endsection
