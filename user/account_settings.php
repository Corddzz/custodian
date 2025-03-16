<?php
include '../config/config.php';
include '../config/conn.php';

if (!isset($_SESSION['userId']) || $_SESSION['role'] != 1) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['userId'];
$username = $_SESSION['username'];
$message = '';
$error = '';

$stmt = $conn->prepare("SELECT * FROM users WHERE userId = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$positions = [];
$stmt = $conn->prepare("SHOW TABLES LIKE 'position'");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt = $conn->prepare("SELECT position_name FROM position");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $positions[] = $row['position_name'];
    }
} else {
    $error = "Position table does not exist.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = trim($_POST['username']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $new_position = trim($_POST['position_name']);

    if ($new_username != $username) {
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
                $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, position = ? WHERE userId = ?");
                $stmt->bind_param("ssii", $new_username, $new_password, $new_position, $user_id);
            }
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ?, position = ? WHERE userId = ?");
            $stmt->bind_param("ssi", $new_username, $new_position, $user_id);
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
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="../asset/css/bootstrap.min.css">
    <!-- Booststrap JS -->
    <script defer src="../asset/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Account Settings</title>
</head>

<body class="bg-light">
    <div class="vh-100 d-flex">

        <?php include '../includes/user_sidebar.php'; ?>

        <div class="flex-grow-1 p-5">
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

            <div class="d-flex justify-content-center">
                <div class="bg-white shadow rounded p-5 mb-4 w-50">
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="form-label" for="username">Username</label>
                            <input class="form-control" id="username" type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="position_name">Position</label>
                            <input class="form-control" id="position_name" type="text" name="position_name" value="<?php echo htmlspecialchars($user['position'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="new_password">New Password (leave blank to keep current password)</label>
                            <input class="form-control" id="new_password" type="password" name="new_password">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="confirm_password">Confirm Password</label>
                            <input class="form-control" id="confirm_password" type="password" name="confirm_password">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>