<!-- Add Household Modal -->
    <div class="modal fade" id="addHouseholdModal" tabindex="-1" aria-labelledby="addHouseholdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addHouseholdModalLabel">Add New Household</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addHouseholdForm">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Household Number</label>
                                <input type="text" class="form-control" id="householdNumber" value="BL-<?= date('Y') ?>-0001" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purok <span class="text-danger">*</span></label>
                                <select class="form-select" id="purok" required>
                                    <option value="" selected disabled>Select Purok</option>
                                    <option value="1">Purok 1</option>
                                    <option value="2">Purok 2</option>
                                    <option value="3">Purok 3</option>
                                    <option value="4">Purok 4</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" placeholder="Enter complete address" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">House Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="houseType" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="Single-detached">Single-detached</option>
                                    <option value="Duplex">Duplex</option>
                                    <option value="Apartment">Apartment</option>
                                    <option value="Shanty">Shanty</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ownership <span class="text-danger">*</span></label>
                                <select class="form-select" id="ownership" required>
                                    <option value="" selected disabled>Select Ownership</option>
                                    <option value="Owned">Owned</option>
                                    <option value="Rented">Rented</option>
                                    <option value="Leased">Leased</option>
                                    <option value="Informal Settler">Informal Settler</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Water Source <span class="text-danger">*</span></label>
                                <select class="form-select" id="waterSource" required>
                                    <option value="" selected disabled>Select Water Source</option>
                                    <option value="Level I">Level I (Well)</option>
                                    <option value="Level II">Level II (Deep Well)</option>
                                    <option value="Level III">Level III (Piped)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Electricity <span class="text-danger">*</span></label>
                                <select class="form-select" id="electricity" required>
                                    <option value="" selected disabled>Select Electricity</option>
                                    <option value="With Meter">With Meter</option>
                                    <option value="Without Meter">Without Meter</option>
                                    <option value="No Electricity">No Electricity</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Household</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Household Modal -->
    <div class="modal fade" id="viewHouseholdModal" tabindex="-1" aria-labelledby="viewHouseholdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewHouseholdModalLabel">Household Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light-custom py-2">
                                    <h6 class="mb-0">Household Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Household No:</strong> <span id="viewHouseholdNumber">BL-2023-0456</span></p>
                                            <p class="mb-1"><strong>Purok:</strong> <span id="viewPurok">2</span></p>
                                            <p class="mb-1"><strong>Address:</strong> <span id="viewAddress">123 Balas Street</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>House Type:</strong> <span id="viewHouseType">Single-detached</span></p>
                                            <p class="mb-1"><strong>Ownership:</strong> <span id="viewOwnership">Owned</span></p>
                                            <p class="mb-1"><strong>Year Built:</strong> <span id="viewYearBuilt">2010</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light-custom py-2">
                                    <h6 class="mb-0">Household Amenities</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Water Source:</strong> <span id="viewWaterSource">Level III (Piped)</span></p>
                                            <p class="mb-1"><strong>Electricity:</strong> <span id="viewElectricity">With Meter</span></p>
                                            <p class="mb-1"><strong>Internet:</strong> <span id="viewInternet">DSL</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Toilet Facility:</strong> <span id="viewToiletFacility">Water-sealed</span></p>
                                            <p class="mb-1"><strong>Waste Disposal:</strong> <span id="viewWasteDisposal">Garbage Collection</span></p>
                                            <p class="mb-1"><strong>Vehicle:</strong> <span id="viewVehicle">Motorcycle, Car</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Household Members</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-bordered" id="membersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Relationship</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Civil Status</th>
                                    <th>Occupation</th>
                                    <th>Education</th>
                                    <th>Voter</th>
                                </tr>
                            </thead>
                            <tbody id="membersTableBody">
                                <!-- Members will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light-custom py-2">
                                    <h6 class="mb-0">Livelihood</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush" id="livelihoodList">
                                        <!-- Livelihood items will be loaded here -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light-custom py-2">
                                    <h6 class="mb-0">Government Assistance</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush" id="assistanceList">
                                        <!-- Assistance items will be loaded here -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="editHouseholdBtn">Edit Household</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Household Modal -->
    <div class="modal fade" id="editHouseholdModal" tabindex="-1" aria-labelledby="editHouseholdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editHouseholdModalLabel">Edit Household</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editHouseholdForm">
                    <input type="hidden" id="editHouseholdId">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Household Number</label>
                                <input type="text" class="form-control" id="editHouseholdNumber" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purok <span class="text-danger">*</span></label>
                                <select class="form-select" id="editPurok" required>
                                    <option value="" selected disabled>Select Purok</option>
                                    <option value="1">Purok 1</option>
                                    <option value="2">Purok 2</option>
                                    <option value="3">Purok 3</option>
                                    <option value="4">Purok 4</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editAddress" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">House Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="editHouseType" required>
                                    <option value="" selected disabled>Select Type</option>
                                    <option value="Single-detached">Single-detached</option>
                                    <option value="Duplex">Duplex</option>
                                    <option value="Apartment">Apartment</option>
                                    <option value="Shanty">Shanty</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ownership <span class="text-danger">*</span></label>
                                <select class="form-select" id="editOwnership" required>
                                    <option value="" selected disabled>Select Ownership</option>
                                    <option value="Owned">Owned</option>
                                    <option value="Rented">Rented</option>
                                    <option value="Leased">Leased</option>
                                    <option value="Informal Settler">Informal Settler</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Water Source <span class="text-danger">*</span></label>
                                <select class="form-select" id="editWaterSource" required>
                                    <option value="" selected disabled>Select Water Source</option>
                                    <option value="Level I">Level I (Well)</option>
                                    <option value="Level II">Level II (Deep Well)</option>
                                    <option value="Level III">Level III (Piped)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Electricity <span class="text-danger">*</span></label>
                                <select class="form-select" id="editElectricity" required>
                                    <option value="" selected disabled>Select Electricity</option>
                                    <option value="With Meter">With Meter</option>
                                    <option value="Without Meter">Without Meter</option>
                                    <option value="No Electricity">No Electricity</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="editStatus" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Incomplete">Incomplete</option>
                                </select>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Household Members</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-sm table-bordered" id="editMembersTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Relationship</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Civil Status</th>
                                        <th>Occupation</th>
                                        <th>Education</th>
                                        <th>Voter</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="editMembersTableBody">
                                    <!-- Members will be loaded here for editing -->
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-primary mt-2" id="addMemberBtn">
                                <i class="fas fa-plus me-1"></i> Add Member
                            </button>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light-custom py-2">
                                        <h6 class="mb-0">Livelihood</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="livelihoodItems">
                                            <!-- Livelihood items will be loaded here -->
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary mt-2" id="addLivelihoodBtn">
                                            <i class="fas fa-plus me-1"></i> Add Livelihood
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light-custom py-2">
                                        <h6 class="mb-0">Government Assistance</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="assistanceItems">
                                            <!-- Assistance items will be loaded here -->
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary mt-2" id="addAssistanceBtn">
                                            <i class="fas fa-plus me-1"></i> Add Assistance
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-white">Update Household</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addMemberModalLabel">Add Household Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addMemberForm">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="memberFirstName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="memberLastName" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="memberMiddleName">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                <select class="form-select" id="memberRelationship" required>
                                    <option value="" selected disabled>Select Relationship</option>
                                    <option value="Head">Head</option>
                                    <option value="Spouse">Spouse</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Grandfather">Grandfather</option>
                                    <option value="Grandmother">Grandmother</option>
                                    <option value="Other Relative">Other Relative</option>
                                    <option value="Non-relative">Non-relative</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Age <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="memberAge" min="0" max="120" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="memberGender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="memberCivilStatus" required>
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="memberOccupation">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Education</label>
                                <select class="form-select" id="memberEducation">
                                    <option value="" selected disabled>Select Education</option>
                                    <option value="None">None</option>
                                    <option value="Elementary">Elementary</option>
                                    <option value="High School">High School</option>
                                    <option value="College">College</option>
                                    <option value="Vocational">Vocational</option>
                                    <option value="Post Graduate">Post Graduate</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Voter</label>
                                <select class="form-select" id="memberVoter">
                                    <option value="Yes">Yes</option>
                                    <option value="No" selected>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this household record?</p>
                    <p class="fw-bold" id="deleteItemName"></p>
                    <p class="text-danger">This action cannot be undone.</p>
                    <input type="hidden" id="deleteItemId">
                    <input type="hidden" id="deleteItemType">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
