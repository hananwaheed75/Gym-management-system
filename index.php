<?php
include 'config/auth.php';
check_auth(); // User login validation
include 'config/db.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// --- BACKEND LOGIC: Fetch Data dynamically from DB ---
// 1. Total Members
$total_query = "SELECT COUNT(*) as count FROM members";
$total_result = $conn->query($total_query)->fetch_assoc();

// 2. Active Members 
$active_query = "SELECT COUNT(*) as count FROM members WHERE status = 'Active'";
$active_result = $conn->query($active_query)->fetch_assoc();

// 3. Cancellations
$cancel_query = "SELECT COUNT(*) as count FROM members WHERE status = 'Cancelled'";
$cancel_result = $conn->query($cancel_query)->fetch_assoc();

// 4. Latest Member for the Quick View Popup
$latest_query = "SELECT * FROM members ORDER BY id DESC LIMIT 1";
$latest_user = $conn->query($latest_query)->fetch_assoc();
?>

<div id="main-app-content">
    <div class="row g-3">

        <div class="col-lg-8">
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title m-0 fw-bold"><i class="fa-solid fa-chart-area me-2 text-muted"></i>Member
                            Changes</h6>
                        <span class="badge bg-light text-dark border">April 2026</span>
                    </div>
                    <canvas id="memberChangesChart" height="120"></canvas>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title mb-3 fw-bold"><i class="fa-solid fa-chart-bar me-2 text-muted"></i>Member
                        Graph as at End of Month</h6>
                    <canvas id="endOfMonthChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="row g-3">
                <div class="col-12">
                    <div class="card-stat bg-white-stat shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="m-0 fw-bold">
                                    <?php echo $total_result['count']; ?>
                                </h2>
                                <small class="text-muted">Total Members Registered</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success">
                                    <?php echo $active_result['count']; ?> Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card-stat bg-green-stat shadow-sm">
                        <h2 class="m-0 fw-bold">
                            <?php echo $cancel_result['count']; ?>
                        </h2>
                        <div>Cancellations</div>
                        <small>Updated Live from DB</small>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card-stat bg-red-stat shadow-sm">
                        <h2 class="m-0 fw-bold">5</h2>
                        <div>New Members This Month</div>
                        <small>-37% * <i class="fa fa-arrow-down"></i></small>
                    </div>
                </div>

                <?php if($latest_user): ?>
                <div class="col-12 mt-4">
                    <div class="profile-card border">
                        <div class="profile-header position-relative">
                            <h5 class="m-0">
                                <?php echo $latest_user['first_name'] . ' ' . $latest_user['last_name']; ?>
                            </h5>
                            <span class="badge bg-warning text-dark mt-1">
                                <?php echo $latest_user['status']; ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-around text-center p-2 border-bottom bg-light">
                            <div style="cursor:pointer;"><i
                                    class="fa fa-right-to-bracket text-primary d-block mb-1"></i><small>Check In</small>
                            </div>
                            <div style="cursor:pointer;"><i
                                    class="fa fa-calendar-check text-primary d-block mb-1"></i><small>Booking</small>
                            </div>
                        </div>
                        <div class="p-3">
                            <table class="table table-sm table-borderless m-0 text-secondary"
                                style="font-size: 0.9rem;">
                                <tr>
                                    <td><strong>DOB:</strong></td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($latest_user['dob'])); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>
                                        <?php echo $latest_user['gender']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Notes:</strong></td>
                                    <td>
                                        <?php echo $latest_user['notes']; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script>
        function renderGymCharts() {
            const canvas1 = document.getElementById('memberChangesChart');
            const canvas2 = document.getElementById('endOfMonthChart');

            if (!canvas1 || !canvas2) return; // Guard clause agar element na miley

            // 1. Member Changes Chart
            const ctx1 = canvas1.getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['01 Apr', '05 Apr', '10 Apr', '15 Apr', '20 Apr', '25 Apr', '30 Apr'],
                    datasets: [{
                        label: 'New Prospects',
                        data: [20, 40, 55, 70, 90, 100, 105],
                        backgroundColor: 'rgba(97, 177, 90, 0.2)',
                        borderColor: '#61b15a',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: { responsive: true }
            });

            // 2. End of Month Chart
            const ctx2 = canvas2.getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Existing Members',
                        data: [800, 850, 900, 920, 950, 980, 1000, 1050, 1100, 1150, 1120, 1140],
                        backgroundColor: '#b39ddb'
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });
        }

        // Yeh trick check kray gi: Agar page fresh load hua hai ya SPA ajax se, dono cases mai chalay gi
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', renderGymCharts);
        } else {
            renderGymCharts();
        }
    </script>
</div>

<?php 
include 'includes/footer.php'; 
?>