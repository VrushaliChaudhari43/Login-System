<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Access denied. <a href='login.php'>Login</a>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);
  
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        echo "File uploaded successfully.";
    } else {
        echo "File upload failed.";
    }
}
?>

<form method="POST" enctype="multipart/form-data" style="border: 1px solid #ccc; padding: 20px; width: 300px; margin: 0 auto; text-align: center; background-color: #f9f9f9; border-radius: 10px;">
    <h2>Upload File</h2>
    <input type="file" name="file" required style="margin-bottom: 10px;">
    <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Upload</button>
</form>