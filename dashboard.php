<?php
// dashboard.php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all users
$users = $conn->query("SELECT * FROM users");

if (isset($_POST['create'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
    $conn->query($sql);
}

if (isset($_POST['update'])) {
    $user_id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET username='$username', email='$email' WHERE id=$user_id";
    $conn->query($sql);
}

if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE id=$user_id";
    $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

<h2>Welcome, <?php echo $_SESSION['username']; ?> | <a href="auth.php?logout=true">Logout</a></h2>

<h2>Create User</h2>
<form action="dashboard.php" method="post">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <button type="submit" name="create">Create</button>
</form>

<h2>All Users</h2>
<table border="1">
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <form action="dashboard.php" method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="username" value="<?php echo $row['username']; ?>">
                    <input type="email" name="email" value="<?php echo $row['email']; ?>">
                    <button type="submit" name="update">Update</button>
                </form>
                <a href="dashboard.php?delete=<?php echo $row['id']; ?>">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
