<?php
// Include the database connection file
include('includes/db_connection.php');

// Check if the ID parameter is provided in the URL
if(isset($_GET['id'])) {
    // Get the complaint ID from the URL
    $complaint_id = $_GET['id'];

    // Delete the complaint from the database using prepared statement to prevent SQL injection
    $deleteQuery = "DELETE FROM ticket_complaints WHERE id = ?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param("i", $complaint_id);
    if ($stmt->execute()) {
        // Delete successful, redirect to complaints list page
        header("Location: user.php");
        exit();
    } else {
        // Delete failed, display error message
        $error_message = "Error: " . $stmt->error;
    }
} else {
    // If no ID parameter provided, redirect to complaints list page
    header("Location: user.php");
    exit();
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$con->close();
?>
