<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
}
?>

<form method="post" style="max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9;">
    <h2 style="text-align: center;">Login</h2>
    <div style="margin-bottom: 1em;"></div>
        <label for="username" style="display: block; margin-bottom: .5em;">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" required style="width: 100%; padding: .5em; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <div style="margin-bottom: 1em;">
        <label for="password" style="display: block; margin-bottom: .5em;">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" required style="width: 100%; padding: .5em; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <button type="submit" style="width: 100%; padding: .5em; background: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer;">Login</button>
</form>
