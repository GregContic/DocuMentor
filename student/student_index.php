<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "documentor";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

$StudentName = "";
$StudentLRN = "";
$DocumentType = "";
$Details = "";
$Status = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $StudentName = $_POST["studentName"];
    $StudentLRN = $_POST["studentLRN"];
    $DocumentType = $_POST["docType"];
    $Details = $_POST["details"];
    $Status = "Pending for Approval";

    do {
        if (empty($StudentName) || empty($StudentLRN) || empty($DocumentType) || empty($Details)) {
            $errorMessage = "All the fields are required";
            break;
        }

        // add new client to database
        $sql = "INSERT INTO studentinquiries (StudentName, StudentLRN, DocumentType, Details, Status)" . 
                " VALUES ('$StudentName', '$StudentLRN', '$DocumentType', '$Details', '$Status')";

        
        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Error: ". $connection->error;
            break;
        }

        $StudentName = "";
        $StudentLRN = "";
        $DocumentType = "";
        $Details = "";
        $Status = "Pending";

        $successMessage = "Added successfully";

        header("Location: /DocuMentor/student_index.php");
        exit;

    } while (false);
}

// read all row from database table
$sql = "SELECT * FROM studentinquiries WHERE StudentLRN = '$StudentLRN'";
$result = $connection->query($sql);

if (!$result) {
    die("Invalid query: " . $connection->error);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - DocuMentor</title>
    <link rel="stylesheet" href="/documentor/css/admin_styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="navdiv">
            <div class="nav-header">
                <a href="#" id="navtitle">
                    <img src="/documentor/images/logo.png" alt="Documentor Logo" width="50" height="50">
                    DocuMentor
                </a>
            </div>
            <ul class="navlist">
                <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#inquiry"><i class="fas fa-file-alt"></i> New Inquiry</a></li>
                <li><a href="#history"><i class="fas fa-history"></i> History</a></li>
                <li><a href="student_profile.php"><i class="fas fa-user"></i> Profile</a></li>
            </ul>
        </div>
    </div>
    
    <div class="container">
        <div class="admin-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>Student Dashboard</h2>
                <hr>
                <nav>
                    <ul>
                        <li><a href="#inquiry"><i class="fas fa-file-alt"></i> New Inquiry</a></li>
                        <li><a href="#history"><i class="fas fa-history"></i> Inquiry History</a></li>
                        <li><a href="student_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a href="#settings"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><a href="#support"><i class="fas fa-question-circle"></i> Support</a></li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Inquiry Section -->
                <section id="inquiry" class="section">
                    <div class="section-header">
                        <div class="header-with-button">
                            <h2><i class="fas fa-file-alt"></i> Request a Document</h2>
                            <br>
                            <a href="/documentor/fill-up.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i> New Request
                            </a>
                        </div>
                    </div>
                </section>

                <!-- History Section -->
                <section id="history" class="section">
                    <h2>Inquiry History</h2>
                    <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Student L.R.N.</th>
                                <th>Document Type</th>
                                <th>Inquiry Date</th>
                                <th>Details</th>
                                <th>Status</th>
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
                                    <td><span class='status {$statusClass}'>{$row['Status']}</span></td>
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
    </div>
</body>
</html>
