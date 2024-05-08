<?php
// Include Composer's autoloader for PHPMailer
require 'vendor/autoload.php';

// Include your database connection file
include('includes/db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    // Check if the complaint ID is provided
    $complaint_id = $_GET['id'];

    // Fetch the complaint details based on the selected ID
    $query = "SELECT id, name AS complaint_name, email, uniqid, name , document_details, stakeholders
              FROM assigned_complaints 
              WHERE id = '$complaint_id'";

    $result = mysqli_query($con, $query);

    // Check if there are any rows returned
    if (mysqli_num_rows($result) > 0) {
        $complaint = mysqli_fetch_assoc($result);

        // Extract data from the fetched complaint
        $complaintName = $complaint['complaint_name'];
        $email = $complaint['email'];
        $uniqid = $complaint['uniqid'];
        $name = $complaint['name'];
        $document_details = $complaint['document_details'];
        $stakeholders = $complaint['stakeholders'];

        // Create a new PHPMailer instance
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'razidlathief11@gmail.com'; // Your Gmail email address
        $mail->Password = 'pzmk eszc raoy grga'; // Your Gmail password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Set sender and recipient
        $mail->setFrom('razidlathief11@gmail.com', 'MSU Help Desk'); // Your email address and name
        $mail->addAddress($email, $name); // Recipient's email address and name

        // Set email subject and body
        $mail->Subject = "Complaint Closed";
        $mail->Body = "Dear $name,\n\nWe are pleased to inform you that your complaint titled \"$complaintName\" has been resolved and the ticket is closed. Thank you for your patience.\n\nSincerely,\nYour Organization";

        // Send email
        if ($mail->send()) {
            // Redirect to a success page
            header("Location: success1.php?status=success");
            exit();
        } else {
            // Redirect to an error page
            header("Location: error1.php?status=error");
            exit();
        }
    } else {
        // Redirect to an error page
        header("Location: error1.php?status=not_found");
        exit();
    }
} else {
    // Redirect to an error page
    header("Location: error1.php?status=invalid_request");
    exit();
}

// Close the database connection
mysqli_close($con);
?>
