<?php
// Include the database connection file
include('includes/db_connection.php');

// Check if the ID parameter is provided in the URL
if(isset($_GET['id'])) {
    // Get the staff member ID from the URL
    $staff_id = $_GET['id'];

    // Fetch the staff member data from the database
    $query = "SELECT * FROM hd_users WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the staff member exists
    if($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
    } else {
        // If staff member does not exist, redirect to staff list page
        header("Location: staff.php");
        exit();
    }
} else {
    // If no ID parameter provided, redirect to staff list page
    header("Location: staff.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $user_type = $_POST["user_type"];
    $status = $_POST["status"];
    $password = $_POST["newPassword"];

    // Update the staff member data in the database using prepared statements
    $updateQuery = "UPDATE hd_users SET name = ?, email = ?, user_type = ?, status = ?, password = ? WHERE id = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("sssiii", $name, $email, $user_type, $status, $password, $staff_id);
    if ($stmt->execute()) {
        // Update successful, redirect to staff list page
        header("Location: staff.php");
        exit();
    } else {
        // Update failed, display error message
        $error_message = "Error updating record: " . $stmt->error;
    }
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Edit Staff Member</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Edit Staff Member</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $staff_id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($staff['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_type" class="form-label">User Type</label>
                        <select id="user_type" name="user_type" class="form-control" required>
                            <option value="admin" <?php if($staff['user_type'] == 'admin') echo "selected"; ?>>Admin</option>
                            <option value="staff" <?php if($staff['user_type'] == 'staff') echo "selected"; ?>>Staff</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="1" <?php if($staff['status'] == 1) echo "selected"; ?>>Active</option>
                            <option value="0" <?php if($staff['status'] == 0) echo "selected"; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" value="<?php echo htmlspecialchars($staff['password']); ?>" required>
                    </div>
                    <?php if(isset($error_message)) { ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
                    <?php } ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <a href="staff.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
