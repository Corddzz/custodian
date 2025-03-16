<?php
require_once('./config/config.php');
require_once('./config/conn.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Function to set session variables based on user role
function setSessionForRole($role, $user)
{
    $_SESSION['isLoggedIn'] = true;
    $_SESSION['userId'] = $user['userId']; // Set user ID in session
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role']; // Store user role in session
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE username = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Direct password comparison (No hashing)
                if ($password == $user['password']) {
                    setSessionForRole($user['role'], $user);

                    // Redirect based on user role
                    if ($user['role'] == 0) { // Assuming 0 is for admin
                        header('Location: ./stocks/academic.php'); // Redirect to admin dashboard
                    } else {
                        header('Location: /custodian/user/user_dashboard.php'); // Redirect to user dashboard
                    }
                    exit();
                }
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Database query failed: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="./asset/css/bootstrap.min.css">
    <!-- Bootstrap JS -->
    <script defer src="./asset/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icon -->
    <!-- <link rel="stylesheet" href="./asset/css/bootstrapicon.css"> -->

    <!-- Fontawesome icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="icon" href="./img/SRCS logo.jpg">
    <title>Login - San Ramon Catholic School</title>

    <style>
        body {
            background: url('img/bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            width: 100%;
        }

        .image-background {
            background: url('img/san_ramon.jpg') no-repeat center center;
            background-size: cover;
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

        .login-container {
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

        .password-toggle-icon {
            position: absolute;
            right: 0;
            top: 50%;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="image-background d-flex justify-content-center align-items-center">
        <div class="overlay"></div>
        <div class="login-container mx-auto">
            <h2 class="text-center text-black mb-4">Login</h2>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label text-black">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required placeholder="Enter Username">
                </div>

                <div class="mb-3 position-relative">
                    <label for="password" class="form-label text-black">Password</label>
                    <input type="password" id="password" name="password" class="form-control pe-5" required placeholder="Enter password">
                    <i onclick="toggleVisibility()" class="password-toggle-icon fa-regular fa-eye-slash p-2"></i>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>

                <div class="d-flex align-items-center justify-content-center my-2">
                    <hr class="flex-grow-1">
                    <span class="mx-3">or</span>
                    <hr class="flex-grow-1">
                </div>
            </form>

            <div class="text-center">
                <a href="./user/register.php" class="text-primary">Create new account</a>
            </div>
        </div>
    </div>

    <script defer>
        // Toggle password visibility
        function toggleVisibility() {
            const password = document.getElementById('password');
            const icon = document.querySelector(".password-toggle-icon");
            password.type = password.type === "password" ? "text" : "password";
            icon.classList.toggle("fa-eye-slash");
            icon.classList.toggle("fa-eye");
        }
    </script>
</body>

</html>