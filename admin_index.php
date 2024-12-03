<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Inquiries</title>
    <link rel="stylesheet" href="css/admin_styles.css">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



</head>
<body>
    <!-- Navigation Bar -->
     <div class="navbar">
        <div class="navdiv">
            <div class="nav-header">
                
                <a href="#" id="navtitle">
                    <img src="images/logo.png" alt="Documentor Logo" width="50" height="50" id="navlogo">Documentor
                </a>
            </div>
        <ul class="navlist">
            <li class="nav-list"><a href="#">Home</a></li>
            <li class="nav-list"><a href="">This</a></li>
            <li class="nav-list"><a href="">This as well</a></li>
            <li class="d_none"><a href="admin_profile.html"><i class="fa fa-user" aria-hidden="true"></i></a></li>  
            <div class="search-bar">
                <input type="text" placeholder="Search..." />
                <button type="submit">Go</button>
            </div>
        </ul>
        </div>
     </div>
    


     <!-- Sidebar -->
    <div class="admin-container">
        
        <aside class="sidebar">
            <h2>Admin Dashboard</h2>
            <hr>
            <nav>
                <ul>
                    <li><a href="#inquiries">Student Inquiries</a></li>
                    <li><a href="#manage">Manage Inquiries</a></li>
                    <li><a href="#settings">Settings</a></li>
                    <li><a href="#support">Support</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <h1>Student Inquiries</h1>
                <div class="admin-info">
                    <span>Welcome, Admin!</span>
                </div>

            </header>

            <!-- Inquiries Section -->
            <section id="inquiries" class="section">
                <h2>New Inquiries</h2>
                <div class="filter">
                    <label for="filter-document">Filter by Document Type:</label>
                    <select id="filter-document">
                        <option value="all">All</option>
                        <option value="transcript">Transcript</option>
                        <option value="certificate">Certificate of Enrollment</option>
                        <option value="id-card">ID Card</option>
                    </select>
                </div>
                
                <!-- Inquiry List -->
                 <div class="container my-5">
                    <h2>List of Inquiries</h2>
                    <a href="/documentor/php/create.php" class="btn btn-primary" role="button">New</a>
                    <br>
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
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $database = "documentor";
                        

                        // Create connection
                        $connection = new mysqli($servername, $username, $password, $database);

                        // Check connection
                        if ($connection->connect_error) {
                            die("Connection failed: ". $connection->connect_error);
                        }

                        // read all row from database table
                        $sql = "SELECT * FROM studentinquiries";
                        $result = $connection->query($sql);

                        if (!$result) {
                            die("Invalid query: " . $connection->error);
                        }

                        //read data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "
                            <tr>
                                <td>$row[StudentName]</td>
                                <td>$row[StudentLRN]</td>
                                <td>$row[DocumentType]</td>
                                <td>$row[InquiryDate]</td>
                                <td>$row[Details]</td>
                                <td>$row[Status]</td>
                                <td>
                                    <a class='btn btn-primary btn-sm' href='/Documentor/php/edit.php?id=$row[ID]'>Edit</a>
                                    <a class='btn btn-danger btn-sm' href='/Documentor/php/delete.php?id=$row[ID]'>Delete</a>
                                </td>
                            </tr>

                            ";
                        }
                        ?>
                        
                    </tbody>
                    </table>
                 </div>
                <table class="inquiry-table">
                    
                </table>
            </section>
        </main>
    </div>
    
</body>
</html>
