<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $name = htmlspecialchars($_POST['name']);
    $gender = isset($_POST['gender']) ? htmlspecialchars($_POST['gender']) : 'Not Selected';
    $course = htmlspecialchars($_POST['course']);
    $address = htmlspecialchars($_POST['address']);

    
    $photoName = "Placeholder Display Only";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photoName = htmlspecialchars($_FILES['photo']['name']);
    }
} else {
    
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmation</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://jsdelivr.net" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-success">
                <div class="card-header bg-success text-white text-center">
                    <h3>Registration Successful</h3>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Submitted Details</h5>
                    
                    <table class="table table-bordered">
                        <tr>
                            <th>Full Name</th>
                            <td><?php echo $name; ?></td>
                        </tr>
                        <tr>
                            <th>Profile Photo File</th>
                            <td><span class="badge bg-secondary"><?php echo $photoName; ?></span></td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td><?php echo $gender; ?></td>
                        </tr>
                        <tr>
                            <th>Course</th>
                            <td><?php echo $course ? $course : 'Not Selected'; ?></td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td><?php echo nl2br($address); ?></td>
                        </tr>
                    </table>

                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-outline-primary">Go Back to Form</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
