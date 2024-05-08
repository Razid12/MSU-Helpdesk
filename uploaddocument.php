<?php
session_start();
// Include your database connection file
include('includes/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form fields are set and not empty
    if (isset($_POST['complaint']) && isset($_POST['document_details'])) {
        $complaint_id = $_POST['complaint'];
        $document_details = $_POST['document_details'];

        // Update the document details in the database
        $update_query = "UPDATE assigned_complaints SET document_details = '$document_details' WHERE id = $complaint_id";
        if (mysqli_query($con, $update_query)) {
            // Document details updated successfully
            header("Location: send_email1.php?complaint_id=$complaint_id"); // Redirect to send_email.php with the complaint ID
            exit();
        } else {
            // Error updating document details
            echo "Error: " . mysqli_error($con);
        }
    } else {
        // Handle case where form fields are not set or empty
        echo "Invalid form data";
    }
}

// Fetch data from the database including complaint names for the logged-in staff member
$staff_id = $_SESSION['id'];
$query = "SELECT ac.id, ac.name AS complaint_name, ac.email, ac.uniqid, ac.name 
          FROM assigned_complaints ac 
          WHERE ac.assigned_to = '$staff_id'";

$result = mysqli_query($con, $query);

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    // Fetch all rows from the result set
    $complaints = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Handle case where no rows are returned, display error message or redirect
    echo "No complaints found for the logged-in staff member.";
    exit();
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Document</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Request Document Upload</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="complaint">Select Complaint:</label>
                <select class="form-control" id="complaint" name="complaint" required>
                    <option value="">Select Complaint</option>
                    <?php foreach ($complaints as $complaint) : ?>
                        <option value="<?php echo $complaint['id']; ?>"><?php echo $complaint['complaint_name'] . ' - ' . $complaint['email']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="document_details">Document Details:</label>
                <textarea class="form-control" id="document_details" name="document_details" placeholder="Enter document details" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Request</button>
        </form>
    </div>
</body>
</html>
