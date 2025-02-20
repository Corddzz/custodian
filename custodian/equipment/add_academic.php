<?php
include '../config/config.php';
include '../config/conn.php';

if (!isset($_SESSION['isLoggedIn'])) {
    header('location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $item_name = $_POST['item_name'];
    $item_code = 0;
    $item_qty = $_POST['item_qty'];
    $item_unit = $_POST['item_unit'];
    $item_brand = $_POST['item_brand'];
    $date_purchase = $_POST['date_purchase'];
    $item_location = $_POST['item_location'];
    $item_life = $_POST['item_life'];
    $item_remarks = $_POST['item_remarks'];

    $sql = "INSERT INTO `academic_item`(`item_name`, `item_code`, `item_qty`, `item_unit`, `item_brand`, `date_purchase`, `item_location`, `item_life`, `item_remarks`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siissssss", $item_name, $item_code, $item_qty, $item_unit, $item_brand, $date_purchase, $item_location, $item_life, $item_remarks);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Academic item added successfully!";
        header("Location: ../stocks/academic.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// $categorySql = "SELECT * FROM tbl_cat ORDER BY cat_desc";
// $categoryResult = $conn->query($categorySql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Academic Item</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="bg-body-secondary">
    <div class="d-flex h-100">
        <?php include '../includes/sidebar.php'; ?>
        <div class="flex-grow-1">
            <div class="container mx-auto p-6">
                <h1 class="text-3xl fw-bold mb-4">Add New Academic Item</h1>

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
                                <label for="item_name" class="form-label fw-bold">Academic Item</label>
                                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter Academic Item" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="item_qty" class="form-label fw-bold">Quantity</label>
                                <input type="number" class="form-control" id="item_qty" name="item_qty" placeholder="Enter Quantity" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="item_unit" class="form-label fw-bold">Unit</label>
                                <select name="item_unit" class="form-select" id="item_unit" aria-label="Select Unit">
                                    <option selected disabled value="">Select Unit</option>
                                    <option value="pcs">Pcs</option>
                                    <option value="liters">Liters</option>
                                    <option value="meters">Meters</option>
                                    <option value="ream">Ream</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="item_brand" class="form-label fw-bold">Brand</label>
                                <input type="text" class="form-control" id="item_brand" name="item_brand" placeholder="Enter Brand" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_purchase" class="form-label fw-bold">Purchase Date</label>
                                <input type="date" class="form-control" id="date_purchase" name="date_purchase" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="item_location" class="form-label fw-bold">Location</label>
                                <input type="text" class="form-control" id="item_location" name="item_location" placeholder="Enter Location" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="item_life" class="form-label fw-bold">Expiration</label>
                                <input type="date" class="form-control" id="item_life" name="item_life" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="item_remarks" class="form-label fw-bold">Remarks</label>
                                <textarea class="form-control" id="item_remarks" name="item_remarks" rows="3" placeholder="Enter Remarks"></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary fw-medium">
                                Add Academic Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- <body class="bg-body-secondary">
    <div class="d-flex h-100">
        <?php include '../includes/sidebar.php'; ?>
        <div class="flex-grow-1">
            <div class="container mx-auto p-6">
                <h1 class="text-3xl fw-bold mb-4">Add New Academic Item</h1>

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
                            <div class="col mb-3">
                                <label for="item_name" class="form-label fw-bold">Academic Item</label>
                                <input type="text" class="form-control" id="item_name" placeholder="Enter Academic Item">
                            </div>
                            <div class="col mb-3">
                                <label for="item_qty" class="form-label fw-bold">Quantity</label>
                                <input type="number" class="form-control" id="item_qty" placeholder="Enter Quantity">
                            </div>
                            <div class="col mb-3">
                                <label for="item_unit" class="form-label fw-bold">Unit</label>
                                <input type="text" class="form-control" id="item_unit" placeholder="Enter Academic Item">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="item_brand" class="form-label fw-bold">Brand</label>
                                <input type="text" class="form-control" id="item_brand" placeholder="Enter Academic Item">
                            </div>
                            <div class="col mb-3">
                                <label for="date_purchase" class="form-label fw-bold">Purchase Date</label>
                                <input type="text" class="form-control" id="date_purchase" placeholder="Enter Academic Item">
                            </div>
                            <div class="col mb-3">
                                <label for="item_location" class="form-label fw-bold">Location</label>
                                <input type="text" class="form-control" id="item_location" placeholder="Enter Academic Item">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="item_life" class="form-label fw-bold">Expiration</label>
                                <input type="text" class="form-control" id="item_life" placeholder="Enter Academic Item">
                            </div>
                            <div class="col mb-3">
                                <label for="item_remarks" class="form-label fw-bold">Remarks</label>
                                <input type="text" class="form-control" id="item_remarks" placeholder="Enter Academic Item">
                            </div>
                        </div>



                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="academic_item">
                                Academic Item
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="academic_item" name="academic_item" type="text" placeholder="Enter academic item name" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="quantity">
                                Quantity
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="quantity" name="quantity" type="number" placeholder="Enter quantity" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="unit">
                                Unit
                            </label>
                            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="unit" name="unit" required>
                                <option value="">Select unit</option>
                                <option value="Pcs">Pcs</option>
                                <option value="Liters">Liters</option>
                                <option value="Meters">Meters</option>
                                <option value="Ream">Ream</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="brand">
                                Brand
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="brand" name="brand" type="text" placeholder="Enter brand" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="purchase_date">
                                Purchase Date
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="purchase_date" name="purchase_date" type="date" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="location">
                                Location
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="location" name="location" type="text" placeholder="Enter location" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="life">
                                Expiration
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="life" name="life" type="text" placeholder="Enter expiration" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="remarks">
                                Remarks
                            </label>
                            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="remarks" name="remarks" placeholder="Enter remarks" required></textarea>
                        </div>
                        <div class="d-flex">
                            <button type="button" class="btn btn-primary fw-medium" type="submit">
                                Add Academic Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body> -->

</html>