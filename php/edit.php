<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "documentor";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

$id = "";
$StudentName = "";
$StudentLRN = "";
$DocumentType = "";
$Details = "";
$Status = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET['id'])) {
        header("Location: /Documentor/admin_index_new.php");
        exit;
    }

    $id = intval($_GET['id']); // Ensure $id is numeric

    $sql = "SELECT * FROM studentinquiries WHERE id = $id";
    $result = $connection->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $StudentName = $row["StudentName"];
        $StudentLRN = $row["StudentLRN"];
        $DocumentType = $row["DocumentType"];
        $Details = $row["Details"];
        $Status = $row["Status"];
    } else {
        header("Location: /Documentor/admin_index_new.php");
        exit;
    }
} else {
    $id = intval($_POST["id"]); // Retrieve $id from form
    $StudentName = $_POST["studentName"];
    $StudentLRN = $_POST["studentLRN"];
    $DocumentType = $_POST["docType"];
    $Details = $_POST["details"];
    $Status = $_POST["status"];

    do {
        if (empty($StudentName) || empty($StudentLRN) || empty($DocumentType) || empty($Details) || empty($Status)) {
            $errorMessage = "All fields are required.";
            break;
        }

        // Use prepared statements to prevent SQL injection
        $stmt = $connection->prepare("UPDATE studentinquiries SET StudentName=?, StudentLRN=?, DocumentType=?, Details=?, Status=? WHERE id=?");
        $stmt->bind_param("sssssi", $StudentName, $StudentLRN, $DocumentType, $Details, $Status, $id);

        if (!$stmt->execute()) {
            $errorMessage = "Error: " . $stmt->error;
            break;
        }

        $stmt->close();

        $successMessage = "Inquiry updated successfully.";
        header("Location: /Documentor/admin_index_new.php");
        exit;
    } while (false);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentor</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    max-width: 900px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.8s ease-out;
    backdrop-filter: blur(10px);
}

h2 {
    color: #2c3e50;
    font-size: 2.5em;
    margin-bottom: 30px;
    text-align: center;
    animation: slideIn 0.6s ease-out;
    background: linear-gradient(45deg, #2c3e50, #3498db);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Form Styles */
form {
    max-width: 700px;
    margin: 0 auto;
}

.form-control {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    outline: none;
}

label {
    font-weight: 600;
    color: #34495e;
    animation: slideIn 0.6s ease-out;
    display: inline-block;
    width: 100%;
    margin-bottom: 8px;
}

.row {
    margin-bottom: 25px;
    animation: slideIn 0.6s ease-out;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.col-sm-6 {
    width: 100%;
    max-width: 500px;
}

/* Button Styles */
.btn {
    padding: 12px 30px;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    overflow: hidden;
    min-width: 150px;
}

.btn::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: -100%;
    background: linear-gradient(90deg, rgba(255,255,255,0.1), rgba(255,255,255,0.4));
    transition: 0.5s;
}

.btn:hover::after {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(45deg, #3498db, #2980b9);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.btn-outline-primary {
    border: 2px solid #3498db;
    color: #3498db;
    background: transparent;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

/* Button Container */
.button-container {
    display: flex;
    justify-content: space-between;
    max-width: 500px;
    margin: 30px auto 0;
    gap: 20px;
}

/* Alert Styles */
.alert {
    border-radius: 15px;
    padding: 15px 20px;
    margin-bottom: 25px;
    animation: fadeIn 0.5s ease-out;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeeba;
    color: #856404;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 20px;
    }

    h2 {
        font-size: 2em;
    }

    .form-control {
        font-size: 0.95rem;
    }

    .btn {
        padding: 10px 20px;
        font-size: 0.9rem;
    }

    .button-container {
        flex-direction: column;
        gap: 15px;
    }

    .btn {
        width: 100%;
    }
}
    </style>
</head>
<body>
    <div class="container my-5">
        <h2>New Inquiry</h2>

        <?php 
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                 <strong> $errorMessage </strong>
                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
         }
         ?>

        <form method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="row mb-3">
                <label for="studentName" class="col-sm-2 col-form-label">Student Name:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="studentName" name="studentName" value="<?php echo $StudentName; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="studentLRN" class="col-sm-2 col-form-label">Student Learner's Reference Number:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="studentLRN" name="studentLRN" value="<?php echo $StudentLRN; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="docType" class="col-sm-2 col-form-label">Document Type:</label>
                <div class="col-sm-6">
                    <select class="form-control" id="docType" name="docType" value="<?php echo $DocumentType; ?>">
                        <option value="select-form">Select Form</option>
                        <option value="Transcript (Form 10)">Transcript (Form 10)</option>
                        <option value="Certificate of Enrolment">Certificate of Enrolment</option>
                        <option value="ID Card">ID Card</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="details" class="col-sm-2 col-form-label">Details:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="details" name="details" value="<?php echo $Details; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="status" class="col-sm-2 col-form-label">Status:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="status" name="status" value="<?php echo $Status; ?>">
                </div>
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

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/DocuMentor/admin_index_new.php">Cancel</a>
                </div>
            </div>
            </div>
        </form>
    </div>
</body>
</html>