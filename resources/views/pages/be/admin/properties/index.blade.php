@extends('layouts.be')

@section('title', 'Property Management')

@push('styles')
    <style>
        .actions-dropdown {
            position: relative;
        }

        .actions-toggle {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .actions-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .actions-toggle:focus {
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
            color: white;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 8px;
            min-width: 180px;
        }

        .dropdown-item {
            border-radius: 6px;
            padding: 10px 15px;
            margin: 2px 0;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 10px;
            font-size: 16px;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transform: translateX(3px);
        }

        .dropdown-item.text-info:hover {
            background: linear-gradient(135deg, #d1ecf1 0%, #b8daff 100%);
            color: #0c5460;
        }

        .dropdown-item.text-success:hover {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .dropdown-item.text-warning:hover {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
        }

        .dropdown-item.text-danger:hover {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        .dropdown-divider {
            margin: 8px 0;
            border-top: 1px solid #e9ecef;
        }

        .verification-status {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-pending {
            background-color: #ffc107;
        }

        .status-approved {
            background-color: #28a745;
        }

        .status-rejected {
            background-color: #dc3545;
        }

        .property-image {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .property-image:hover {
            transform: scale(1.05);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">Property Management</h1>
                            <p class="text-muted">Kelola dan verifikasi semua properti</p>
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
                        <form method="GET" action="{{ route('admin.properties.index') }}">
                            <div class="row g-1">
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="pending_draw"
                                            {{ request('status') === 'pending_draw' ? 'selected' : '' }}>Pending Draw
                                        </option>
                                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="verification_status" class="form-label">Verification</label>
                                    <select name="verification_status" id="verification_status" class="form-select">
                                        <option value="">All Verification</option>
                                        <option value="pending"
                                            {{ request('verification_status') === 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="approved"
                                            {{ request('verification_status') === 'approved' ? 'selected' : '' }}>Approved
                                        </option>
                                        <option value="rejected"
                                            {{ request('verification_status') === 'rejected' ? 'selected' : '' }}>Rejected
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" name="search" id="search" class="form-control"
                                        placeholder="Search by title or seller name..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i>Filter
                                        </button>
                                        <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary">
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

        <!-- Bulk Actions -->
        <div class="row mb-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body py-2">
                        <form id="bulkActionForm" action="{{ route('admin.properties.bulk-verification') }}"
                            method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label" for="selectAll">
                                        Select All
                                    </label>
                                </div>
                                <select name="verification_status" class="form-select" style="width: auto;">
                                    <option value="">Bulk Action</option>
                                    <option value="approved">Approve Selected</option>
                                    <option value="rejected">Reject Selected</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary" id="bulkActionBtn" disabled>
                                    Apply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Properties Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" id="selectAllHeader" class="form-check-input">
                                        </th>
                                        <th>Property</th>
                                        <th>Seller</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Verification</th>
                                        <th>Created</th>
                                        <th width="120" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($properties as $property)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="property_ids[]" value="{{ $property->id }}"
                                                    class="form-check-input property-checkbox">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($property->primaryImage())
                                                        <img src="{{ asset('storage/' . $property->primaryImage()->image_path) }}"
                                                            alt="{{ $property->title }}" class="me-2"
                                                            style="width: 50px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light me-2 d-flex align-items-center justify-content-center"
                                                            style="width: 50px; height: 40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ Str::limit($property->title, 30) }}</h6>
                                                        <small class="text-muted">{{ $property->city }},
                                                            {{ $property->province }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $property->seller->name }}</h6>
                                                    <small class="text-muted">{{ $property->seller->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>Rp {{ number_format($property->coupon_price) }}</strong>
                                                @if ($property->max_coupons)
                                                    <br><small class="text-muted">Max:
                                                        {{ number_format($property->max_coupons) }} coupons</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $property->status_badge }}">
                                                    <i
                                                        class="bx bx-{{ $property->status === 'active' ? 'play-circle' : ($property->status === 'completed' ? 'check-circle' : ($property->status === 'cancelled' ? 'times-circle' : 'pause-circle')) }} me-1"></i>
                                                    {{ $property->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="verification-status">
                                                    <span
                                                        class="badge bg-{{ $property->verification_status === 'approved' ? 'success' : ($property->verification_status === 'rejected' ? 'danger' : 'warning') }}">
                                                        <i
                                                            class="bx bx-{{ $property->verification_status === 'approved' ? 'shield-alt-2' : ($property->verification_status === 'rejected' ? 'error' : 'stopwatch') }} me-1"></i>
                                                        {{ ucfirst($property->verification_status) }}
                                                    </span>
                                                </div>
                                                @if ($property->verification_notes)
                                                    <br><small class="text-muted"
                                                        title="{{ $property->verification_notes }}">
                                                        {{ Str::limit($property->verification_notes, 30) }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $property->created_at->format('M d, Y') }}</small>
                                                <br><small
                                                    class="text-muted">{{ $property->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="dropdown actions-dropdown">
                                                    <a href="#"
                                                        class="btn btn-soft-secondary btn-sm dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-more-fill"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <!-- View Action -->
                                                        <li>
                                                            <a class="dropdown-item text-info"
                                                                href="{{ route('admin.properties.show', $property) }}">
                                                                <i class="fas fa-eye"></i>
                                                                View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <!-- Verification Actions -->
                                                        @if ($property->verification_status === 'pending')
                                                            <li>
                                                                <a class="dropdown-item text-success" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#verificationModal{{ $property->id }}">
                                                                    <i class="fas fa-check-circle"></i>
                                                                    Quick Approve
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-warning" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#verificationModal{{ $property->id }}">
                                                                    <i class="fas fa-times-circle"></i>
                                                                    Reject Property
                                                                </a>
                                                            </li>
                                                        @elseif($property->verification_status === 'approved')
                                                            <li>
                                                                <a class="dropdown-item text-warning" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#verificationModal{{ $property->id }}">
                                                                    <i class="fas fa-edit"></i>
                                                                    Edit Verification
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a class="dropdown-item text-success" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#verificationModal{{ $property->id }}">
                                                                    <i class="fas fa-redo"></i>
                                                                    Reprocess
                                                                </a>
                                                            </li>
                                                        @endif
                                                         <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <!-- Delete Action -->
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.properties.destroy', $property) }}"
                                                                method="POST" class="d-inline w-100">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item text-danger w-100 text-start"
                                                                    onclick="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                    Delete Property
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Verification Modal -->
                                        <div class="modal fade" id="verificationModal{{ $property->id }}"
                                            tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{ route('admin.properties.update-verification', $property) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Verification: {{ $property->title }}
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="verification_status{{ $property->id }}"
                                                                    class="form-label">Status</label>
                                                                <select name="verification_status"
                                                                    id="verification_status{{ $property->id }}"
                                                                    class="form-select" required>
                                                                    <option value="pending"
                                                                        {{ $property->verification_status === 'pending' ? 'selected' : '' }}>
                                                                        Pending</option>
                                                                    <option value="approved"
                                                                        {{ $property->verification_status === 'approved' ? 'selected' : '' }}>
                                                                        Approved</option>
                                                                    <option value="rejected"
                                                                        {{ $property->verification_status === 'rejected' ? 'selected' : '' }}>
                                                                        Rejected</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="verification_notes{{ $property->id }}"
                                                                    class="form-label">Notes</label>
                                                                <textarea name="verification_notes" id="verification_notes{{ $property->id }}" class="form-control" rows="3"
                                                                    placeholder="Add verification notes...">{{ $property->verification_notes }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-home fa-3x mb-3"></i>
                                                    <p>No properties found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($properties->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $properties->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize dropdown animations
                document.querySelectorAll('.dropdown').forEach(dropdown => {
                    const toggle = dropdown.querySelector('.dropdown-toggle, .actions-toggle');
                    const menu = dropdown.querySelector('.dropdown-menu');

                    dropdown.addEventListener('show.bs.dropdown', function() {
                        toggle.style.transform = 'translateY(-1px)';
                        menu.style.opacity = '0';
                        menu.style.transform = 'translateY(-10px)';

                        setTimeout(() => {
                            menu.style.transition = 'all 0.3s ease';
                            menu.style.opacity = '1';
                            menu.style.transform = 'translateY(0)';
                        }, 10);
                    });

                    dropdown.addEventListener('hide.bs.dropdown', function() {
                        toggle.style.transform = 'translateY(0)';
                        menu.style.transition = 'all 0.2s ease';
                        menu.style.opacity = '0';
                        menu.style.transform = 'translateY(-10px)';
                    });
                });

                // Enhanced dropdown item interactions
                document.querySelectorAll('.dropdown-item').forEach(item => {
                    item.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateX(3px)';
                    });

                    item.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateX(0)';
                    });
                });
                const selectAll = document.getElementById('selectAll');
                const selectAllHeader = document.getElementById('selectAllHeader');
                const checkboxes = document.querySelectorAll('.property-checkbox');
                const bulkActionBtn = document.getElementById('bulkActionBtn');
                const bulkActionForm = document.getElementById('bulkActionForm');

                // Handle select all
                function handleSelectAll(checked) {
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = checked;
                    });
                    updateBulkActionButton();
                }

                selectAll.addEventListener('change', function() {
                    handleSelectAll(this.checked);
                    selectAllHeader.checked = this.checked;
                });

                selectAllHeader.addEventListener('change', function() {
                    handleSelectAll(this.checked);
                    selectAll.checked = this.checked;
                });

                // Handle individual checkboxes
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const checkedCount = document.querySelectorAll('.property-checkbox:checked')
                            .length;
                        selectAll.checked = checkedCount === checkboxes.length;
                        selectAllHeader.checked = selectAll.checked;
                        updateBulkActionButton();
                    });
                });

                // Update bulk action button
                function updateBulkActionButton() {
                    const checkedCount = document.querySelectorAll('.property-checkbox:checked').length;
                    bulkActionBtn.disabled = checkedCount === 0;
                }

                // Handle bulk action form submission
                bulkActionForm.addEventListener('submit', function(e) {
                    const checkedBoxes = document.querySelectorAll('.property-checkbox:checked');
                    const actionSelect = bulkActionForm.querySelector('select[name="verification_status"]');

                    if (checkedBoxes.length === 0) {
                        e.preventDefault();
                        alert('Please select at least one property.');
                        return;
                    }

                    if (!actionSelect.value) {
                        e.preventDefault();
                        alert('Please select an action.');
                        return;
                    }

                    // Add selected property IDs to form
                    checkedBoxes.forEach(checkbox => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'property_ids[]';
                        input.value = checkbox.value;
                        bulkActionForm.appendChild(input);
                    });
                });
            });
        </script>
    @endpush
@endsection
