<?php
include '../config/config.php';
include '../config/conn.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $role = 1; // 1 for regular user, assuming 0 is for admin or unverified users

    if ($username && $password && $confirm_password) {
        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, password) FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $error = "Username already exists.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                $stmt->bind_param("ssi", $username, $password, $role);

                if ($stmt->execute()) {
                    $message = "Registration successful. You can now login.";
                } else {
                    $error = "Error: " . $stmt->error;
                }
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="../asset/css/bootstrap.min.css">
    <!-- Bootstrap JS -->
    <script defer src="./asset/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icon -->
    <!-- <link rel="stylesheet" href="./asset/css/bootstrapicon.css"> -->

    <!-- Fontawesome icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Registration - San Ramon Catholic School</title>

    <style>
        body {
            background-image: url('../img/bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            width: 100%;
        }

        .image-background {
            background-image: url('../img/san_ramon.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 45vh;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: black;
            opacity: 0.4;
        }

        .register-container {
            position: relative;
            top: 55%;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .password-toggle-icon,
        .confirm-password-toggle-icon {
            position: absolute;
            right: 0;
            top: 50%;
            cursor: pointer;
        }

        .btn-back {
            border: 1px solid #000;
            background-color: transparent;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .btn-signup {
            background-color: #007bff;
            color: #fff;
            transition: background-color 0.3s;
        }

        .btn-signup:hover {
            background-color: #0056b3;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="image-background d-flex justify-content-center align-items-center">
        <div class="overlay"></div>
        <div class="register-container mx-auto">
            <h2 class="text-3xl font-bold text-center text-black mb-6">Sign Up</h2>

            <!-- PHP Error and Message -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($message): ?>
                <div class="alert alert-success text-center" role="alert"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter Username">
                </div>

                <div class="position-relative mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control pe-5" placeholder="Enter password">

                    <i onclick="toggleVisibility()" class="password-toggle-icon fa-regular fa-eye-slash p-2"></i>
                </div>

                <div class="position-relative mb-5">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="form-control pe-5" placeholder="Confirm password">
                    <i onclick="toggleConfirmVisibility()" class="confirm-password-toggle-icon fa-regular fa-eye-slash p-2"></i>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="../login.php" class="btn btn-back d-flex align-items-center">
                        <i class="fa-solid fa-chevron-left me-2"></i>
                        <span>Back</span>
                    </a>

                    <button type="submit" class="btn btn-signup d-flex align-items-center">
                        <span>Sign Up</span>
                        <i class="fa-solid fa-arrow-right-from-bracket ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function toggleVisibility() {
            const password = document.getElementById("password");
            const icon = document.querySelector(".password-toggle-icon");

            const type = password.type === "password" ? "text" : "password";
            password.type = type;

            icon.classList.toggle("fa-eye-slash");
            icon.classList.toggle("fa-eye");

        }

        function toggleConfirmVisibility() {
            const confirmPassword = document.getElementById('confirm-password');
            const icon = document.querySelector(".confirm-password-toggle-icon");

            const type = confirmPassword.type === "password" ? "text" : "password";
            confirmPassword.type = type;

            icon.classList.toggle("fa-eye-slash");
            icon.classList.toggle("fa-eye");
        }
    </script>
</body>

</html>