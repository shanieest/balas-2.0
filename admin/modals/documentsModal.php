
    <!-- View Request Modal -->
    <div class="modal fade" id="viewRequestModal" tabindex="-1" aria-labelledby="viewRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewRequestModalLabel">Request Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Request Information</h6>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <p class="mb-0"><strong>Request ID:</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">REQ-101</p>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <p class="mb-0"><strong>Queue #:</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">BRGY-2023-001</p>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <p class="mb-0"><strong>Document Type:</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">Barangay Clearance</p>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <p class="mb-0"><strong>Date Requested:</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">June 10, 2023</p>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <p class="mb-0"><strong>Date Approved:</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">June 12, 2023</p>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <p class="mb-0"><strong>Status:</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-success">Approved</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Resident Information</h6>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <p class="mb-0"><strong>Name:</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">Pedro Reyes</p>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <p class="mb-0"><strong>Address:</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">789 Pine St, Zone 3</p>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <p class="mb-0"><strong>Contact:</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">09151234567</p>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    <p class="mb-0"><strong>Portal Status:</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-success">Registered</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Purpose</h6>
                        <p class="text-muted">For employment requirements at ABC Company.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Requirements Submitted</h6>
                        <ul class="text-muted">
                            <li>Valid ID (Driver's License)</li>
                            <li>Proof of Residency (Electric Bill)</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h6>Approval Details</h6>
                        <div class="row mb-2">
                            <div class="col-sm-3">
                                <p class="mb-0"><strong>Approved By:</strong></p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">Admin User</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-3">
                                <p class="mb-0"><strong>Notes:</strong></p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">All requirements are complete and verified.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-envelope me-1"></i> Send Email Notification
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Request Modal -->
    <div class="modal fade" id="approveRequestModal" tabindex="-1" aria-labelledby="approveRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveRequestModalLabel">Approve Document Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to approve this document request:</p>
                    <p><strong>Request ID:</strong> REQ-001</p>
                    <p><strong>Resident:</strong> Juan Dela Cruz</p>
                    <p><strong>Document Type:</strong> Barangay Clearance</p>
                    
                    <div class="mb-3">
                        <label for="approvalNotes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="approvalNotes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success">Approve Request</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Disapprove Request Modal -->
    <div class="modal fade" id="disapproveRequestModal" tabindex="-1" aria-labelledby="disapproveRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="disapproveRequestModalLabel">Disapprove Document Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to disapprove this document request:</p>
                    <p><strong>Request ID:</strong> REQ-001</p>
                    <p><strong>Resident:</strong> Juan Dela Cruz</p>
                    <p><strong>Document Type:</strong> Barangay Clearance</p>
                    
                    <div class="mb-3">
                        <label for="disapprovalReason" class="form-label">Reason for Disapproval</label>
                        <select class="form-select" id="disapprovalReason" required>
                            <option value="">Select a reason...</option>
                            <option value="Incomplete requirements">Incomplete requirements</option>
                            <option value="Invalid information">Invalid information</option>
                            <option value="Unverified resident">Unverified resident</option>
                            <option value="Other">Other (please specify)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="otherReasonContainer" style="display: none;">
                        <label for="otherReason" class="form-label">Specify Reason</label>
                        <textarea class="form-control" id="otherReason" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Disapprove Request</button>
                </div>
            </div>
        </div>
    </div>
