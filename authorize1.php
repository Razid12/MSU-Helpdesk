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

// Fetch the ticket ID from the form submission
$ticket_id = isset($_POST['id']) ? $_POST['id'] : '';

// Fetch data of the assigned complaint from the assigned_complaints table
$complaint_query = "SELECT * FROM assigned_complaints WHERE id = '$ticket_id'";
$complaint_result = mysqli_query($con, $complaint_query);
$complaint_row = mysqli_fetch_assoc($complaint_result);

// Process form submission
if (isset($_POST['assign_complaint'])) {
    $complaint_id = $_POST['complaint_id'];
    $assigned_to = $_POST['assigned_to'];

    // Update the assigned_complaints table with the assigned user
    $update_query = "UPDATE assigned_complaints SET assigned_to = '$assigned_to' WHERE id = '$complaint_id'";
    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {
        $_SESSION['message'] = "Complaint assigned successfully";
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
                                    value="<?php echo $complaint_row['id']; ?>" readonly>
                            </div>
                            <!-- Add other fields as needed -->
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
