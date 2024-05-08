<?php
// Include the database connection file
include('includes/db_connection.php');

// Check if the ID parameter is provided in the URL
if(isset($_GET['id'])) {
    // Get the college ID from the URL
    $college_id = $_GET['id'];

    // Delete the college from the database
    $deleteQuery = "DELETE FROM hd_college WHERE id = $college_id";
    if ($con->query($deleteQuery) === TRUE) {
        // Delete successful, redirect to colleges list page
        header("Location: colleges.php");
        exit();
    } else {
        // Delete failed, display error message
        $error_message = "Error: " . $deleteQuery . "<br>" . $con->error;
    }
} else {
    // If no ID parameter provided, redirect to colleges list page
    header("Location: colleges.php");
    exit();
}

// Close the database connection
$con->close();
?>
