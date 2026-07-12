<?php
$message = "";
$alertClass = "";
$submittedCGPA = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submittedCGPA = filter_var($_POST['cgpa'], FILTER_VALIDATE_FLOAT);

    if ($submittedCGPA === false || $submittedCGPA < 0 || $submittedCGPA > 10) {
        $message = "⚠️ Please enter a valid CGPA between 0 and 10.";
        $alertClass = "alert-danger custom-alert";
    } else {
        if ($submittedCGPA >= 9.0) {
            $message = "👑 Excellent performance! Keep shining.";
            $alertClass = "alert-success custom-alert-success";
        } elseif ($submittedCGPA >= 8.0) {
            $message = "🚀 Very Good effort! You are doing great.";
            $alertClass = "alert-primary custom-alert-primary";
        } elseif ($submittedCGPA >= 7.0) {
            $message = "✨ Good job! Consistent focus will take you higher.";
            $alertClass = "alert-warning custom-alert-warning";
        } else {
            $message = "💪 Keep Improving! Hard work always pays off.";
            $alertClass = "alert-danger custom-alert-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Student Grade Checker</title>
    <!-- Bootstrap CSS -->
    <link href="https://jsdelivr.net" rel="stylesheet">

    <!-- Updated CSS for Absolute Centering -->
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: grid;
            place-items: center;
            /* Enforces dead center placement vertically & horizontally */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .wrapper-container {
            width: 100%;
            max-width: 450px;
            /* Restricts scaling stretch on desktop viewports */
        }

        .custom-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
            transition: transform 0.3s ease;
        }

        .custom-card:hover {
            transform: translateY(-5px);
        }

        .custom-header {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border-top-left-radius: 20px !important;
            border-top-right-radius: 20px !important;
            padding: 25px !important;
        }

        .btn-custom {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
            transform: translateY(-2px);
            opacity: 0.95;
        }

        .form-control-custom {
            border-radius: 12px;
            padding: 14px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        }

        .custom-alert,
        .custom-alert-success,
        .custom-alert-primary,
        .custom-alert-warning,
        .custom-alert-danger {
            border: none;
            border-radius: 14px;
            padding: 18px;
            font-size: 1.1rem;
            animation: slideUp 0.4s ease-out forwards;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
        }

        .custom-alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border-left: 5px solid #10b981;
        }

        .custom-alert-primary {
            background-color: #eff6ff;
            color: #1e40af;
            border-left: 5px solid #3b82f6;
        }

        .custom-alert-warning {
            background-color: #fffbeb;
            color: #92400e;
            border-left: 5px solid #f59e0b;
        }

        .custom-alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 5px solid #ef4444;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="wrapper-container">
        <div class="card custom-card">
            <div class="card-header custom-header text-white text-center">
                <h4 class="mb-0 fw-bold">🎓 Student Grade Checker</h4>
            </div>
            <div class="card-body p-4">

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="mb-4">
                        <label for="cgpa" class="form-label fw-semibold text-secondary">Enter your CGPA</label>
                        <input type="number"
                            step="0.1"
                            min="0"
                            max="10"
                            class="form-control form-control-custom text-center fs-5 fw-bold"
                            id="cgpa"
                            name="cgpa"
                            placeholder="e.g. 8.7"
                            value="<?php echo htmlspecialchars($submittedCGPA); ?>"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-custom w-100 fs-5">Check Performance</button>
                </form>

                <!-- Dynamic Alert Output -->
                <?php if (!empty($message)): ?>
                    <div class="alert <?php echo $alertClass; ?> mt-4 text-center fw-bold" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>

</html>