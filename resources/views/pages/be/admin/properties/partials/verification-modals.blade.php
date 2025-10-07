<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.properties.update-verification', $property) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="verification_status" value="approved">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i>
                        Approve Property
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle me-2"></i>
                        You are about to approve this property. This will make it visible to buyers.
                    </div>
                    
                    <div class="mb-3">
                        <label for="approve_notes" class="form-label">Approval Notes (Optional)</label>
                        <textarea name="verification_notes" id="approve_notes" class="form-control" rows="3" 
                                  placeholder="Add any notes for the approval...">{{ $property->verification_notes }}</textarea>
                    </div>

                    <div class="property-summary p-3 bg-light rounded">
                        <h6 class="mb-2">Property Summary:</h6>
                        <div><strong>{{ $property->title }}</strong></div>
                        <div class="text-muted">{{ $property->city }}, {{ $property->province }}</div>
                        <div class="text-muted">Price: Rp {{ number_format($property->coupon_price) }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i>
                        Approve Property
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.properties.update-verification', $property) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="verification_status" value="rejected">
                
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle me-2"></i>
                        Reject Property
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        You are about to reject this property. Please provide a reason for the rejection.
                    </div>
                    
                    <div class="mb-3">
                        <label for="reject_notes" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="verification_notes" id="reject_notes" class="form-control" rows="4" 
                                  placeholder="Please explain why this property is being rejected..." required>{{ $property->verification_notes }}</textarea>
                        <div class="form-text">This message will be visible to the property seller.</div>
                    </div>

                    <div class="property-summary p-3 bg-light rounded">
                        <h6 class="mb-2">Property Summary:</h6>
                        <div><strong>{{ $property->title }}</strong></div>
                        <div class="text-muted">{{ $property->city }}, {{ $property->province }}</div>
                        <div class="text-muted">Price: Rp {{ number_format($property->coupon_price) }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-times-circle me-1"></i>
                        Reject Property
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.properties.update-verification', $property) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Verification Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="verification_status" class="form-label">Verification Status</label>
                        <select name="verification_status" id="verification_status" class="form-select" required>
                            <option value="pending" {{ $property->verification_status === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="approved" {{ $property->verification_status === 'approved' ? 'selected' : '' }}>
                                Approved
                            </option>
                            <option value="rejected" {{ $property->verification_status === 'rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="verification_notes" class="form-label">Verification Notes</label>
                        <textarea name="verification_notes" id="verification_notes" class="form-control" rows="4" 
                                  placeholder="Add verification notes...">{{ $property->verification_notes }}</textarea>
                        <div class="form-text">These notes will be visible to the property seller.</div>
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
                        <i class="fas fa-save me-1"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>