<?php
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymMaster - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="login-body">
    <div class="login-card">
        <h3 class="text-center mb-4 fw-bold text-dark"><i class="text-primary fas fa-dumbbell"></i> GYM System</h3>
        <div id="alertBox" class="alert d-none"></div>

        <form id="loginForm">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control form-inputs">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control form-inputs">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
        </form>
    </div>
</div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const alertBox = document.getElementById('alertBox');

            fetch('api/login_api.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alertBox.className = "alert alert-success";
                        alertBox.innerText = data.message;
                        alertBox.classList.remove('d-none');
                        setTimeout(() => window.location.href = 'index.php', 1000);
                    } else {
                        alertBox.className = "alert alert-danger";
                        alertBox.innerText = data.message;
                        alertBox.classList.remove('d-none');
                    }
                });
        });
    </script>
</body>

</html>