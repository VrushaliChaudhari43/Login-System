<?php
$user_role = $_SESSION['role']; // Assuming you store the user role in session
?>

<div class="d-flex flex-column p-3 bg-light" style="width: 250px; height: 100vh;">
    <h4 class="mb-4">Menu</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link active" aria-current="page">
                Dashboard
            </a>
        </li>
        <li>
            <a href="profile.php" class="nav-link text-dark">
                Profile
            </a>
        </li>
       
        <?php if ($user_role == 'admin') { ?>
          
            <li>
                <a href="employee.php" class="nav-link text-dark">
                    Manage Users
                </a>
            </li>
             <li>
                <a href="files.php" class="nav-link text-dark">
                    Uploaded File
                </a>
            </li>
        <?php } else if ($user_role == 'employee') { ?>
             <li>
            <a href="upload.php" class="nav-link text-dark">
                Upload File
            </a>
        </li>
           <li>
            <a href="admin_login.php" class="nav-link text-dark">
                Login as Admin
            </a>
        </li>
        <?php } ?>
        
        <li>
            <a href="logout.php" class="nav-link text-dark">
                Logout
            </a>
        </li>
    </ul>
</div>
