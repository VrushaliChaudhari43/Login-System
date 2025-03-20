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
        echo "File uploaded and saved to database successfully.";
        // Close the statement
        $stmt->close();
    } else {
        // Error message
        echo "File upload failed.";
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
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" enctype="multipart/form-data" class="border p-4 bg-light rounded">
                    <h2 class="text-center">Upload File</h2>
                    <div class="form-group">
                        <input type="file" name="file" class="form-control-file" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Upload</button>
                </form>
                <form method="POST" action="logout.php" class="mt-3">
                    <button type="submit" class="btn btn-danger btn-block" style="background-color: #dc3545; border-color: #dc3545;">Logout</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>