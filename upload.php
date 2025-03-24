<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Access denied. <a href='login.php'>Login</a>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Directory where the file will be uploaded
    $uploadDir = 'uploads/';
    // Path of the file to be uploaded
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);
    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {     
        // Set parameters for database insertion
        $fileName = basename($_FILES['file']['name']);
        $filePath = $uploadFile;
        $userId = $_SESSION['user_id'];
        
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO filedata (filename, filepath, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $fileName, $filePath, $userId);
        $stmt->execute();

        // Success message
        echo "<div class='alert alert-success' role='alert'>File uploaded and saved to database successfully.</div>";
        // Close the statement
        $stmt->close();
    } else {
        // Error message
        echo "<div class='alert alert-danger' role='alert'>File upload failed.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <?php require 'sidebar.php'; // Include the sidebar file ?>
            </div>
            <div class="col-md-9">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <form method="POST" enctype="multipart/form-data" class="border p-4 bg-light rounded">
                            <h2 class="text-center">Upload File</h2>
                            <div class="form-group">
                                <input type="file" name="file" class="form-control-file" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block col-lg-2">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>