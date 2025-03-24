<?php
require 'header.php'; // Include the header file
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Hash the password

    // Check if the username already exists
    $checkUserStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkUserStmt->bind_param("s", $username);
    $checkUserStmt->execute();
    $checkUserStmt->store_result();

    if ($checkUserStmt->num_rows > 0) {
        echo "<div class='alert alert-danger' role='alert'>Username already exists. Please choose a different username.</div>";
        $checkUserStmt->close();
    }else{
        // Prepare an SQL statement to insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password); // Bind parameters

        // Execute the statement and check for success
        if ($stmt->execute()) {
            echo '<div class="alert alert-danger text-center" role="alert">Registration successful! <a href="login.php" class="alert-link">Login here</a></div>';
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
        }
            $stmt->close();

    }

    // Close the statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="post" class="mt-5 p-4 border rounded bg-light" onsubmit="return validateForm()">
                    <h2 class="text-center mb-4">Register</h2>
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" >
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" >
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" >
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Register</button>
                    <a href="login.php" class="d-block text-center mt-3 text-success">Already have an account? Login here</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function validateForm() {
            var fullname = document.getElementById('fullname').value;
            var username = document.getElementById('username').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;

            if (fullname == "" || username == "" || email == "" || password == "" || confirmPassword == "") {
                alert("All fields must be filled out");
                if (fullname == "") {
                    document.getElementById('fullname').focus();
                } else if (username == "") {
                    document.getElementById('username').focus();
                } else if (email == "") {
                    document.getElementById('email').focus();
                } else if (password == "") {
                    document.getElementById('password').focus();
                } else if (confirmPassword == "") {
                    document.getElementById('confirm_password').focus();
                }
                return false;
            }

            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address");
                return false;
            }

            if (password.length < 6) {
                alert("Password must be at least 6 characters long");
                return false;
            }

            var confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                alert("Passwords do not match");
                return false;
            }

            return true;
        }
    </script>

<?php
require 'footer.php'; // Include the footer file
?>
</body>
</html>
