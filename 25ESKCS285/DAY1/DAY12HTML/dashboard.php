<?php
require_once 'config.php';

// Session Guard: Agar logged in nahi hai to direct logout/login par phenko
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// LOGOUT LOGIC
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// DELETE USER LOGIC
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id_to_delete);
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}

// INSERT / ADD USER LOGIC (With Image Upload)
$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_user'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $image_name = 'default.png';

    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image_name);
    }

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $image_name);
    if ($stmt->execute()) {
        $msg = "User Added Successfully!";
    } else {
        $msg = "Error: Email already exists!";
    }
}

// SEARCH LOGIC
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY id DESC");
    $search_term = "%" . $search . "%";
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $users_result = $stmt->get_result();
} else {
    $users_result = $conn->query("SELECT * FROM users ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .fade-up { animation: slideUp 0.5s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .user-img { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; }
        .preview-img { width: 100px; height: 100px; object-fit: cover; display: none; border-radius: 10px; }
    </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="#"><i class="bi bi-speedometer2"></i> Admin Dashboard</a>
    <span class="navbar-text text-white me-3">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
    <a href="dashboard.php?logout=1" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
</nav>

<div class="container my-5 fade-up">
    <?php if (!empty($msg)): ?>
        <div class="alert alert-info alert-dismissible fade show"><?php echo $msg; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Left Side: Add User Form (Create) -->
        <div class="col-md-4">
            <div class="card shadow p-4">
                <h4 class="mb-3"><i class="bi bi-person-plus-fill text-success"></i> Add New User</h4>
                <form method="POST" action="dashboard.php" enctype="multipart/form-data">
                    <input type="hidden" name="add_user" value="1">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <!-- Image Upload with Bonus Client-Side Preview -->
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                        <div class="mt-2">
                            <img id="imagePreview" class="preview-img img-thumbnail" alt="Preview">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle"></i> Save User</button>
                </form>
            </div>
        </div>

        <!-- Right Side: CRUD View + Search Widget -->
        <div class="col-md-8">
            <div class="card shadow p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="bi bi-people-fill text-primary"></i> Registered Users</h4>
                    
                    <!-- Search Feature -->
                    <form method="GET" action="dashboard.php" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                        <?php if(!empty($search)): ?>
                            <a href="dashboard.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Recent Registrations Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined Date</th>
                                class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($users_result->num_rows > 0): ?>
                                <?php while($row = $users_result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <img src="uploads/<?php echo $row['image']; ?>" class="user-img border" onerror="this.src='https://cdn-icons-png.flaticon.com/512/149/149071.png'">
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><small class="text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small></td>
                                        <td class="text-center">
                                            <a href="dashboard.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">
                                                <i class="bi bi-trash"></i>
                                            </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">No users found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Client side Image Preview Script -->
<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('imagePreview');
            preview.src = reader.result;
            preview.style.display = 'block';
        }
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    });
</script>
</body>
</html>