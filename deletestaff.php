<?php
// Include the database connection file
include('includes/db_connection.php');

// Check if the ID parameter is provided in the URL
if(isset($_GET['id'])) {
    // Get the staff member ID from the URL
    $staff_id = $_GET['id'];

    // Delete the staff member from the database using prepared statement to prevent SQL injection
    $deleteQuery = "DELETE FROM hd_users WHERE id = ?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param("i", $staff_id);
    if ($stmt->execute()) {
        // Delete successful, redirect to staff list page
        header("Location: staff.php");
        exit();
    } else {
        // Delete failed, display error message
        $error_message = "Error: " . $stmt->error;
    }
} else {
    // If no ID parameter provided, redirect to staff list page
    header("Location: staff.php");
    exit();
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$con->close();
?>
