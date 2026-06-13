<?php
include '../../config/auth.php';
check_auth();
include '../../config/db.php';
include '../../includes/header.php';

$query = "SELECT * FROM members ORDER BY id DESC";
$result = $conn->query($query);
?>

<div id="main-app-content">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4 fw-bold">Gym Members Directory</h5>
            <div id="listAlert" class="alert d-none shadow-sm fw-bold mb-4"></div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>DOB</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr id="row-<?php echo $row['id']; ?>">
                                <td>#<span><?php echo $row['id']; ?></span></td>
                                <td><strong class="m-name" data-fname="<?php echo $row['first_name']; ?>" data-lname="<?php echo $row['last_name']; ?>"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></strong></td>
                                <td><span class="m-dob"><?php echo $row['dob']; ?></span></td>
                                <td><span class="m-gender"><?php echo $row['gender']; ?></span></td>
                                <td>
                                    <span class="badge m-status-badge <?php echo $row['status'] === 'Active' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                    <span class="d-none m-status-text"><?php echo $row['status']; ?></span>
                                </td>
                                <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                                <td class="d-none"><span class="m-notes"><?php echo $row['notes']; ?></span></td>
                                
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1 edit-btn-spa" data-id="<?php echo $row['id']; ?>">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn-spa" data-id="<?php echo $row['id']; ?>">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted">No members found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title fw-bold" id="editModalLabel"><i class="fa fa-user-edit me-2"></i>Edit Member</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editMemberForm" onsubmit="return false;">
            <div class="modal-body">
                <div id="modalAlert" class="alert d-none fw-bold mb-3"></div>
                <input type="hidden" name="id" id="edit_id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" id="edit_fname" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" id="edit_lname" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" id="edit_dob" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" id="edit_gender" class="form-select" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Status</label>
                        <select name="status" id="edit_status" class="form-select" required>
                            <option value="Active">Active</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="On Hold">On Hold</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes / Remarks</label>
                        <textarea name="notes" id="edit_notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
          </form>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="updateBtn">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>