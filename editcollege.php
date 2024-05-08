<?php
// Include the database connection file
include('includes/db_connection.php');

// Check if the ID parameter is provided in the URL
if(isset($_GET['id'])) {
    // Get the college ID from the URL
    $college_id = $_GET['id'];

    // Fetch the college data from the database
    $query = "SELECT * FROM hd_college WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $college_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the college exists
    if($result->num_rows > 0) {
        $college = $result->fetch_assoc();
    } else {
        // If college does not exist, redirect to colleges list page
        header("Location: colleges.php");
        exit();
    }
} else {
    // If no ID parameter provided, redirect to colleges list page
    header("Location: colleges.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    // Retrieve form data
    $collegeName = $_POST["collegeName"];
    $collegeStatus = $_POST["collegeStatus"];

    // Update the college data in the database using prepared statements
    $updateQuery = "UPDATE hd_college SET name = ?, status = ? WHERE id = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("sii", $collegeName, $collegeStatus, $college_id);
    if ($stmt->execute()) {
        // Update successful, redirect to colleges list page
        header("Location: colleges.php");
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

    <title>Edit College</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Edit College</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $college_id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="collegeName" class="form-label">College Name</label>
                        <input type="text" class="form-control" id="collegeName" name="collegeName" value="<?php echo htmlspecialchars($college['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="collegeStatus" class="form-label">Status</label>
                        <select id="collegeStatus" name="collegeStatus" class="form-control" required>
                            <option value="1" <?php if($college['status'] == 1) echo "selected"; ?>>Active</option>
                            <option value="0" <?php if($college['status'] == 0) echo "selected"; ?>>Inactive</option>
                        </select>
                    </div>
                    <?php if(isset($error_message)) { ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
                    <?php } ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <a href="college.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
