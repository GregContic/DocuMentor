<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - DocuMentor</title>
    <link rel="stylesheet" href="css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="navdiv">
            <div class="nav-header">
                <a href="#" id="navtitle">
                    <img src="images/logo.png" alt="Documentor Logo" width="50" height="50">
                    DocuMentor
                </a>
            </div>
            <ul class="navlist">
                <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#inquiry"><i class="fas fa-file-alt"></i> New Inquiry</a></li>
                <li><a href="#history"><i class="fas fa-history"></i> History</a></li>
                <li><a href="student_profile.html"><i class="fas fa-user"></i> Profile</a></li>
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
                        <li><a href="student_profile.html"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a href="#settings"><i class="fas fa-cog"></i> Settings</a></li>
                        <li><a href="#support"><i class="fas fa-question-circle"></i> Support</a></li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Inquiry Section -->
                <section id="inquiry" class="section">
                    <h2>Request a Document</h2>
                    <form class="inquiry-form" action="process_inquiry.php" method="POST">
                        <div class="row mb-3">
                            <label for="studentName">Student Name:</label>
                            <input type="text" class="form-control" id="studentName" name="studentName" value="<?php echo $StudentName; ?>">
                        </div>
                        <div class="row mb-3">
                            <label for="studentLRN">Student Learner's Reference Number:</label>
                            <input type="text" class="form-control" id="studentLRN" name="studentLRN" value="<?php echo $StudentLRN; ?>">
                        </div>
                        <div class="row mb-3">
                            <label for="docType">Document Type:</label>
                            <select class="form-control" id="docType" name="docType">
                                <option value="select-form">Select Form</option>
                                <option value="Transcript (Form 10)">Transcript (Form 10)</option>
                                <option value="Certificate of Enrolment">Certificate of Enrollment</option>
                                <option value="ID Card">ID Card</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <label for="details">Details:</label>
                            <input type="text" class="form-control" id="details" name="details" value="<?php echo $Details; ?>">
                        </div>
                        <div class="row mb-3">
                            <label for="status">Status:</label>
                            <input type="text" class="form-control" id="status" name="status" value="<?php echo $Status; ?>" readonly>
                        </div>
                        <?php
                        if (!empty($successMessage)){
                            echo "
                            <div class='row mb-3'>
                                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                 <strong> $successMessage </strong>
                                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                 </div>
                            </div>
                            ";
                        }
                        ?>
                        <button type="submit" class="btn btn-primary">Submit Inquiry</button>
                        <a class="btn btn-outline-primary" href="/DocuMentor/student_index.html">Cancel</a>
                    </form>
                </section>

                <!-- History Section -->
                <section id="history" class="section">
                    <h2>Inquiry History</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Document Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2024-11-14</td>
                                    <td>Transcript (Form 10)</td>
                                    <td><span class="status-pending">Pending</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="view-btn">View</button>
                                            <button class="delete-btn">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2024-10-29</td>
                                    <td>Certificate</td>
                                    <td><span class="status-approved">Approved</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="view-btn">View</button>
                                            <button class="delete-btn">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2024-09-15</td>
                                    <td>ID Card</td>
                                    <td><span class="status-rejected">Rejected</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="view-btn">View</button>
                                            <button class="delete-btn">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
