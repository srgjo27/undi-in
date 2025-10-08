@extends('layouts.be')

@section('title', 'User Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">User Management</h1>
                            <p class="text-muted">Kelola semua pengguna sistem</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-label waves-effect waves-light"><i
                                class="las la-plus label-icon align-middle fs-16 me-2"></i><a
                                href="{{ route('admin.users.create') }}" class="text-white">Add New User</a></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mt-2">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Total Users</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-success fs-14 mb-0">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                    +{{ $statistics['total']['growth_percentage'] }}%
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary">
                                    <span class="counter-value" data-target="{{ number_format($statistics['total']['count']) }}">
                                        {{ number_format($statistics['total']['count']) }}
                                    </span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-primary rounded fs-3">
                                    <i class="las la-user text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Active Users</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-success fs-14 mb-0">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                    {{ $statistics['active']['percentage'] }}% Active
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary">
                                    <span class="counter-value" data-target="{{ number_format($statistics['active']['count']) }}">
                                        {{ number_format($statistics['active']['count']) }}
                                    </span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success rounded fs-3">
                                    <i class="las la-user-check text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Sellers</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-warning fs-14 mb-0">
                                    <i class="ri-store-2-line fs-13 align-middle"></i>
                                    +{{ $statistics['sellers']['growth_percentage'] }}% MTD
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary">
                                    <span class="counter-value" data-target="{{ number_format($statistics['sellers']['count']) }}">
                                        {{ number_format($statistics['sellers']['count']) }}
                                    </span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning rounded fs-3">
                                    <i class="las la-store-alt text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Buyers</p>
                            </div>
                            <div class="flex-shrink-0">
                                <h5 class="text-info fs-14 mb-0">
                                    <i class="ri-shopping-bag-3-line fs-13 align-middle"></i>
                                    +{{ $statistics['buyers']['growth_percentage'] }}% MTD
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary">
                                    <span class="counter-value" data-target="{{ number_format($statistics['buyers']['count']) }}">
                                        {{ number_format($statistics['buyers']['count']) }}
                                    </span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded fs-3">
                                    <i class="las la-shopping-bag text-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.users.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select name="role" id="role" class="form-select">
                                        <option value="">All Roles</option>
                                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="seller" {{ request('role') === 'seller' ? 'selected' : '' }}>Seller
                                        </option>
                                        <option value="buyer" {{ request('role') === 'buyer' ? 'selected' : '' }}>Buyer
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" name="search" id="search" class="form-control"
                                        placeholder="Search by name or email..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i>Filter
                                        </button>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                            Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <div class="bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'seller' ? 'warning' : 'primary') }} text-white rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px; font-size: 14px; font-weight: bold;">
                                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">{{ $user->name }}</h6>
                                                        <small class="text-muted d-block">{{ $user->email }}</small>
                                                        @if ($user->phone_number)
                                                            <small
                                                                class="text-muted d-block">{{ $user->phone_number }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'seller' ? 'warning' : 'primary') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($user->email_verified_at)
                                                    <span class="badge bg-success">
                                                        <i class="las la-check-circle"></i>
                                                        Active
                                                    </span>
                                                    <div class="mt-1">
                                                        <small class="text-success">
                                                            <i class="las la-check-circle"></i>
                                                            Can login
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="las la-ban"></i>
                                                        Blocked
                                                    </span>
                                                    <div class="mt-1">
                                                        <small class="text-danger">
                                                            <i class="las la-exclamation-circle"></i>
                                                            Cannot login
                                                        </small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $user->created_at->format('M d, Y') }}</small>
                                                <br><small
                                                    class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#"
                                                        class="btn btn-soft-secondary btn-sm dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-fill"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.users.show', $user) }}">
                                                                <i class="las la-eye me-2 text-info"></i>View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.users.edit', $user) }}">
                                                                <i class="las la-edit me-2 text-primary"></i>Edit User
                                                            </a>
                                                        </li>
                                                        @if (Auth::id() !== $user->id)
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.users.toggle-status', $user) }}"
                                                                    method="post" 
                                                                    class="d-inline w-100"
                                                                    onsubmit="return confirm('{{ $user->email_verified_at ? 'This will BLOCK the user and force logout if currently logged in. Continue?' : 'This will ACTIVATE the user and allow them to login again. Continue?' }}')">
                                                                    @csrf
                                                                    @method('patch')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-{{ $user->email_verified_at ? 'warning' : 'success' }} w-100 text-start">
                                                                        <i class="las la-{{ $user->email_verified_at ? 'user-times' : 'user-check' }} me-2"></i>
                                                                        {{ $user->email_verified_at ? 'Block User' : 'Activate User' }}
                                                                        <small class="d-block text-muted">{{ $user->email_verified_at ? 'Disable account access' : 'Enable account access' }}</small>
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.users.destroy', $user) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger"
                                                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                                        <i class="las la-trash me-2"></i>Delete User
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-users fa-3x mb-3"></i>
                                                    <p>No users found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($users->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    <small>
                                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }}
                                        of {{ number_format($users->total()) }} users
                                    </small>
                                </div>
                                <nav aria-label="Users pagination">
                                    {{ $users->withQueryString()->links('pagination.custom') }}
                                </nav>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
