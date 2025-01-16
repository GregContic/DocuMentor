<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - DocuMentor</title>
    <link rel="stylesheet" href="/docuMentor/css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="navdiv">
            <div class="nav-header">
                <a href="student_index.php" id="navtitle">
                    <img src="images/logo.png" alt="Documentor Logo" width="50" height="50">
                    DocuMentor
                </a>
            </div>
            <ul class="navlist">
                <li><a href="student_index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="student_index.php#inquiry"><i class="fas fa-file-alt"></i> New Inquiry</a></li>
                <li><a href="student_index.php#history"><i class="fas fa-history"></i> History</a></li>
                <li><a href="student_profile.php" class="active"><i class="fas fa-user"></i> Profile</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="admin-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <h2>Student Profile</h2>
                <hr>
                <nav>
                    <ul>
                        <li><a href="#personal-info"><i class="fas fa-user-circle"></i> Personal Information</a></li>
                        <li><a href="#academic-info"><i class="fas fa-graduation-cap"></i> Academic Information</a></li>
                        <li><a href="#contact-info"><i class="fas fa-address-card"></i> Contact Information</a></li>
                        <li><a href="#settings"><i class="fas fa-cog"></i> Account Settings</a></li>
                    </ul>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <section id="personal-info" class="profile-section">
                    <div class="section-header">
                        <h2><i class="fas fa-user-circle"></i> Personal Information</h2>
                    </div>
                    <div class="profile-card">
                        <div class="profile-header">
                            <div class="profile-avatar">
                                <img src="images/default-avatar.png" alt="Profile Picture">
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-camera"></i> Change Photo
                                </button>
                            </div>
                            <div class="profile-info">
                                <h3>John Doe</h3>
                                <p class="student-id">Student ID: 2023-0001</p>
                                <p class="student-lrn">LRN: 123456789012</p>
                            </div>
                        </div>
                        <div class="profile-details">
                            <div class="info-group">
                                <label>Full Name</label>
                                <p>John Michael Doe</p>
                            </div>
                            <div class="info-group">
                                <label>Date of Birth</label>
                                <p>January 1, 2000</p>
                            </div>
                            <div class="info-group">
                                <label>Gender</label>
                                <p>Male</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="academic-info" class="profile-section">
                    <div class="section-header">
                        <h2><i class="fas fa-graduation-cap"></i> Academic Information</h2>
                    </div>
                    <div class="profile-card">
                        <div class="info-group">
                            <label>Grade Level</label>
                            <p>Grade 12</p>
                        </div>
                        <div class="info-group">
                            <label>Strand</label>
                            <p>STEM</p>
                        </div>
                        <div class="info-group">
                            <label>Section</label>
                            <p>A</p>
                        </div>
                        <div class="info-group">
                            <label>School Year</label>
                            <p>2023-2024</p>
                        </div>
                    </div>
                </section>

                <section id="contact-info" class="profile-section">
                    <div class="section-header">
                        <h2><i class="fas fa-address-card"></i> Contact Information</h2>
                    </div>
                    <div class="profile-card">
                        <div class="info-group">
                            <label>Email Address</label>
                            <p>john.doe@example.com</p>
                        </div>
                        <div class="info-group">
                            <label>Phone Number</label>
                            <p>+63 912 345 6789</p>
                        </div>
                        <div class="info-group">
                            <label>Address</label>
                            <p>123 Main Street, City, Province</p>
                        </div>
                    </div>
                </section>

                <section id="settings" class="profile-section">
                    <div class="section-header">
                        <h2><i class="fas fa-cog"></i> Account Settings</h2>
                    </div>
                    <div class="profile-card">
                        <div class="settings-actions">
                            <button class="btn btn-primary">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit Profile
                            </button>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
