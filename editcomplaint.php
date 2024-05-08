<?php
// Include the database connection file
include('includes/db_connection.php');

// Check if the ID parameter is provided in the URL
if(isset($_GET['id'])) {
    // Get the complaint ID from the URL
    $complaint_id = $_GET['id'];

    // Fetch the complaint data from the database
    $query = "SELECT * FROM hd_complaints WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the complaint exists
    if($result->num_rows > 0) {
        $complaint = $result->fetch_assoc();
    } else {
        // If complaint does not exist, redirect to complaints list page
        header("Location: complaints.php");
        exit();
    }
} else {
    // If no ID parameter provided, redirect to complaints list page
    header("Location: complaints.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    // Retrieve form data
    $complaintName = $_POST["complaintName"];
    $complaintStatus = $_POST["complaintStatus"];

    // Update the complaint data in the database using prepared statements
    $updateQuery = "UPDATE hd_complaints SET name = ?, status = ? WHERE id = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("sii", $complaintName, $complaintStatus, $complaint_id);
    if ($stmt->execute()) {
        // Update successful, redirect to complaints list page
        header("Location: complaints.php");
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

    <title>Edit Complaint</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Edit Complaint</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $complaint_id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="complaintName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="complaintName" name="complaintName" value="<?php echo htmlspecialchars($complaint['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="complaintStatus" class="form-label">Status</label>
                        <select id="complaintStatus" name="complaintStatus" class="form-control" required>
                            <option value="1" <?php if($complaint['status'] == 1) echo "selected"; ?>>Active</option>
                            <option value="0" <?php if($complaint['status'] == 0) echo "selected"; ?>>Inactive</option>
                        </select>
                    </div>
                    <?php if(isset($error_message)) { ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
                    <?php } ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <a href="complaints.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
