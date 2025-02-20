<?php
include '../config/config.php';
include '../config/conn.php';


// Define categories
$categories = [
    'equipment' => 'item_equipment',
    'medical' => 'medical_item',
    'academic' => 'academic_item',
    'cleaning' => 'cleaning_item'
];

// Fetch all items for borrowing
$all_items_sql = "SELECT 'equipment' AS category, item_id, item_name FROM item_equipment
                  UNION ALL
                  SELECT 'medical' AS category, item_id, item_name FROM medical_item
                  UNION ALL
                  SELECT 'academic' AS category, item_id, item_name FROM academic_item
                  UNION ALL
                  SELECT 'cleaning' AS category, item_id, item_name FROM cleaning_item
                  ORDER BY category, item_name";
$all_items_result = $conn->query($all_items_sql);

$items_by_category = [];
while ($item = $all_items_result->fetch_assoc()) {
    $items_by_category[$item['category']][] = $item;
}

// Fetch all users
$users_sql = "SELECT userId, username FROM users WHERE role != 'admin'";
$users_result = $conn->query($users_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Item - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex vh-100">
        <?php include '../includes/sidebar.php'; ?>

        <div class="flex-grow-1 p-4">
            <div class="container py-4">
                <h3 class="mb-6">Borrow an item</h3>

                <?php if (isset($_SESSION['borrow_message'])): ?>
                    <div class="bg-<?php echo ($_SESSION['borrow_status'] == 'success' ? 'green' : 'red'); ?>-100 border-l-4 border-<?php echo ($_SESSION['borrow_status'] == 'success' ? 'green' : 'red'); ?>-500 text-<?php echo ($_SESSION['borrow_status'] == 'success' ? 'green' : 'red'); ?>-700 p-4 mb-6" role="alert">
                        <p class="font-bold">
                            <?php echo ($_SESSION['borrow_status'] == 'success' ? 'Success' : 'Error'); ?>
                        </p>
                        <p><?php echo $_SESSION['borrow_message']; ?></p>
                    </div>
                    <?php unset($_SESSION['borrow_message']);
                    unset($_SESSION['borrow_status']); ?>
                <?php endif; ?>

                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="p-6">
                        <h4 class="text-xl font-semibold text-gray-800 mb-6">Borrow Form</h4>
                        <form action="handle_borrow.php" method="POST">
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="user" class="block text-sm font-medium text-gray-700">User</label>
                                    <select id="user" name="user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                        <option value="">Select a user</option>

                                        <?php
                                        while ($user = $users_result->fetch_assoc()) {
                                            echo "<option value='" . $user['userId'] . "'>" . htmlspecialchars($user['username']) . "</option>";
                                        }
                                        ?>

                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                                        Category
                                    </label>
                                    <select name="category" id="category" onchange="updateItems()" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500 transition duration-300" required>
                                        <option value="">Select a category</option>
                                        <?php foreach ($categories as $category => $table): ?>
                                            <option value="<?php echo $category; ?>"><?php echo ucfirst($category); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="item">
                                        Item
                                    </label>
                                    <select name="item_id" id="item" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500 transition duration-300" required>
                                        <option value="">Select an item</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="quantity">
                                        Quantity
                                    </label>
                                    <input type="number" name="quantity" id="quantity" min="1" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500 transition duration-300" required>
                                </div>
                            </div>
                            <div class="mt-8 flex justify-end">
                                <a href="release.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 mr-4">
                                    <i class="fas fa-arrow-left mr-2"></i>Back
                                </a>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300">
                                    <i class="fas fa-plus mr-2"></i>Borrow
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateItems() {
            const category = document.getElementById('category').value;
            const itemSelect = document.getElementById('item');
            itemSelect.innerHTML = '<option value="">Select an item</option>';

            if (category) {
                const items = <?php echo json_encode($items_by_category); ?>[category];
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.item_id;
                    option.textContent = item.item_name;
                    itemSelect.appendChild(option);
                });
            }
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>