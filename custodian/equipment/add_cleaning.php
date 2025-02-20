<?php 
include '../config/config.php';
include '../config/conn.php'; 

if (!isset($_SESSION['isLoggedIn'])) {
    header('location: login.php');
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $item_name = $_POST['cleaning_item'];
    $item_code = 0; // You may want to generate this dynamically
    $item_qty = $_POST['quantity'];
    $item_unit = $_POST['unit'];
    $item_brand = $_POST['brand'];
    $date_purchase = $_POST['purchase_date'];
    $item_location = $_POST['location'];
    $item_life = $_POST['life'];
    $item_remarks = $_POST['remarks'];

    $sql = "INSERT INTO `cleaning_item`(`item_name`, `item_code`, `item_qty`, `item_unit`, `item_brand`, `date_purchase`, `item_location`, `item_life`, `item_remarks`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siissssss", $item_name, $item_code, $item_qty, $item_unit, $item_brand, $date_purchase, $item_location, $item_life, $item_remarks);
    
    if($stmt->execute()){
        $_SESSION['success_message'] = "Cleaning item added successfully!";
        header("Location: ../stocks/cleaning.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch categories from the database
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include '../includes/sidebar.php'; ?>
        <div class="flex-1 overflow-x-hidden overflow-y-auto">
            <div class="container mx-auto p-6">
                <h1 class="text-3xl font-bold mb-6 text-gray-800">Add New Cleaning Item</h1>
                
                <?php
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">';
                    echo '<p class="font-bold">Error</p>';
                    echo '<p>' . $_SESSION['error_message'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['error_message']);
                }
                ?>

                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <form action="" method="POST">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="cleaning_item">
                                Cleaning Item
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cleaning_item" name="cleaning_item" type="text" placeholder="Enter cleaning item name" required>
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
                        <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Add Cleaning Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
