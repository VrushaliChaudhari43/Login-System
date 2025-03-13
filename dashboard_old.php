<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle File Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $user_id = $_SESSION['user_id'];
    $file_name = basename($_FILES["file"]["name"]);
    $target_dir = "uploads/";
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO uploads (user_id, file_name) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $file_name);
        $stmt->execute();
        echo "File uploaded successfully!";
    } else {
        echo "Error uploading file.";
    }
}

// Fetch Uploaded Files
// $stmt = $conn->prepare("SELECT file_name FROM uploads WHERE user_id = ?");
// $stmt->bind_param("i", $_SESSION['user_id']);
// $stmt->execute();
// $result = $stmt->get_result();
// $files = $result->fetch_all(MYSQLI_ASSOC);
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

<!-- File Upload Form -->
<h3>Upload File</h3>
<form method="post" enctype="multipart/form-data" style="margin-bottom: 20px;"></form>
    <input type="file" name="file" required style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;"><br><br>
    <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Upload</button>
</form>

<!-- Display Uploaded Files -->
<h3>Your Uploaded Files:</h3>
<ul style="list-style-type: none; padding: 0;"></ul>
    <?php foreach ($files as $file): ?>
        <li style="margin-bottom: 10px;"></li>
            <a href="uploads/<?php echo htmlspecialchars($file['file_name']); ?>" target="_blank" style="text-decoration: none; color: #4CAF50;">
                <?php echo htmlspecialchars($file['file_name']); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<a href="logout.php" style="display: inline-block; margin-top: 20px; background-color: #f44336; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Logout</a>
