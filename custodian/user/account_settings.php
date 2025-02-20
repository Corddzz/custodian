<?php
include '../config/config.php';
include '../config/conn.php';

// Check if user is logged in
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 1) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['userId'];
$username = $_SESSION['username'];
$message = '';
$error = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE userId = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch positions from the database
$positions = [];
$stmt = $conn->prepare("SELECT position_name FROM position");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $positions[] = $row['position_name'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = trim($_POST['username']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_username != $username) {
        // Check if the new username is available
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND userId != ?");
        $stmt->bind_param("si", $new_username, $user_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Username already taken.";
        }
    }

    if (!$error) {
        if ($new_password) {
            if ($new_password != $confirm_password) {
                $error = "New passwords do not match.";
            } else {
                // Update username and password
                $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE userId = ?");
                $stmt->bind_param("ssi", $new_username, $new_password, $user_id);
            }
        } else {
            // Update username only
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE userId = ?");
            $stmt->bind_param("si", $new_username, $user_id);
        }

        if (!$error) {
            if ($stmt->execute()) {
                $_SESSION['username'] = $new_username;
                $message = "Account updated successfully.";
            } else {
                $error = "Error updating account.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex">
        <!-- Sidebar -->
        <?php include '../includes/user_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4" style="margin-left: 250px;">
            <h1 class="text-3xl font-semibold mb-6">Account Settings</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Username
                    </label>
                    <input class="form-control" id="username" type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="position">
                        Position
                    </label>
                    <select class="form-control" id="position" name="position" required>
                        <option value="" disabled>Select Position</option>
                        <?php foreach ($positions as $position): ?>
                            <option value="<?php echo htmlspecialchars($position); ?>" <?php echo (isset($user['position']) && $user['position'] == $position) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($position); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">
                        New Password (leave blank to keep current password)
                    </label>
                    <input class="form-control" id="new_password" type="password" name="new_password">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                        Confirm Password
                    </label>
                    <input class="form-control" id="confirm_password" type="password" name="confirm_password">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
