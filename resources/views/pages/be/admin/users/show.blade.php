@extends('layouts.be')

@section('title', 'User Details - ' . $user->name)
@section('content')
    <!-- Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div>
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="mb-1">{{ $user->name }}</h2>
                                <p class="mb-2 opacity-75">{{ $user->email }}</p>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    @if ($user->email_verified_at)
                                        <h5>
                                            <span class="badge bg-success border-success text-white">
                                                <i class="las la-check-circle"></i>
                                                Active
                                            </span>
                                        </h5>
                                    @else
                                        <h5><span class="badge bg-danger border-danger text-white">
                                                <i class="las la-ban"></i>
                                                Blocked
                                            </span>
                                        </h5>
                                    @endif
                                    <h5>
                                        <span class="badge bg-white border-white text-dark">
                                            <i
                                                class="las la-{{ $user->role === 'admin' ? 'user-shield' : ($user->role === 'seller' ? 'store-alt' : 'shopping-cart') }}"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="d-flex flex-column flex-lg-row gap-2 justify-content-lg-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                                <i class="las la-arrow-left me-1"></i>
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- User Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <h5>
                        <i class="fas fa-user-circle text-primary"></i>
                        Personal Information
                    </h5>
                    <div class="row g-0">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0" scope="row">Email Address</th>
                                            <td class="text-muted">{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Phone Number</th>
                                            <td class="text-muted">{{ $user->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Address</th>
                                            <td class="text-muted">{{ $user->address }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Registration Date</th>
                                            <td class="text-muted">{{ $user->created_at->format('F d, Y \a\t H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0" scope="row">Last Updated</th>
                                            <td class="text-muted">{{ $user->updated_at->format('F d, Y \a\t H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <h5>
                        <i class="fas fa-cogs text-primary"></i>
                        Quick Actions
                    </h5>

                    @if (Auth::id() !== $user->id)
                        <div class="d-grid gap-3">
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="btn btn-{{ $user->email_verified_at ? 'warning' : 'success' }} btn-custom w-100"
                                    onclick="return confirm('{{ $user->email_verified_at ? 'This will block the user and force logout if currently logged in. Continue?' : 'This will activate the user and allow them to login again. Continue?' }}')">
                                    <i class="fas fa-{{ $user->email_verified_at ? 'user-lock' : 'user-check' }} me-2"></i>
                                    {{ $user->email_verified_at ? 'Block User' : 'Activate User' }}
                                </button>
                            </form>

                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-custom">
                                <i class="fas fa-edit me-2"></i>
                                Edit Profile
                            </a>

                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-custom w-100"
                                    onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    <i class="fas fa-trash me-2"></i>
                                    Delete User
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shield-alt fs-1 text-muted mb-3"></i>
                            <p class="text-muted">You cannot perform actions on your own account for security reasons.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Statistics -->
    @if ($user->role === 'seller' || $user->role === 'buyer')
        <div class="row g-4 mb-4">
            <div class="col-12">
                <h5>
                    <i class="fas fa-chart-line text-primary"></i>
                    Activity Statistics
                </h5>
            </div>

            @if ($user->role === 'seller')
                <div class="col-lg-3 col-md-6">
                    <div>
                        <div class="text-primary">{{ $user->properties->count() }}</div>
                        <div>Total Properties</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div>
                        <div class="text-success">
                            {{ $user->properties->where('verification_status', 'approved')->count() }}</div>
                        <div>Approved Properties</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div>
                        <div class="text-warning">
                            {{ $user->properties->where('verification_status', 'pending')->count() }}</div>
                        <div>Pending Verification</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div>
                        <div class="text-info">{{ $user->properties->where('status', 'active')->count() }}
                        </div>
                        <div>Active Properties</div>
                    </div>
                </div>
            @endif

            @if ($user->role === 'buyer')
                <div class="col-lg-3 col-md-6">
                    <div>
                        <div class="text-primary">{{ $user->orders->count() }}</div>
                        <div>Total Orders</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div>
                        <div class="text-success">{{ $user->orders->where('status', 'paid')->count() }}</div>
                        <div>Completed Orders</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div>
                        <div class="text-info">{{ $user->coupons->count() }}</div>
                        <div>Total Coupons</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div>
                        <div class="text-warning">{{ $user->coupons->where('is_winner', true)->count() }}
                        </div>
                        <div>Winning Coupons</div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Recent Activity -->
    @if ($user->role === 'seller' && $user->properties->count() > 0)
        <div class="row g-4">
            <div class="col-12">
                <h5>
                    <i class="fas fa-history text-primary"></i>
                    Recent Properties
                </h5>
            </div>

            @foreach ($user->properties->take(6) as $property)
                <div class="col-lg-6 col-md-12">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0 fw-semibold">{{ Str::limit($property->title, 40) }}</h6>
                            <div class="d-flex flex-column align-items-end gap-1">
                                <span
                                    class="badge bg-{{ $property->verification_status === 'approved' ? 'success' : ($property->verification_status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($property->verification_status) }}
                                </span>
                                <span class="badge bg-secondary">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </div>
                        </div>
                        <p class="text-muted mb-2 small">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $property->city }}, {{ $property->province }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $property->created_at->diffForHumans() }}
                            </small>
                            <strong class="text-primary">
                                Rp {{ number_format($property->coupon_price) }}
                            </strong>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($user->role === 'buyer' && $user->orders->count() > 0)
        <div class="row g-4 mb-5">
            <div class="col-12">
                <h5>
                    <i class="fas fa-shopping-bag text-primary"></i>
                    Recent Orders
                </h5>
            </div>

            @foreach ($user->orders->take(6) as $order)
                <div class="col-lg-6 col-md-12">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0 fw-semibold">{{ Str::limit($order->property->title, 40) }}</h6>
                            <span
                                class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <p class="text-muted mb-2 small">
                            {{ $order->quantity }} coupons × Rp {{ number_format($order->property->coupon_price) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $order->created_at->diffForHumans() }}
                            </small>
                            <strong class="text-success">
                                Rp {{ number_format($order->total_price) }}
                            </strong>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    @if (
        ($user->role === 'seller' && $user->properties->count() === 0) ||
            ($user->role === 'buyer' && $user->orders->count() === 0))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i
                            class="las la-{{ $user->role === 'seller' ? 'home' : 'shopping-cart' }} fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No {{ $user->role === 'seller' ? 'Properties' : 'Orders' }} Yet</h5>
                        <p class="text-muted">This user hasn't
                            {{ $user->role === 'seller' ? 'created any properties' : 'made any orders' }} yet.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
