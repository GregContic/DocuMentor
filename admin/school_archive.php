<?php
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

// Get archive records
$sql = "SELECT * FROM document_archive ORDER BY CompletionDate DESC";
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
    <title>School Archive - DocuMentor</title>
    <link rel="stylesheet" href="/Documentor/css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ... existing styles ... */
        
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            animation: fadeIn 0.5s ease-out;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-dismissible {
            position: relative;
            padding-right: 4rem;
        }

        .btn-close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 1.25rem 1rem;
            background: transparent;
            border: 0;
            cursor: pointer;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-info {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }
        
        .btn-info:hover {
            background: #138496;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(19, 132, 150, 0.15);
            color: white;
            text-decoration: none;
        }
        
        .fa-history {
            font-size: 0.9em;
        }

        .document-info {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .document-info h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .no-history {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="navdiv">
            <div class="nav-header">
                <a href="admin_index_new.php" id="navtitle">
                    <img src="/documentor/images/logo.png" alt="Documentor Logo" width="50" height="50">
                    DocuMentor
                </a>
            </div>
            <ul class="navlist">
                <li><a href="admin_index_new.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="school_archive.php" class="active"><i class="fas fa-archive"></i> Archive</a></li>
                <li><a href="admin_profile.html"><i class="fas fa-user"></i> Profile</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="admin-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>Archive Dashboard</h2>
                <hr>
                <nav>
                    <ul>
                        <li><a href="#completed"><i class="fas fa-check-circle"></i> Completed Requests</a></li>
                        <li><a href="#statistics"><i class="fas fa-chart-bar"></i> Statistics</a></li>
                        <li><a href="#export"><i class="fas fa-file-export"></i> Export Data</a></li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <section id="completed" class="section">
                    <div class="section-header">
                        <h2><i class="fas fa-archive"></i> Document Archive</h2>
                    </div>

                    <div class="filter">
                        <label for="archive-filter">Filter by Document Type:</label>
                        <select id="archive-filter" onchange="filterArchive(this.value)">
                            <option value="all">All Documents</option>
                            <option value="Transcript of Records (TOR)">Transcript of Records (TOR)</option>
                            <option value="Certificate of Graduation">Certificate of Graduation</option>
                            <option value="Diploma">Diploma</option>
                            <option value="Certificate of Enrollment">Certificate of Enrollment</option>
                            <option value="Form 137">Form 137</option>
                            <option value="Certificate of Good Moral">Certificate of Good Moral</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Student L.R.N.</th>
                                    <th>Document Type</th>
                                    <th>Completion Date</th>
                                    <th>Details</th>
                                    <th>QR Code</th>
                                    <th>History</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($row = $result->fetch_assoc()) {
                                    echo "
                                    <tr>
                                        <td>{$row['StudentName']}</td>
                                        <td>{$row['StudentLRN']}</td>
                                        <td>{$row['DocumentType']}</td>
                                        <td>{$row['CompletionDate']}</td>
                                        <td>{$row['Details']}</td>
                                        <td>
                                            <img src='/{$row['QRCodePath']}' alt='QR Code' width='50' height='50'
                                                 onclick='window.open(this.src)' style='cursor: pointer;'>
                                        </td>
                                        <td>
                                            <a class='btn btn-info' href='school_archive.php?view_blockchain={$row['original_id']}'>
                                                <i class='fas fa-history'></i> View History
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

                <section id="blockchain" class="section">
                    <div class="section-header">
                        <h2><i class="fas fa-link"></i> Document Blockchain History</h2>
                    </div>
                    
                    <?php
                    if (isset($_GET['view_blockchain'])) {
                        require_once '../php/blockchain_utils.php';
                        
                        $documentId = intval($_GET['view_blockchain']);
                        
                        // Get document details
                        $stmt = $connection->prepare("SELECT StudentName, DocumentType FROM studentinquiries WHERE id = ?");
                        $stmt->bind_param("i", $documentId);
                        $stmt->execute();
                        $docResult = $stmt->get_result();
                        $docInfo = $docResult->fetch_assoc();
                        
                        if ($docInfo) {
                            echo "<div class='document-info'>
                                    <h3>Document Information</h3>
                                    <p><strong>Student:</strong> {$docInfo['StudentName']}</p>
                                    <p><strong>Document Type:</strong> {$docInfo['DocumentType']}</p>
                                  </div>";
                        }
                        
                        $stmt = $connection->prepare("SELECT * FROM document_blockchain 
                                                    WHERE document_id = ? 
                                                    ORDER BY timestamp DESC");
                        $stmt->bind_param("i", $documentId);
                        $stmt->execute();
                        $blockchain_result = $stmt->get_result();
                        
                        if ($blockchain_result->num_rows > 0) {
                            echo "<div class='blockchain-container'>";
                            while ($block = $blockchain_result->fetch_assoc()) {
                                echo "
                                <div class='blockchain-block'>
                                    <div class='block-header'>
                                        <span class='timestamp'>{$block['timestamp']}</span>
                                        <span class='action'>{$block['action']}</span>
                                    </div>
                                    <div class='block-content'>
                                        <p><strong>Status:</strong> {$block['status']}</p>
                                        <p class='hash'><strong>Hash:</strong> {$block['current_hash']}</p>
                                        <p class='hash'><strong>Previous Hash:</strong> {$block['previous_hash']}</p>
                                    </div>
                                </div>";
                            }
                            echo "</div>";
                        } else {
                            echo "<p class='no-history'>No blockchain history available for this document.</p>";
                        }
                    }
                    ?>
                </section>
            </main>
        </div>
    </div>

    <script>
        function filterArchive(value) {
            // Implementation of archive filtering
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const documentType = row.children[2].textContent;
                if (value === 'all' || documentType === value) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html> 