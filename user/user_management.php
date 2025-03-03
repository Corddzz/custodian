<?php
include '../config/config.php';
include '../config/conn.php';

// Fetch users from the database
$user_sql = "SELECT userId, username, role, position FROM users";
$user_result = $conn->query($user_sql);

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM users WHERE userId = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_id);
    $delete_stmt->execute();
    $delete_stmt->close();
    header("Location: user_management.php"); // Redirect to the same page
    exit();
}

// Handle user addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $position = $_POST['position'];

    $add_sql = "INSERT INTO users (username, role, position) VALUES (?, ?, ?)";
    $add_stmt = $conn->prepare($add_sql);
    $add_stmt->bind_param("sss", $username, $role, $position);
    $add_stmt->execute();
    $add_stmt->close();
    header("Location: user_management.php"); // Redirect to the same page
    exit();
}

// Handle user editing
$user_to_edit = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_sql = "SELECT userId, username, role, position FROM users WHERE userId = ?";
    $edit_stmt = $conn->prepare($edit_sql);
    $edit_stmt->bind_param("i", $edit_id);
    $edit_stmt->execute();
    $user_to_edit = $edit_stmt->get_result()->fetch_assoc();
    $edit_stmt->close();
}

// Handle user update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $edit_userId = $_POST['userId'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $position = $_POST['position'];

    $update_sql = "UPDATE users SET username = ?, role = ?, position = ? WHERE userId = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $username, $role, $position, $edit_userId);
    $update_stmt->execute();
    $update_stmt->close();
    header("Location: user_management.php"); // Redirect to the same page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5; /* Light background for the dashboard */
        }
        .main-content {
            margin-left: 220px; /* Align content with the sidebar width */
            padding: 20px; /* Add padding for better spacing */
        }
        .form-label {
            font-weight: bold; /* Bold labels */
        }
    </style>
</head>
<body class="d-flex">
    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container mt-4">
            <h1 class="text-center mb-4">User Management</h1>

            <!-- Add User Form -->
            <form method="POST" class="mb-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($user_to_edit) ? htmlspecialchars($user_to_edit['username']) : ''; ?>" required>
                    </div>
                
                    <div class="col-md-4 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="admin" <?php echo (isset($user_to_edit) && $user_to_edit['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="user" <?php echo (isset($user_to_edit) && $user_to_edit['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" name="position" id="position" class="form-control" value="<?php echo isset($user_to_edit) ? htmlspecialchars($user_to_edit['position']) : ''; ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 d-flex align-items-end">
                        <?php if (isset($user_to_edit)): ?>
                            <input type="hidden" name="userId" value="<?php echo htmlspecialchars($user_to_edit['userId']); ?>">
                            <button type="submit" name="update_user" class="btn btn-success w-100">Update User</button>
                        <?php else: ?>
                            <button type="submit" name="add_user" class="btn btn-primary w-100">Add User</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <!-- User Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Position</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $user_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['userId']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['role']); ?></td>
                                <td><?php echo htmlspecialchars($row['position']); ?></td>
                                <td>
                                    <a href="?edit_id=<?php echo $row['userId']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?delete_id=<?php echo $row['userId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
