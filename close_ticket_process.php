<?php
// Include Composer's autoloader for PHPMailer
require 'vendor/autoload.php';

// Include your database connection file
include('includes/db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['uniqid'])) {
    // Check if the necessary fields are set and not empty
    $uniqid = $_POST['uniqid'];

    // Update the status of the complaint to closed
    $update_query = "UPDATE assigned_complaints SET status = 'closed' WHERE uniqid = '$uniqid'";
    if (mysqli_query($con, $update_query)) {
        // Status updated successfully, now send email notification
        $email_query = "SELECT email, name FROM assigned_complaints WHERE uniqid = '$uniqid'";
        $email_result = mysqli_query($con, $email_query);
        if ($email_result && mysqli_num_rows($email_result) > 0) {
            $row = mysqli_fetch_assoc($email_result);
            $email = $row['email'];
            $name = $row['name'];

            // Create a new PHPMailer instance
            $mail = new PHPMailer\PHPMailer\PHPMailer(true); // Set true for exceptions

            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'razidlathief11@gmail.com'; // Your Gmail email address
                $mail->Password = 'pzmk eszc raoy grga'; // Your Gmail password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption
                $mail->Port = 587; // TCP port to connect to

                // Set sender and recipient
                $mail->setFrom('razidlathief11@gmail.com', 'Razid Lathief'); // Your email address and name
                $mail->addAddress($email, $name); // Recipient's email address and name

                // Set email subject and body
                $mail->Subject = "Complaint Closed";
                $mail->Body = "Dear $name,\n\nYour complaint with Ticketid: $uniqid has been closed.\n\nThank you.\n\nSincerely,\nMSU Helpdesk";

                // Send email
                $mail->send();

                // Redirect to staff_panel.php
                header("Location: staff_panel.php");
                exit();
            } catch (Exception $e) {
                // Display error message as a JavaScript notification
                echo "<script>alert('Error sending email: " . $mail->ErrorInfo . "');</script>";
            }
        } else {
            // Display error message as a JavaScript notification
            echo "<script>alert('Error fetching email address for notification.');</script>";
        }
    } else {
        // Display error message as a JavaScript notification
        echo "<script>alert('Error updating status: " . mysqli_error($con) . "');</script>";
    }
} else {
    // Handle case where necessary fields are not set or empty
    echo "<script>alert('Invalid request or missing parameters.');</script>";
}

// Close the database connection
mysqli_close($con);
?>
