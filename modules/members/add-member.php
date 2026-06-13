<?php
include '../../config/auth.php';
check_auth();
include '../../includes/header.php';
?>

<div id="main-app-content">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Register New Gym Member</h5>
            
            <div id="memberAlert" class="alert d-none shadow-sm fw-bold mb-4" role="alert"></div>
            
            <form id="addMemberForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes / Remarks</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Any medical issues or references..."></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success px-4" id="saveBtn">
                            <i class="fa fa-save me-1"></i> Save Member
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>