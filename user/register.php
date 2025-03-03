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
    <!-- TailwindCSS CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <!-- Fontawesome icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Registration - San Ramon Catholic School</title>
</head>

<body class="bg-[url('../img/bg.jpg')] bg-cover bg-center bg-no-repeat w-full h-screen">
    <div class="flex items-center justify-center bg-[url('../img/san_ramon.jpg')] bg-cover bg-center bg-no-repeat w-full h-[45vh]">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <div class="relative top-52 bg-white/30 backdrop-blur-sm border border-white/10 p-8 rounded-lg shadow-xl max-w-sm w-full">
            <h2 class="text-3xl font-bold ext-center text-black mb-6 text-center">Sign Up</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($message): ?>
                <div class="alert alert-success text-center" role="alert"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-bold text-black">Username</label>
                    <input type="text" id="username" name="username" class="w-full p-3 mt-1 bg-transparent border border-black/50 rounded-md focus:outline-none" placeholder="Enter Username">
                </div>

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
                    <input type="password" id="password" name="password" class="w-full p-3 mt-1 bg-transparent border border-black/50 rounded-md focus:outline-none pr-10" placeholder="Enter password">

                    <!-- Toggle Password -->
                    <i onclick="toggleVisibility()" class="password-toggle-icon fa-regular fa-eye-slash absolute right-3 top-[50px] transform -translate-y-1/2 text-black cursor-pointer"></i>
                </div>
                <div class="relative">
                    <label for="password" class="block text-sm font-bold text-black">Confirm Passoword</label>
                    <input type="password" class="w-full p-3 mt-1 bg-transparent border border-black/50 rounded-md focus:outline-none pr-10" placeholder="Confirm password">

                    <i onclick="toggleVisibility()" class="password-toggle-icon fa-regular fa-eye-slash absolute right-3 top-[50px] transform -translate-y-1/2 text-black cursor-pointer"></i>
                </div>

                <div class="flex">
                    <a href="../login.php" class="w-full py-3 px-6 mb-3 mr-5 border-black border hover:bg-white/10 text-black font-semibold rounded-md transition flex justify-center items-center">
                        <i class="fa-solid fa-chevron-left mr-4"></i>
                        <span>Back</span>
                    </a>

                    <button type="submit" class="w-full py-3 px-6 mb-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                        Sign Up
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>