<?php
// Include the database connection file
include('includes/db_connection.php');

// Check if the ID parameter is provided in the URL
if(isset($_GET['id'])) {
    // Get the department ID from the URL
    $department_id = $_GET['id'];

    // Delete the department from the database
    $deleteQuery = "DELETE FROM hd_department WHERE id = $department_id";
    if ($con->query($deleteQuery) === TRUE) {
        // Delete successful, redirect to departments list page
        header("Location: department.php");
        exit();
    } else {
        // Delete failed, display error message
        $error_message = "Error: " . $deleteQuery . "<br>" . $con->error;
    }
} else {
    // If no ID parameter provided, redirect to departments list page
    header("Location: department.php");
    exit();
}

// Close the database connection
$con->close();
?>
