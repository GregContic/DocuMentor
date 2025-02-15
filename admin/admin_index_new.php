<?php
require_once 'auth_check.php';
$servername = "localhost";
$username = "root";
$password = "";
$database = "documentor";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// read all row from database table
$sql = "SELECT * FROM studentinquiries WHERE is_archived = 0";
$result = $connection->query($sql);

if (!$result) {
    die("Invalid query: " . $connection->error);
}
// Get the filter value from URL parameter, default to 'all'
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Modify the SQL query based on the filter
$sql = "SELECT * FROM studentinquiries WHERE is_archived = 0";
if ($statusFilter !== 'all') {
    $sql .= " AND Status = '$statusFilter'";
}
$result = $connection->query($sql);

if (!$result) {
    die("Invalid query: " . $connection->error);
}
?>


<script>
function filterByStatus(status) {
    // Update the URL with the new filter value
    window.location.href = 'admin_index_new.php?status=' + status;
}
</script> 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DocuMentor</title>
    <link rel="stylesheet" href="/Documentor/css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 40px 20px;
            color: #2c3e50;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-out;
            backdrop-filter: blur(10px);
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 30px;
            margin-bottom: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navdiv {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .nav-header a {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .navlist {
            display: flex;
            align-items: center;
            gap: 30px;
            list-style: none;
        }

        .nav-list a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-list a:hover {
            color: #3498db;
        }

        /* Search Bar */
        .search-bar {
            display: flex;
            gap: 10px;
        }

        .search-bar input {
            padding: 8px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 20px;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-bar input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .search-bar button {
            padding: 8px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-bar button:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        /* Admin Container */
        .admin-container {
            display: flex;
            gap: 30px;
            margin-top: 30px;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .sidebar nav ul {
            list-style: none;
        }

        .sidebar nav ul li {
            margin: 15px 0;
        }

        .sidebar nav ul li a {
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar nav ul li a:hover {
            color: #3498db;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            color: #2c3e50;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        /* Button Styles */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #3498db;
            color: white;
            border: none;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Status Styles */
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3e0;
            color: #f57c00;
        }

        .status-approved {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-rejected {
            background: #fbe9e7;
            color: #d32f2f;
        }

        /* Filter Section */
        .filter {
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .filter label {
            font-weight: 600;
            color: #2c3e50;
        }

        .filter select {
            padding: 8px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s ease;
        }

        .filter select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .table {
                display: block;
                overflow-x: auto;
            }

            .btn {
                padding: 8px 16px;
                font-size: 0.9rem;
            }
        }

        .status-select {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 2px solid #e0e0e0;
            background-color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .status-select:hover {
            border-color: #3498db;
        }

        .status-select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="navdiv">
            <div class="nav-header">
                <a href="#" id="navtitle">
                    <img src="/documentor/images/logo.png" alt="Documentor Logo" width="50" height="50" id="navlogo">
                    Documentor
                </a>
            </div>
            <ul class="navlist">
                <li class="nav-list"><a href="#">Home</a></li>
                <li class="nav-list"><a href="">Dashboard</a></li>
                <li class="nav-list"><a href="">Reports</a></li>
                <li class="nav-list"><a href="/documentor/admin/admin_profile.html"><i class="fa fa-user" aria-hidden="true"></i></a></li>
                <div class="search-bar">
                    <input type="text" placeholder="Search..." />
                    <button type="submit">Search</button>
                </div>
            </ul>
        </div>
    </div>

    <!-- Admin Container -->
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Admin Dashboard</h2>
            <hr>
            <nav>
                <ul>
                    <li><a href="#inquiries"><i class="fas fa-clipboard-list"></i> Student Inquiries</a></li>
                    <li><a href="#manage"><i class="fas fa-tasks"></i> Manage Inquiries</a></li>
                    <li><a href="#settings"><i class="fas fa-cog"></i> Settings</a></li>
                    <li><a href="#support"><i class="fas fa-headset"></i> Support</a></li>
                    <li><a href="school_archive.php"><i class="fas fa-archive"></i> Document Archive</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1>Student Inquiries</h1>
                <div class="admin-info">
                    <span>Welcome, Admin!</span>
                </div>
            </header>

            <!-- Inquiries Section -->
            <section id="inquiries" class="section">
                
                <div class="filter">
                    <label for="filter-document">Filter by Document Type:</label>
                    <select id="filter-document">
                        <option value="All">All</option>
                        <option value="Transcript of Records (TOR)">Transcript of Records (TOR)</option>
                        <option value="Certificate of Graduation">Certificate of Graduation</option>
                        <option value="Diploma">Diploma</option>
                        <option value="Certificate of Enrollment">Certificate of Enrollment</option>
                        <option value="Affidavit of Lost Documents">Affidavit of Lost Documents</option>
                        <option value="Form 137">Form 137</option>
                        <option value="Certificate of Good Moral">Certificate of Good Moral</option>
                        <option value="Transcript (Form 10)">Transcript (Form 10)</option>
                        <option value="ID Card">ID Card</option>
                    </select>
                </div>
                <div class="filter">
                    <label for="status-filter">Filter by Status:</label>
                    <select id="status-filter" class="form-control" onchange="filterByStatus(this.value)">
                        <option value="all" <?php echo $statusFilter === 'all' ? 'selected' : ''; ?>>All Requests</option>
                        <option value="Pending" <?php echo $statusFilter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo $statusFilter === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo $statusFilter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="Rejected" <?php echo $statusFilter === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>

                <!-- Inquiry List -->
                <div class="container my-5">
                    <div class="table-header">
                        <h2>List of Inquiries</h2>
                        <a href="/documentor/php/create.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New
                        </a>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Student L.R.N.</th>
                                <th>Document Type</th>
                                <th>Inquiry Date</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while($row = $result->fetch_assoc()) {
                                $statusClass = "status-" . strtolower($row['Status']);
                                echo "
                                <tr>
                                    <td>{$row['StudentName']}</td>
                                    <td>{$row['StudentLRN']}</td>
                                    <td>{$row['DocumentType']}</td>
                                    <td>{$row['InquiryDate']}</td>
                                    <td>{$row['Details']}</td>
                                    <td>
                                        <select class='form-control status-select' onchange='updateStatus(this, {$row['ID']})'>
                                            <option value='Pending' " . ($row['Status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                            <option value='In Progress' " . ($row['Status'] == 'In Progress' ? 'selected' : '') . ">In Progress</option>
                                            <option value='Completed' " . ($row['Status'] == 'Completed' ? 'selected' : '') . ">Completed</option>
                                            <option value='Rejected' " . ($row['Status'] == 'Rejected' ? 'selected' : '') . ">Rejected</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a class='btn btn-primary' href='/Documentor/php/edit.php?id={$row['ID']}'>
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        <a class='btn btn-danger' href='/Documentor/php/delete.php?id={$row['ID']}' 
                                           onclick='return confirm(\"Are you sure you want to delete this record?\")'>
                                            <i class='fas fa-trash'></i>
                                        </a>
                                    </td>
                                </tr>
                                ";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Add fade-in animation to table rows
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.animation = `fadeIn 0.3s ease-out ${index * 0.1}s both`;
            });
        });
    </script>

    <script>
    function updateStatus(selectElement, id) {
        const newStatus = selectElement.value;
        
        // Show confirmation dialog if status is being set to Completed
        if (newStatus === 'Completed') {
            if (!confirm('This will move the inquiry to the archive. Continue?')) {
                // Reset the select to its previous value if user cancels
                selectElement.value = selectElement.getAttribute('data-previous-value');
                return;
            }
        }
        
        // Store the current value before making the request
        selectElement.setAttribute('data-previous-value', selectElement.value);
        
        // Send AJAX request to update status
        fetch('/Documentor/php/update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // If status was updated to Completed, refresh the page
                if (newStatus === 'Completed') {
                    window.location.href = '/Documentor/admin/school_archive.php?archived=true';
                } else {
                    // Show success message
                    alert('Status updated successfully');
                }
            } else {
                alert('Error updating status: ' + data.message);
                // Reset the select to its previous value
                selectElement.value = selectElement.getAttribute('data-previous-value');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status');
            // Reset the select to its previous value
            selectElement.value = selectElement.getAttribute('data-previous-value');
        });
    }
    </script>
</body>
</html>
