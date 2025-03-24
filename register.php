<?php
require 'header.php'; // Include the header file
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    // Retrieve and sanitize form inputs
    $fullname = sanitize($_POST['fullname']);
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $role = sanitize($_POST['role']);
    $password = password_hash(sanitize($_POST['password']), PASSWORD_BCRYPT); // Hash the password

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
        $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password,role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $username, $email, $password, $role); // Bind parameters

        // Execute the statement and check for success
        if ($stmt->execute()) {
            echo '<div class="alert alert-success text-center" role="alert">Registration successful! <a href="login.php" class="alert-link">Login here</a></div>';
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
                <form id="registerForm" method="post" class="mt-5 p-4 border rounded bg-light" >
                    <h2 class="text-center mb-4">Register</h2>
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Full Name">
                        <small class="text-danger validatefullname" style="display:none;">Please Enter Fullname</small>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control">
                            <option value="admin">Admin</option>
                            <option value="employee">Employee</option>
                        </select>
                        <small class="text-danger validaterole" style="display:none;">Please Select a Role</small>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" >
                        <small class="text-danger validateusername" style="display:none;">Please Enter Username</small>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" >
                         <small class="text-danger validateemail" style="display:none;">Please Enter Email</small>

                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" >
                        <small class="text-danger validatepassword" style="display:none;">Please Enter Password</small>

                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" >
                        <small class="text-danger validateconfirmpassword" style="display:none;">Please Enter Confirm Password</small>

                    </div>
                    <div class="text-center" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
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

            if (fullname == "" || username == "" || email == "" || password == "" || confirmPassword == "" || document.getElementById('role').value == "") {
                if (fullname == "") {
                    document.querySelector('.validatefullname').style.display = 'block';
                } else {
                    document.querySelector('.validatefullname').style.display = 'none';
                }
                if (username == "") {
                    document.querySelector('.validateusername').style.display = 'block';
                } else {
                    document.querySelector('.validateusername').style.display = 'none';
                }
                if (email == "") {
                    document.querySelector('.validateemail').style.display = 'block';
                } else {
                    document.querySelector('.validateemail').style.display = 'none';
                }
                if (password == "") {
                    document.querySelector('.validatepassword').style.display = 'block';
                } else {
                    document.querySelector('.validatepassword').style.display = 'none';
                }
                if (confirmPassword == "") {
                    document.querySelector('.validateconfirmpassword').style.display = 'block';
                } else {
                    document.querySelector('.validateconfirmpassword').style.display = 'none';
                }
                if (document.getElementById('role').value == "") {
                    document.querySelector('.validaterole').style.display = 'block';
                } else {
                    document.querySelector('.validaterole').style.display = 'none';
                }
                return false;
            } else {
                document.querySelector('.validatefullname').style.display = 'none';
                document.querySelector('.validateusername').style.display = 'none';
                document.querySelector('.validateemail').style.display = 'none';
                document.querySelector('.validatepassword').style.display = 'none';
                document.querySelector('.validateconfirmpassword').style.display = 'none';
                document.querySelector('.validaterole').style.display = 'none';
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
         // Attach an event listener to the form's submit event
        document.getElementById('registerForm').addEventListener('submit', function(event) {
        // Call the validateForm function and prevent submission if it returns false
        if (!validateForm()) {
            event.preventDefault();
        }
        });
    </script>

<?php
require 'footer.php'; // Include the footer file
?>
</body>
</html>
