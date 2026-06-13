<?php
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymMaster - Register Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="registration-page">
    <div class="register-card">
        <h3 class="text-center mb-4 fw-bold text-dark">Register New Admin/Staff</h3>
        <div id="regAlert" class="alert d-none"></div>

        <form id="registerForm">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Your Name">
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="username">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter strong password">
            </div>
            <button type="submit" class="btn btn-success w-100 py-2">Create Account</button>
            <div class="text-center mt-3">
                <a href="login.php" class="text-decoration-none">Back to Login</a>
            </div>
        </form>
    </div>
</div>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const alertBox = document.getElementById('regAlert');

            fetch('api/register_user_api.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alertBox.className = "alert alert-success";
                        alertBox.innerText = data.message;
                        alertBox.classList.remove('d-none');
                        document.getElementById('registerForm').reset();
                    } else {
                        alertBox.className = "alert alert-danger";
                        alertBox.innerText = data.message;
                        alertBox.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    alertBox.className = "alert alert-danger";
                    alertBox.innerText = "Something went wrong!";
                    alertBox.classList.remove('d-none');
                });
        });
    </script>
</body>

</html>