<?php
include '../config/config.php';
include '../config/conn.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header('location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $item_name = $_POST['item_name'];
    $item_code = 0; // You may want to generate this dynamically
    $item_qty = $_POST['quantity'];
    $item_unit = $_POST['unit'];
    $item_brand = $_POST['brand'];
    $date_purchase = $_POST['date'];
    $item_location = $_POST['location'];
    $item_life = $_POST['life'];
    $item_remarks = $_POST['remarks'];

    $sql = "INSERT INTO `cleaning_item`(`item_name`, `item_code`, `item_qty`, `item_unit`, `item_brand`, `date_purchase`, `item_location`, `item_life`, `item_remarks`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siissssss", $item_name, $item_code, $item_qty, $item_unit, $item_brand, $date_purchase, $item_location, $item_life, $item_remarks);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Cleaning item added successfully!";
        header("Location: ../stocks/cleaning.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }
    $stmt->close();
}

$categorySql = "SELECT * FROM tbl_cat ORDER BY cat_desc";
$categoryResult = $conn->query($categorySql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Cleaning Item</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>


<body class="bg-body-secondary">
    <div class="d-flex h-100">

        <?php include '../includes/sidebar.php'; ?>

        <div class="flex-grow-1">
            <div class="container mx-auto p-6">
                <h1 class="text-3xl fw-bold mb-4">Add New Cleaning Supplies </h1>

                <?php
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">';
                    echo '<p class="fw-bold">Error</p>';
                    echo '<p>' . $_SESSION['error_message'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['error_message']);
                }
                ?>

                <div class="bg-white shadow rounded px-8 pt-6 pb-8 mb-4">
                    <form action="" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="item_name" class="form-label fw-bold">Cleaning Item</label>
                                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter Cleaning Item" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="item_qty" class="form-label fw-bold">Quantity</label>
                                <input type="number" class="form-control" id="item_qty" name="item_qty" placeholder="Enter Quantity" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="unit" class="form-label fw-bold">Unit</label>
                                <select name="unit" class="form-select" id="unit" aria-label="Select Unit">
                                    <option selected disabled value="">Select Unit</option>
                                    <option value="Pcs">Pcs</option>
                                    <option value="Liters">Liters</option>
                                    <option value="Meters">Meters</option>
                                    <option value="Ream">Ream</option>
                                    <option value="Gallons">Gallons</option>
                                    <option value="Pounds">Pounds</option>
                                    <option value="Tons">Tons</option>
                                    <option value="Boxes">Boxes</option>
                                    <option value="Packs">Packs</option>
                                    <option value="Rolls">Rolls</option>
                                    <option value="Cases">Cases</option>
                                    <option value="Bundles">Bundles</option>
                                    <option value="Sets">Sets</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label fw-bold">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand" placeholder="Enter Brand" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label fw-bold">Purchase Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label fw-bold">Location</label>
                                <input type="text" class="form-control" id="location" name="location" placeholder="Enter Location" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="life" class="form-label fw-bold">Expiration</label>
                                <input type="text" class="form-control" id="life" name="life" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="remarks" class="form-label fw-bold">Remarks</label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter Remarks"></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="../request/request.php" class="btn btn-secondary fw-medium me-4">
                                <i class="fa-solid fa-chevron-left me-2"></i>
                                <span>Back</span>
                            </a>
                            <button type="submit" class="btn btn-primary fw-medium">
                                Add Cleaning Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- <body class="d-flex">

    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="container mx-auto p-6">
            <h1 class="text-3xl fw-bold mb-4">Add New Cleaning Item</h1>

            <?php
            if (isset($_SESSION['error_message'])) {
                echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">';
                echo '<p class="fw-bold">Error</p>';
                echo '<p>' . $_SESSION['error_message'] . '</p>';
                echo '</div>';
                unset($_SESSION['error_message']);
            }
            ?>

            <div class="bg-white shadow rounded px-8 pt-6 pb-8 mb-4">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cleaning_item" class="form-label fw-bold">Cleaning Item</label>
                            <input type="text" class="form-control" id="cleaning_item" name="cleaning_item" placeholder="Enter cleaning item name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label fw-bold">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label fw-bold">Unit</label>
                            <select class="form-select" id="unit" name="unit" required>
                                <option value="">Select unit</option>
                                <option value="pcs">Pcs</option>
                                <option value="liters">Liters</option>
                                <option value="meters">Meters</option>
                                <option value="ream">Ream</option>
                                <option value="gallons">Gallons</option>
                                <option value="pounds">Pounds</option>
                                <option value="tons">Tons</option>
                                <option value="boxes">Boxes</option>
                                <option value="packs">Packs</option>
                                <option value="rolls">Rolls</option>
                                <option value="cases">Cases</option>
                                <option value="bundles">Bundles</option>
                                <option value="sets">Sets</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="brand" class="form-label fw-bold">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand" placeholder="Enter brand" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="purchase_date" class="form-label fw-bold">Purchase Date</label>
                            <input type="date" class="form-control" id="purchase_date" name="purchase_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label fw-bold">Location</label>
                            <input type="text" class="form-control" id="location" name="location" placeholder="Enter location" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="life" class="form-label fw-bold">Expiration</label>
                            <input type="text" class="form-control" id="life" name="life" placeholder="Enter expiration" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="remarks" class="form-label fw-bold">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter remarks"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary fw-medium">
                            Add Cleaning Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body> -->

</html>