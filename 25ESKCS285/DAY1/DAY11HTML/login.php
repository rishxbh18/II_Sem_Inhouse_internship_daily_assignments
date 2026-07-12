<?php
$error_message = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Challenge: Display "Invalid Credentials" if submitted empty
    if (empty($email) || empty($password)) {
        $error_message = "Invalid Credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            
            <!-- Centered Login Card -->
            <div class="card shadow-sm p-4">
                <h3 class="text-center mb-4">Login</h3>

                <!-- Bootstrap Red Alert Challenge -->
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($error_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Login Form Structure -->
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" 
                               class="form-control" 
                               placeholder="you@example.com"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <!-- Input group with password toggle wrapper -->
                        <div class="input-group">
                            <input type="password" id="passwordField" name="password" class="form-control">
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordBtn">Show</button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bonus: Show/Hide Password Toggle JavaScript -->
<script>
    const passwordField = document.getElementById('passwordField');
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');

    togglePasswordBtn.addEventListener('click', function () {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            togglePasswordBtn.textContent = 'Hide';
        } else {
            passwordField.type = 'password';
            togglePasswordBtn.textContent = 'Show';
        }
    });
</script>
</body>
</html>