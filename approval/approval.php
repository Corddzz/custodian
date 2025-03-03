<?php
include '../config/config.php';
include '../config/conn.php';

// Fetch all borrow records
$borrow_records_sql = "SELECT b.borrow_id, b.userId, u.username, b.item_id, b.category, b.quantity, b.borrow_date, b.status,
                                CASE 
                                  WHEN b.category = 'equipment' THEN e.item_name
                                  WHEN b.category = 'medical' THEN m.item_name
                                  WHEN b.category = 'academic' THEN a.item_name
                                  WHEN b.category = 'cleaning' THEN c.item_name
                                END AS item_name
                         FROM borrow b
                         LEFT JOIN users u ON b.userId = u.userId
                         LEFT JOIN item_equipment e ON b.item_id = e.item_id AND b.category = 'equipment'
                         LEFT JOIN medical_item m ON b.item_id = m.item_id AND b.category = 'medical'
                         LEFT JOIN academic_item a ON b.item_id = a.item_id AND b.category = 'academic'
                         LEFT JOIN cleaning_item c ON b.item_id = c.item_id AND b.category = 'cleaning'
                         ORDER BY b.borrow_date DESC";

// $borrow_stmt = $conn->prepare($borrow_records_sql);
// $borrow_stmt->execute();
// $borrow_result = $borrow_stmt->get_result();


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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($row['borrow_date'])); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
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