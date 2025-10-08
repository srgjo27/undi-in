@extends('layouts.be')

@section('title', 'Property Management')

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
                                    <label for="status_property" class="form-label">Status</label>
                                    <select name="status" id="status_property" class="form-select">
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
                                    <label for="has_notes" class="form-label">Notes</label>
                                    <select name="has_notes" id="has_notes" class="form-select">
                                        <option value="">All Properties</option>
                                        <option value="yes" {{ request('has_notes') === 'yes' ? 'selected' : '' }}>With Notes</option>
                                        <option value="no" {{ request('has_notes') === 'no' ? 'selected' : '' }}>Without Notes</option>
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
                        <form id="bulkActionForm" action="{{ route('admin.properties.bulk-status') }}"
                            method="post">
                            @csrf
                            @method('patch')
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label" for="selectAll">
                                        Select All
                                    </label>
                                </div>
                                <select name="status" class="form-select" style="width: auto;">
                                    <option value="">Bulk Action</option>
                                    <option value="active">Set Active</option>
                                    <option value="cancelled">Cancel Selected</option>
                                    <option value="completed">Mark Completed</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary" id="bulkActionBtn">
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
                                        <th>Notes</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($properties as $property)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="property_ids[]" value="{{ $property->id }}"
                                                    class="form-check-input">
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
                                                            <i class="bx bx-image text-muted"></i>
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
                                                @if ($property->notes)
                                                    <small class="text-muted" title="{{ $property->notes }}">
                                                        <i class="bx bx-note me-1"></i>
                                                        {{ Str::limit($property->notes, 30) }}
                                                    </small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $property->created_at->format('M d, Y') }}</small>
                                                <br><small
                                                    class="text-muted">{{ $property->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
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
                                                        <!-- Status Actions -->
                                                        <li>
                                                            <a class="dropdown-item text-primary" href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#statusModal{{ $property->id }}">
                                                                <i class="fas fa-edit"></i>
                                                                Update Status & Notes
                                                            </a>
                                                        </li>
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

                                        <!-- Status Modal -->
                                        <div class="modal fade" id="statusModal{{ $property->id }}"
                                            tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{ route('admin.properties.update-status', $property) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Update Status: {{ $property->title }}
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="status{{ $property->id }}"
                                                                    class="form-label">Status</label>
                                                                <select name="status"
                                                                    id="status{{ $property->id }}"
                                                                    class="form-select" required>
                                                                    <option value="draft"
                                                                        {{ $property->status === 'draft' ? 'selected' : '' }}>
                                                                        Draft</option>
                                                                    <option value="active"
                                                                        {{ $property->status === 'active' ? 'selected' : '' }}>
                                                                        Active</option>
                                                                    <option value="pending_draw"
                                                                        {{ $property->status === 'pending_draw' ? 'selected' : '' }}>
                                                                        Pending Draw</option>
                                                                    <option value="completed"
                                                                        {{ $property->status === 'completed' ? 'selected' : '' }}>
                                                                        Completed</option>
                                                                    <option value="cancelled"
                                                                        {{ $property->status === 'cancelled' ? 'selected' : '' }}>
                                                                        Cancelled</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="notes{{ $property->id }}"
                                                                    class="form-label">Notes</label>
                                                                <textarea name="notes" id="notes{{ $property->id }}" class="form-control" rows="3"
                                                                    placeholder="Add notes...">{{ $property->notes }}</textarea>
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Inisialisasi Elemen ---
        const selectAllHeader = document.getElementById('selectAllHeader');
        const selectAllBulk = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('input[name="property_ids[]"]');
        const bulkActionBtn = document.getElementById('bulkActionBtn');
        const bulkActionForm = document.getElementById('bulkActionForm');
        const allSelectAllCheckboxes = [selectAllHeader, selectAllBulk];

        // --- Fungsi Logika (Tidak ada perubahan di sini) ---
        function toggleBulkActionButton() {
            const checkedItemsCount = document.querySelectorAll('input[name="property_ids[]"]:checked').length;
            bulkActionBtn.disabled = checkedItemsCount === 0;
        }

        function updateSelectAllState() {
            const totalItems = itemCheckboxes.length;
            const checkedItemsCount = document.querySelectorAll('input[name="property_ids[]"]:checked').length;
            const allChecked = totalItems > 0 && totalItems === checkedItemsCount;
            allSelectAllCheckboxes.forEach(cb => cb.checked = allChecked);
        }

        // --- Event Listeners (Tidak ada perubahan di sini) ---
        allSelectAllCheckboxes.forEach(selectAllCheckbox => {
            selectAllCheckbox.addEventListener('change', function () {
                itemCheckboxes.forEach(item => {
                    item.checked = this.checked;
                });
                allSelectAllCheckboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkActionButton();
            });
        });

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                updateSelectAllState();
                toggleBulkActionButton();
            });
        });
        
        // =======================================================
        // ==> PERUBAHAN UTAMA ADA DI SINI <==
        // =======================================================
        // Event listener BARU untuk form submission
        bulkActionForm.addEventListener('submit', function(e) {
            // 1. Hentikan pengiriman form otomatis
            e.preventDefault();

            // 2. Validasi dropdown aksi
            const actionSelect = this.querySelector('select[name="verification_status"]');
            if (actionSelect.value === "") {
                alert('Silakan pilih salah satu aksi masal (Bulk Action) terlebih dahulu.');
                return; // Hentikan proses jika tidak ada aksi dipilih
            }

            // 3. Cari semua checkbox item yang tercentang
            const checkedItems = document.querySelectorAll('input[name="property_ids[]"]:checked');

            // 4. Buat dan sisipkan <input type="hidden"> untuk setiap item yang tercentang
            checkedItems.forEach(item => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'property_ids[]';
                hiddenInput.value = item.value;
                this.appendChild(hiddenInput); // Sisipkan ke dalam form
            });
            
            // 5. Kirim form yang sudah dilengkapi dengan data ID
            this.submit();
        });


        // Panggil fungsi saat halaman pertama kali dimuat
        toggleBulkActionButton();
    });
</script>
@endpush