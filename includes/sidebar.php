<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            background-color: #424242;
            width: 230px;
            transition: background-color 0.3s;
        }

        .sidebar a {
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            font-weight: 500;
            letter-spacing: 0.5px;
            font-size: 16px;
            color: #f0f0f0;
        }

        .sidebar a:hover {
            background-color: hsl(0, 0.00%, 50%);
            color: #ffffff;
            transform: scale(1.05);
        }

        .sidebar a.active {
            background-color: #007bff;
            color: white;
            border: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .main-content {
                width: 100%;
            }

            .print-header {
                display: block !important;
            }
        }
    </style>

</head>

<body>
    <div class="d-flex vh-100 no-print">
        <div class="sidebar p-3 d-flex flex-column">
            <a href="../stocks/academic.php" class="btn text-start mb-3 w-100 
                <?php echo ($current_page == 'academic.php') ? 'active' : ''; ?>">
                <i class="fas fa-boxes me-2"></i>
                Stock Availability
            </a>
            <a href="../inventory/inventory.php" class="btn text-start mb-3 w-100 
                <?php echo ($current_page == 'inventory.php') ? 'active' : ''; ?>">
                <i class="fas fa-clipboard-list me-2"></i>
                Inventory
            </a>
            <a href="../release/release.php" class="btn text-start mb-3 w-100 
                <?php echo ($current_page == 'release.php') ? 'active' : ''; ?>">
                <i class="fas fa-dolly me-2"></i>
                Releasing
            </a>
            <a href="../report/report.php" class="btn text-start mb-3 w-100 
                <?php echo ($current_page == 'report.php') ? 'active' : ''; ?>">
                <i class="fas fa-pencil me-2"></i>
                Report
            </a>
            <a href="../request/request.php" class="btn text-start mb-3 w-100 
                <?php echo ($current_page == 'request.php') ? 'active' : ''; ?>">
                <i class="fas fa-file me-2"></i>
                Request
            </a>
            <a href="../approval/approval.php" class="btn text-start mb-3 w-100 
                <?php echo ($current_page == 'approval.php') ? 'active' : ''; ?>">
                <i class="fas fa-check-circle me-2"></i>
                Approval
            </a>
            <div class="mt-auto">
                <a href="../logout.php" class="btn text-start w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <main class="content">

    </main>

</body>

</html>