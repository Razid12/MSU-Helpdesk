<?php
session_start();

include('includes/db_connection.php');

if(isset($_POST['save_complaint'])) {
    // Retrieve user ID from session
    $user_id = $_SESSION['id'];
    
    // Generate unique ID
    $uniqid = uniqid();
    
    // Sanitize and retrieve complaint details
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $reg_no = mysqli_real_escape_string($con, $_POST['reg_no']); // Sanitize register number
    $stakeholders = mysqli_real_escape_string($con, $_POST['stakeholders']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phn_no = mysqli_real_escape_string($con, $_POST['phn_no']);
    $complaints = mysqli_real_escape_string($con, $_POST['complaints']);
    $Details = mysqli_real_escape_string($con, $_POST['message']); // Correctly retrieve the 'message' field
    
    // Sanitize and retrieve complaint date
    $complaint_date_raw = mysqli_real_escape_string($con, $_POST['registered_date']);
    $complaint_date = date('Y-m-d', strtotime($complaint_date_raw)); // Format the date as YYYY-MM-DD

    // Handle image upload
    if(isset($_FILES['complaint_copy']) && $_FILES['complaint_copy']['error'] === UPLOAD_ERR_OK) {
        // Get the image data
        $imageData = file_get_contents($_FILES['complaint_copy']['tmp_name']);
        // Escape special characters
        $imageData = mysqli_real_escape_string($con, $imageData);
    } else {
        // Set default image data if no image is uploaded
        $imageData = null;
    }

    // Get the creator's username from the users table
    $query_username = "SELECT name FROM users WHERE id = '$user_id'";
    $result_username = mysqli_query($con, $query_username);

    if ($result_username) {
        // Fetch the username
        $row = mysqli_fetch_assoc($result_username);
        $created_by = $row['name'];
    } else {
        // Handle errors
        echo "Error: " . mysqli_error($con);
    }
    
    $created_at = time();
   
    // Insert the complaint into the database along with the user ID, complaint date, and image data
    $query = "INSERT INTO ticket_complaints (user_id, uniqid, name, reg_no, stakeholders, email, phn_no, complaints, Details, created_by, created_at, complaint_date, complaint_copy) 
              VALUES ('$user_id', '$uniqid', '$name', '$reg_no', '$stakeholders', '$email', '$phn_no', '$complaints', '$Details', '$created_by', '$created_at', '$complaint_date', '$imageData')";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        $_SESSION['message'] = "Complaint Created Successfully";
        header("Location: addticket.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Complaint Not Created";
        header("Location: addticket.php");
        exit(0);
    }
}
if(isset($_POST['update_complaint'])) {
    // Retrieve complaint ID from form
    $complaint_id = isset($_POST['complaint_id']) ? mysqli_real_escape_string($con, $_POST['complaint_id']) : null;
    
    // Sanitize and retrieve updated complaint details
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $reg_no = isset($_POST['reg_no']) ? mysqli_real_escape_string($con, $_POST['reg_no']) : null; // Sanitize register number
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    // Create the 'uploads' directory if it doesn't exist
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }
    
    // Check if a new file is uploaded
    if(isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        // File upload handling
        $targetDirectory = "uploads/"; // Directory where files will be uploaded
        $targetFile = $targetDirectory . basename($_FILES["document"]["name"]);

        // Attempt to move uploaded file
        if (move_uploaded_file($_FILES["document"]["tmp_name"], $targetFile)) {
            // File uploaded successfully, now store file path in database
            $documentPath = $targetFile;

            // Update complaint in the database along with the uploaded document path
            $query = "UPDATE ticket_complaints 
                      SET name = '$name', reg_no = '$reg_no', email = '$email', document_path = '$documentPath' 
                      WHERE id = '$complaint_id'";
            $query_run = mysqli_query($con, $query);

            if($query_run) {
                $_SESSION['message'] = "Complaint Updated Successfully";
                header("Location: user.php");
                exit;
            } else {
                $_SESSION['message'] = "Complaint Not Updated";
                header("Location: user.php");
                exit;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        // No new file uploaded, update complaint without changing the document path
        $query = "UPDATE ticket_complaints 
                  SET name = '$name', reg_no = '$reg_no', email = '$email' 
                  WHERE id = '$complaint_id'";
        $query_run = mysqli_query($con, $query);

        if($query_run) {
            $_SESSION['message'] = "Complaint Updated Successfully";
            header("Location: user.php");
            exit;
        } else {
            $_SESSION['message'] = "Complaint Not Updated";
            header("Location: user.php");
            exit;
        }
    }
}


?>
