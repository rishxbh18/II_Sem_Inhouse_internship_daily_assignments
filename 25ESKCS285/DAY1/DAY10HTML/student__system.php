<?php include('process.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://jsdelivr.net" rel="stylesheet">
    <!-- FontAwesome Icons for Premium Grid Polish UI -->
    <link rel="stylesheet" href="https://cloudflare.com">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 30px 0;
        }
        .main-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .gradient-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }
        .badge-branch {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Main Dashboard Title Panel Header Layout -->
    <div class="card main-card mb-4">
        <div class="card-header gradient-header p-4 text-center">
            <h2 class="mb-0 fw-bold"><i class="fa-solid fa-graduation-cap me-2"></i>Student Management Dashboard</h2>
            <p class="mb-0 opacity-75 mt-1">Manage, View, Edit, and Search Student Records seamlessly.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT COLUMN: Dynamic CRUD Action Input Pre-filled Form -->
        <div class="col-lg-4">
            <div class="card main-card p-4 bg-white">
                <h4 class="fw-bold mb-3 text-primary">
                    <?php echo $editMode ? '<i class="fa-solid fa-user-pen me-2"></i>Edit Student' : '<i class="fa-solid fa-user-plus me-2"></i>Add New Student'; ?>
                </h4>
                <hr>
                <form action="student_system.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editStudent['id']); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Full Name</label>
                        <input type="text" class="form-control py-2" name="name" value="<?php echo htmlspecialchars($editStudent['name']); ?>" required placeholder="your name">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Branch</label>
                        <input type="text" class="form-control py-2" name="branch" value="<?php echo htmlspecialchars($editStudent['branch']); ?>" required placeholder="your branch">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Email Address</label>
                        <input type="email" class="form-control py-2" name="email" value="<?php echo htmlspecialchars($editStudent['email']); ?>" required placeholder="name@college.com">
                    </div>
                    
                    <button type="submit" name="save_student" class="btn <?php echo $editMode ? 'btn-warning text-dark' : 'btn-success'; ?> w-100 fw-bold py-2.5 mt-2 shadow-sm">
                        <?php echo $editMode ? 'Update Student Record' : 'Save Student Entry'; ?>
                    </button>
                    
                    <?php if ($editMode): ?>
                        <a href="student_system.php" class="btn btn-light border w-100 mt-2 fw-semibold">Cancel Selection</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- RIGHT COLUMN: Interactive View table Matrix, Search and Filter Controls -->
        <div class="col-lg-8">
            <div class="card main-card p-4 bg-white">
                
                <!-- Search Filter Controls Template Row Header -->
                <div class="row mb-4 align-items-center">
                    <div class="col-md-5">
                        <h4 class="fw-bold text-secondary mb-md-0"><i class="fa-solid fa-users-viewfinder me-2"></i>Student Ledger</h4>
                    </div>
                    <div class="col-md-7">
                        <form action="student_system.php" method="GET" class="d-flex">
                            <div class="input-group shadow-sm rounded">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" name="search" class="form-control border-start-0 py-2" placeholder="Filter by name, branch, email..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                                <?php if ($searchQuery !== ''): ?>
                                    <a href="student_system.php" class="btn btn-outline-secondary d-flex align-items-center bg-white text-muted border-start-0"><i class="fa-solid fa-xmark"></i></a>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-primary fw-semibold px-4">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Live Output View Grid Rendering Matrix -->
                <div class="table-responsive table-container border shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-secondary">
                                <th class="ps-3 py-3">ID</th>
                                <th class="py-3">Name</th>
                                <th class="py-3">Branch</th>
                                <th class="py-3">Email</th>
                                <th class="text-center py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($filteredStudents) > 0): ?>
                                <?php foreach ($filteredStudents as $student): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-muted">#<?php echo $student['id']; ?></td>
                                        <td class="fw-semibold text-dark"><?php echo $student['name']; ?></td>
                                        <td><span class="badge-branch"><?php echo $student['branch']; ?></span></td>
                                        <td class="text-secondary"><?php echo $student['email']; ?></td>
                                        <td class="text-center">
                                            <a href="?edit=<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-primary me-1 px-2.5 shadow-sm" title="Edit Record">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="?delete=<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-danger px-2.5 shadow-sm" onclick="return confirm('Are you absolutely sure?');" title="Delete Record">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="fa-regular fa-folder-open display-4 d-block mb-3 text-black-50"></i>
                                        <span class="fw-medium">No records found.</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>
