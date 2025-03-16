<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="../asset/css/bootstrap.min.css">
    <!-- Booststrap JS -->
    <script defer src="../asset/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>User Request</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .main-content {
            margin-left: 240px;
            padding: 30px;
            flex-grow: 1;
        }

        .dashboard-card {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-decoration: none;
            color: #fff;
            background: linear-gradient(135deg, #6a82fb, #fc5c7d);
            border-radius: 1rem;
            padding: 25px;
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .icon-container {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            padding: 15px;
            margin-bottom: 10px;
        }

        .icon-container i {
            font-size: 2rem;
        }
    </style>
</head>

<body class="d-flex">
    <!-- Sidebar -->
    <?php include '../includes/user_sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="bg-white p-4 rounded shadow-sm">
                <h1 class="text-dark fw-bold mb-4 text-center">User Requests</h1>
                <div class="row justify-content-center g-4">
                    <div class="col-md-6 col-lg-4">
                        <a href="../borrow/borrow_user.php" class="dashboard-card">
                            <div class="icon-container">
                                <i class="fas fa-hand-holding text-white"></i>
                            </div>
                            <span class="fw-bold">Borrowed Items</span>
                        </a>
                    </div>
                    <!-- Add more cards here -->
                    <div class="col-md-6 col-lg-4">
                        <a href="../borrow/consumables.php" class="dashboard-card">
                            <div class="icon-container">
                                <i class="fas fa-boxes text-white"></i>
                            </div>
                            <span class="fw-bold">Manage Consumables</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>