<?php
session_start();
include 'db.php'; // Database connection

// Include header, sidebar, and footer files
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <?php require 'sidebar.php'; // Include the sidebar file ?>
                </div>
                <div class="col-md-9">
                    <h2 class="mb-4">Files</h2>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>File</th>
                                <th>Uploaded By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Retrieve employee records from the database
                            $sql = "SELECT filedata.id as file_id, filedata.filename, filedata.filepath, users.fullname, users.email, users.role FROM filedata LEFT JOIN users ON filedata.user_id = users.id";
                            $result = $conn->query($sql);
                        
                            if ($result->num_rows > 0) {
                                // Output data for each row
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['file_id'] . "</td>";
                                    echo "<td><a href='/Login System/" . htmlspecialchars($row['filepath']) . "'>" . htmlspecialchars($row['filename']) . "</a></td>";
                                    echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                                   
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No Files Found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

<?php include 'footer.php'; ?>
<!-- Bootstrap Bundle with Popper -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function deleteEmployee(id) {
    if (confirm("Are you sure you want to delete this employee?")) {
        $.ajax({
            url: 'employee_delete.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                if (response == 'success') {
                    alert('Employee deleted successfully.');
                    location.reload();
                } else {
                    alert('Failed to delete employee.');
                }
            }
        });
    }
}
</script>

</body>
</html>
