<?php
// Include the database connection file
include('includes/db_connection.php');

// Check if the ID parameter is provided in the URL
if(isset($_GET['id'])) {
    // Get the complaint ID from the URL
    $complaint_id = $_GET['id'];

    // Delete the complaint from the database
    $deleteQuery = "DELETE FROM hd_complaints WHERE id = $complaint_id";
    if ($con->query($deleteQuery) === TRUE) {
        // Delete successful, redirect to complaints list page
        header("Location: complaints.php");
        exit();
    } else {
        // Delete failed, display error message
        $error_message = "Error: " . $deleteQuery . "<br>" . $con->error;
    }
} else {
    // If no ID parameter provided, redirect to complaints list page
    header("Location: complaints.php");
    exit();
}

// Close the database connection
$con->close();
?>
