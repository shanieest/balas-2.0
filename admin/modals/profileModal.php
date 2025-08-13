 <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you absolutely sure you want to delete your account?</p>
                    <p>This action cannot be undone. This will permanently delete your account and remove all your data from our servers.</p>
                    <div class="mb-3">
                        <label for="deleteConfirmation" class="form-label">Please type <strong>DELETE MY ACCOUNT</strong> to confirm</label>
                        <input type="text" class="form-control" id="deleteConfirmation" name="confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>Delete Account</button>
                </div>
            </div>
        </div>
    </div>