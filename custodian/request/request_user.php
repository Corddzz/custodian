<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5; /* Light background for the dashboard */
        }
        .sidebar {
            min-height: 100vh; /* Full height for the sidebar */
            background-color: #343a40; /* Dark background for the sidebar */
            width: 220px; /* Set the width of the sidebar */
            position: fixed; /* Fix the sidebar to the left */
        }
        .sidebar a {
            color: white; /* White text for links */
            text-decoration: none; /* Remove underline from links */
            padding: 15px; /* Add padding for better spacing */
            display: block; /* Make links block elements */
        }
        .sidebar a:hover {
            background-color: #495057; /* Darker background on hover */
        }
        .main-content {
            margin-left: 220px; /* Align content with the sidebar width */
            padding: 20px; /* Add padding for better spacing */
            flex-grow: 1; /* Allow the main content to grow */
        }
        .dashboard-card {
            display: flex; /* Use flexbox for alignment */
            align-items: center; /* Center items vertically */
            text-decoration: none; /* Remove underline from links */
            color: inherit; /* Inherit text color */
            background: linear-gradient(135deg, #6a82fb, #fc5c7d); /* Gradient background */
            border-radius: 1rem; /* Rounded corners */
            padding: 20px; /* Padding for the card */
            transition: transform 0.3s; /* Smooth transition for hover effect */
        }
        .dashboard-card:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Shadow on hover */
        }
        .icon-container {
            background-color: white; /* White background for icons */
            border-radius: 50%; /* Circular icon container */
            padding: 1rem; /* Padding for the icon */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .text-dark {
            color: #333; /* Darker text color for better contrast */
        }
    </style>
</head>
<body class="d-flex">
    <!-- Sidebar -->
    <?php include '../includes/user_sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="bg-white rounded-xl shadow-2xl p-8">
            <h1 class="text-3xl font-bold mb-8 text-gray-800">User Requests</h1>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <a href="../borrow/borrow_user.php" class="dashboard-card">
                        <div class="icon-container text-yellow-500 me-3">
                            <i class="fas fa-hand-holding text-2xl"></i>
                        </div>
                        <span class="text-dark font-semibold">Borrowed Items</span>
                    </a>
                </div>
                <!-- You can add more cards here for additional requests -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
