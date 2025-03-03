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
    <!-- TailwindCSS CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <!-- Fontawesome icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Login - San Ramon Catholic School</title>

</head>

<body class="bg-[url('img/bg.jpg')] bg-cover bg-center bg-no-repeat w-full h-screen">
    <div class="flex items-center justify-center bg-[url('img/san_ramon.jpg')] bg-cover bg-center bg-no-repeat w-full h-[45vh]">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <div class="relative top-52 bg-white/30 backdrop-blur-sm border border-white/10 p-8 rounded-lg shadow-xl max-w-sm w-full">

            <h2 class="text-3xl font-bold ext-center text-black mb-6 text-center">Login</h2>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-bold text-black">Username</label>
                    <input type="text" id="username" name="username" class="w-full p-3 mt-1 bg-transparent border border-black/50 rounded-md focus:outline-none" required placeholder="Enter Username">
                </div>

                <!-- Toggle Password -->
                <script>
                    function toggleVisibility() {
                        const password = document.getElementById('password');
                        const icon = document.querySelector(".password-toggle-icon");
                        password.type = password.type === "password" ? "text" : "password";
                        icon.classList.toggle("fa-eye-slash");
                        icon.classList.toggle("fa-eye");
                    }
                </script>

                <div class="relative">
                    <label for="password" class="block text-sm font-bold text-black">Password</label>
                    <input type="password" id="password" name="password" class="w-full p-3 mt-1 bg-transparent border border-black/50 rounded-md focus:outline-none pr-10" required placeholder="Enter password">

                    <i onclick="toggleVisibility()" class="password-toggle-icon fa-regular fa-eye-slash absolute right-3 top-[50px] transform -translate-y-1/2 text-black cursor-pointer"></i>
                </div>

                <button type="submit" class="w-full py-3 px-6 mb-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                    Login
                </button>

                <div class="flex items-center justify-center space-x-4 w-full">
                    <hr class="flex-grow border-t border-black">
                    <span class="px-4 text-black">or</span>
                    <hr class="flex-grow border-t  border-black">
                </div>

            </form>

            <div class="w-full text-center">
                <a href="./user/register.php" class="text-blue-800 hover:text-blue-500 duration-300 transition-all">Create new account</a>
            </div>
        </div>

    </div>

</body>

</html>