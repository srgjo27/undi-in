@extends('layouts.be')

@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">Edit User</h1>
                            <p class="text-muted">Edit informasi pengguna</p>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="las la-arrow-left me-1"></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit User: {{ $user->name }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role <span
                                                class="text-danger">*</span></label>
                                        <select name="role" id="role"
                                            class="form-select @error('role') is-invalid @enderror" required>
                                            <option value="">Select Role</option>
                                            <option value="admin"
                                                {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="seller"
                                                {{ old('role', $user->role) === 'seller' ? 'selected' : '' }}>Seller
                                            </option>
                                            <option value="buyer"
                                                {{ old('role', $user->role) === 'buyer' ? 'selected' : '' }}>Buyer</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" name="phone_number" id="phone_number"
                                            class="form-control @error('phone_number') is-invalid @enderror"
                                            value="{{ old('phone_number', $user->phone_number) }}">
                                        @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="las la-save me-2"></i>Update User
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Information Card -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">User Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div>
                                <span class="badge bg-{{ $user->email_verified_at ? 'success' : 'secondary' }}">
                                    {{ $user->email_verified_at ? 'Active' : 'Blocked' }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Registered</label>
                            <div class="text-muted">{{ $user->created_at->format('M d, Y H:i') }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Last Updated</label>
                            <div class="text-muted">{{ $user->updated_at->format('M d, Y H:i') }}</div>
                        </div>

                        @if ($user->role === 'seller')
                            <div class="mb-3">
                                <label class="form-label">Properties</label>
                                <div class="text-muted">{{ $user->properties()->count() }} properties</div>
                            </div>
                        @endif

                        @if ($user->role === 'buyer')
                            <div class="mb-3">
                                <label class="form-label">Orders</label>
                                <div class="text-muted">{{ $user->orders()->count() }} orders</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Password Reset -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Actions</h6>
                    </div>
                    <div class="card-body">
                        @if (Auth::id() !== $user->id)
                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-outline-{{ $user->email_verified_at ? 'warning' : 'success' }} w-100"
                                        onclick="return confirm('Are you sure?')">
                                        <i class="las la-{{ $user->email_verified_at ? 'lock' : 'unlock' }} me-2"></i>
                                        {{ $user->email_verified_at ? 'Block User' : 'Activate User' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        <i class="las la-trash me-2"></i>Delete User
                                    </button>
                                </form>
                            </div>
                        @else
                            <p class="text-muted">You cannot perform actions on your own account.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
