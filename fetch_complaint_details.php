<?php
// Include your database connection file
include('includes/db_connection.php');

// Check if the complaintName parameter is set
if(isset($_POST['complaintName'])) {
    // Fetch complaint details based on the complaint name
    $complaintName = $_POST['complaintName'];
    $query = "SELECT ac.name AS user_name, ac.email, ac.uniqid, hc.name AS complaint_name, ac.stakeholders, ac.reg_no
              FROM assigned_complaints ac 
              JOIN hd_complaints hc ON ac.complaints = hc.id 
              WHERE hc.name = '$complaintName'";

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Prepare JSON response
        $response = array(
            'uniqid' => $row['uniqid'],
            'user_name' => $row['user_name'],
            'email' => $row['email'],
            'complaint_name' => $row['complaint_name'],
            'stakeholders' => $row['stakeholders'],
            'reg_no' => $row['reg_no']
        );

        echo json_encode($response);
    } else {
        // Return error message if no data found
        $response = array('error' => 'No data found for the selected complaint.');
        echo json_encode($response);
    }
} else {
    // Return error message if complaintName parameter is not set
    $response = array('error' => 'Complaint name not provided.');
    echo json_encode($response);
}
?>
