<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.properties.update-status', $property) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="las la-edit me-2"></i>
                        Update Status & Notes
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status_property" class="form-label">Property Status</label>
                        <select name="status" id="status_property" class="form-select" required>
                            <option value="draft" {{ $property->status === 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>
                            <option value="active" {{ $property->status === 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="pending_draw" {{ $property->status === 'pending_draw' ? 'selected' : '' }}>
                                Pending Draw
                            </option>
                            <option value="completed" {{ $property->status === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="cancelled" {{ $property->status === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="4"
                            placeholder="Add notes about this property...">{{ $property->notes }}</textarea>
                        <div class="form-text">These notes are for administrative purposes.</div>
                    </div>

                    <div class="property-summary p-3 bg-light rounded">
                        <h6 class="mb-2">Property Summary:</h6>
                        <div><strong>{{ $property->title }}</strong></div>
                        <div class="text-muted">{{ $property->city }}, {{ $property->province }}</div>
                        <div class="text-muted">Seller: {{ $property->seller->name }}</div>
                        <div class="text-muted">Created: {{ $property->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-save me-1"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
