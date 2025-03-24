<?php
session_start();

// Include your database configuration and sanitization function
include 'db.php';   // File containing the $conn (MySQLi connection)

// Define the sanitize function


// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$fullname = "";
$message = "";

// Process the form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the full name; password is sanitized later (if provided)
    $fullname = sanitize($_POST['fullname']);
    $new_password = $_POST['password']; // do not sanitize immediately as we need to check if provided
    $confirm_password = $_POST['confirm_password'];

    // Validate the input
    if (empty($fullname)) {
        $message = "Full Name is required.";
    } elseif (!empty($new_password) && $new_password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // If a new password is provided, hash it and update it along with fullname
        if (!empty($new_password)) {
            // Sanitize and hash the new password
            $hashed_password = password_hash(sanitize($new_password), PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET fullname = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssi", $fullname, $hashed_password, $user_id);
        } else {
            // Update only the fullname
            $stmt = $conn->prepare("UPDATE users SET fullname = ? WHERE id = ?");
            $stmt->bind_param("si", $fullname, $user_id);
        }
        if ($stmt->execute()) {
            $message = "Profile updated successfully.";
        } else {
            $message = "Error updating profile.";
        }
        $stmt->close();
    }
} else {
    // Fetch current user details for pre-filling the form
    $stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($fullname);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Update Profile</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'header.php'; ?>
  <div class="container mt-5">
    <h2>Update Profile</h2>
    <?php if (!empty($message)) { ?>
      <div class="alert alert-info"><?php echo $message; ?></div>
    <?php } ?>
    <form method="post" action="">
      <div class="mb-3">
        <label for="fullname" class="form-label">Full Name</label>
        <input type="text" name="fullname" id="fullname" class="form-control" value="<?php echo htmlspecialchars($fullname); ?>" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">New Password (leave blank to keep unchanged)</label>
        <input type="password" name="password" id="password" class="form-control">
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
  </div>
  <?php include 'footer.php'; ?>
  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
