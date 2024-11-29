<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/trial.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Inquiry List -->
    <div class="container my-5">
                    <h2>List of Inquiries</h2>
                    <a href="create.php" class="btn btn-primary" role="button">New</a>
                    <br>
                    <table class="table">
                    </table>
                 </div>
                <table class="inquiry-table">
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

                        if ($result) {
                            die("Error: ". $connection->error);
                        }

                        //read data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "
                            <tr>
                                <td>$row[ID]</td>
                                <td>$row[StudentName]</td>
                                <td>$row[StudentLRN]</td>
                                <td>$row[DocumentType]</td>
                                <td>$row[InquiryDate]</td>
                                <td>$row[Details]</td>
                                <td>$row[Status]</td>
                                <td>
                                    <a class='btn btn-primary btn-sm' href='/Documentor/php/edit.php?id=$row[id]'>Edit</a>
                                    <a class='btn btn-danger btn-sm' href='/Documentor/php/delete.phpid=$row[id]'>Delete</a>
                                </td>
                        </tr>
                            ";
                        }
                        ?>
                        
                    </tbody>
                </table>
            </section>
</body>
</html>