<?php
session_start();
include('includes/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id']; // Assuming you store user ID in session

// Fetch list of staff from hd_user table (exclude admins)
$query = "SELECT * FROM hd_users WHERE user_type = 'staff'";
$result = mysqli_query($con, $query);

// Fetch ticket details from ticket_complaints table
$ticket_id = $_GET['id']; // Assuming you pass the ticket ID via URL
$ticket_query = "SELECT * FROM ticket_complaints WHERE id = '$ticket_id'";
$ticket_result = mysqli_query($con, $ticket_query);
$ticket_row = mysqli_fetch_assoc($ticket_result);

// Check if the 'id' index exists in the $ticket_row array
if (!isset($ticket_row['id'])) {
    // Handle the case where 'id' index is not set
    echo "Ticket ID not found";
    exit();
}

// Process form submission
if (isset($_POST['assign_complaint'])) {
    $complaint_id = $_POST['complaint_id'];
    $assigned_to = $_POST['assigned_to'];

    // Update the ticket_complaints table with the assigned user
    $update_query = "UPDATE ticket_complaints SET assigned_to = '$assigned_to' WHERE id = '$complaint_id'";
    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {
        // Insert data into assigned_complaints table
        $insert_query = "INSERT INTO assigned_complaints (name, reg_no, stakeholders, email, complaints, assigned_to,uniqid) 
                         VALUES ('{$ticket_row['name']}', '{$ticket_row['reg_no']}', '{$ticket_row['stakeholders']}', 
                                 '{$ticket_row['email']}', '{$ticket_row['complaints']}', $assigned_to,'{$ticket_row['uniqid']}')";

        $insert_result = mysqli_query($con, $insert_query);

        if ($insert_result) {
            $_SESSION['message'] = "Complaint assigned successfully";
        } else {
            $_SESSION['error'] = "Failed to assign complaint";
        }
    } else {
        $_SESSION['error'] = "Failed to update complaint";
    }
    header("Location: ticket.php"); // Redirect back to admin panel
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Authorize Complaint - MSU Helpdesk Admin Panel</title>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Authorize Complaint</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="complaint_id" class="form-label">Complaint ID</label>
                                <input type="text" class="form-control" id="complaint_id" name="complaint_id"
                                    value="<?php echo $ticket_row['uniqid']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="ticket_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="ticket_name" name="ticket_name"
                                    value="<?php echo $ticket_row['name']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="ticket_reg_no" class="form-label">Register Number</label>
                                <input type="text" class="form-control" id="ticket_reg_no" name="ticket_reg_no"
                                    value="<?php echo $ticket_row['reg_no']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="ticket_stakeholders" class="form-label">Stakeholders</label>
                                <input type="text" class="form-control" id="ticket_stakeholders"
                                    name="ticket_stakeholders" value="<?php echo $ticket_row['stakeholders']; ?>"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="ticket_email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="ticket_email" name="ticket_email"
                                    value="<?php echo $ticket_row['email']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="ticket_complaints" class="form-label">Complaints</label>
                                <input type="text" class="form-control" id="ticket_complaints" name="ticket_complaints"
                                    value="<?php echo $ticket_row['complaints']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="ticket_details" class="form-label">Details</label>
                                <textarea class="form-control" id="ticket_details" name="ticket_details"
                                    readonly><?php echo $ticket_row['Details']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Assign To</label>
                                <select class="form-select" id="assigned_to" name="assigned_to" required>
                                    <option value="">Select User</option>
                                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary" name="assign_complaint">Assign
                                Complaint</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
