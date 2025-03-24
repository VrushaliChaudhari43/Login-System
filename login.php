<?php
require 'header.php'; // Include the header file
require 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    print_r($_POST);
    // Retrieve username and password from POST request
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    // Prepare SQL statement to select user details from the database
    $stmt = $conn->prepare("SELECT id, username, password,role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    // Bind the result variables to the prepared statement. 
    // This will store the result of the query into the variables $id, $username, and $hashed_password. 
    $stmt->bind_result($id, $username, $hashed_password,$role);
    $stmt->fetch();
    print_r($hashed_password);

    // Check if user exists and verify the password using password_verify function
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        // Set session variables and redirect to dashboard
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role; // Set the role
        header("Location: dashboard.php");
    } else {
        // Display error message for invalid login
        echo '<div class="alert alert-danger text-center" role="alert">Invalid username or password.</div>';
    }

    // Close the statement
    $stmt->close();
}
?>

 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post" class="mt-5 p-4 border rounded bg-light">
                <h2 class="text-center">Login</h2>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="text-center" style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                <p class="text-center mt-3">If you are a new user, then <a href="register.php">Register here</a>.</p>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script><?php
require 'footer.php'; // Include the footer file
?>
