<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);
    
    // Prepare SQL statement to select admin details from the database
    $stmt = $conn->prepare("SELECT id, username, password,role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    // Bind result variables to store query result
    $stmt->bind_result($id, $username_db, $hashed_password,$role);
    $stmt->fetch();
    
    // Check if admin exists and verify the password
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        // Set session variables and redirect to admin dashboard
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role; // Set the role
        header("Location: admin-dashboard.php");
        exit();
    } else {
        // Set error message for invalid login
        $message = "Invalid admin username or password.";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Admin Login</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger text-center"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Admin Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter admin username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Admin Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter admin password" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
     <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
