<?php
include '../config/config.php';
include '../config/conn.php';

// Fetch all borrow records
$borrow_records_sql = "SELECT
    borrow.borrow_id,
    borrow.user_id,
    users.username,
    borrow.item_id,
    borrow.category,
    borrow.quantity,
    borrow.borrow_date,
    CASE WHEN borrow.category = 'Equipment' THEN item_equipment.item_name WHEN borrow.category = 'Medical' THEN medical_item.item_name WHEN borrow.category = 'Academic' THEN academic_item.item_name WHEN borrow.category = 'Cleaning' THEN cleaning_item.item_name
END AS item_name
FROM
    borrow
LEFT JOIN users ON borrow.user_id = users.userId
LEFT JOIN item_equipment ON borrow.item_id = item_equipment.item_id AND borrow.category = 'Equipment'
LEFT JOIN medical_item ON borrow.item_id = medical_item.item_id AND borrow.category = 'Medical'
LEFT JOIN academic_item ON borrow.item_id = academic_item.item_id AND borrow.category = 'Academic'
LEFT JOIN cleaning_item ON borrow.item_id = cleaning_item.item_id AND borrow.category = 'Cleaning'
ORDER BY
    borrow.borrow_date
DESC";

$borrow_stmt = $conn->prepare($borrow_records_sql);
$borrow_stmt->execute();
$borrow_result = $borrow_stmt->get_result();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {




    foreach ($_POST['requests'] as $borrow_id => $action) {
        if ($action == 'approve' || $action == 'reject') {

            $update_sql = "UPDATE borrow SET status = ? WHERE borrow_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $action, $borrow_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
    }
    header("Location: approval.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Page</title>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="../asset/css/bootstrap.min.css">
    <!-- Booststrap JS -->
    <script defer src="../asset/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="d-flex vh-100">

        <?php include '../includes/sidebar.php'; ?>

        <div class="flex-grow-1 p-4">
            <div class="container py-4">


                <h1 class="mb-4">Borrow Requests</h1>
                <form method="POST">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>User</th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Borrow Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if ($borrow_result->num_rows > 0): ?>
                                <?php while ($row = $borrow_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['borrow_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($row['category'])); ?></td>
                                        <td><?php echo  $row['quantity']; ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($row['borrow_date'])); ?></td>
                                        <td><?php echo  $row['status']; ?></td>
                                        <td>
                                            <select name="requests[<?php echo $row['borrow_id']; ?>]" class="form-select">
                                                <option value="">Select Action</option>
                                                <option value="approve">Approve</option>
                                                <option value="reject">Reject</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No borrow requests found.</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$borrow_stmt->close();
$conn->close();
?>