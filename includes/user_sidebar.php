<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

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
        padding: 8px;
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

<div class="d-flex vh-100">
    <div class="sidebar p-3 d-flex flex-column">
        <a href="../user/user_dashboard.php" class="nav-link  text-white mb-3 rounded <?php echo ($current_page === 'user_dashboard.php') ? 'active ' : ''; ?>">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="../request/request_user.php" class="nav-link text-white text-start mb-3 rounded <?php echo ($current_page === 'request_user.php') ? 'active ' : ''; ?>">
            <i class="fas fa-clipboard-list"></i> My Requests
        </a>
        <a href="../user/account_settings.php" class="nav-link text-white mb-3 rounded <?php echo ($current_page === 'account_settings.php') ? 'active ' : ''; ?>">
            <i class="fas fa-user-cog"></i> Profile Settings
        </a>

        <div class="mt-auto">
            <a href="logout.php" class="nav-link text-white rounded">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>