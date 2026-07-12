<?php
require_once 'config.php';

// Agar pehle se login hai to direct dashboard par bhejo
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error_message = "Invalid Credentials (Fields cannot be empty)";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Password verification
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error_message = "Invalid Credentials";
            }
        } else {
            $error_message = "Invalid Credentials";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .fade-in-card { animation: fadeIn 0.6s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow fade-in-card p-4">
                <h3 class="text-center mb-4"><i class="bi bi-shield-lock-fill text-primary"></i> Login</h3>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="you@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" id="passwordField" name="password" class="form-control">
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordBtn"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const passwordField = document.getElementById('passwordField');
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    togglePasswordBtn.addEventListener('click', function () {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            togglePasswordBtn.innerHTML = '<i class="bi bi-eye-slash"></i>';
        } else {
            passwordField.type = 'password';
            togglePasswordBtn.innerHTML = '<i class="bi bi-eye"></i>';
        }
    });
</script>
</body>
</html>