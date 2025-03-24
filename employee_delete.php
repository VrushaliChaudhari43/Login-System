<?php
session_start();
include 'db.php'; // Contains $conn for the database connection

header('Content-Type: application/json');

// Optionally, check if the user is logged in (if deletion is for authenticated users only)
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Check if the employee id is provided in the URL
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $employee_id = intval($_POST['id']);

    // Check if the connection is successful
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
        exit();
    }

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if (!$stmt) {
        echo 'success';
    }
    
    $stmt->bind_param("i", $employee_id);
    
    if ($stmt->execute()) {
        // Successful deletion
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
} else {
    // If no valid id is provided
        echo 'error';
}
?>
