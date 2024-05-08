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

// Check if complaint ID is provided
if (!isset($_GET['id'])) {
    // Redirect or display error message
    echo "No complaint ID provided.";
    exit();
}

$complaint_id = $_GET['id'];

// Fetch ticket details from the database
$query = "SELECT ac.uniqid, ac.name AS user_name, ac.email, ac.stakeholders, ac.reg_no, ac.name AS complaint_name
          FROM assigned_complaints ac 
          WHERE ac.id = '$complaint_id'";

$result = mysqli_query($con, $query);

// Check if the query executed successfully
if (!$result) {
    // Display error message or log the error
    echo "Error: " . mysqli_error($con);
    exit();
}

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    // Fetch ticket details
    $row = mysqli_fetch_assoc($result);
    $uniqid = $row['uniqid'];
    $user_name = $row['user_name'];
    $email = $row['email'];
    $stakeholders = $row['stakeholders'];
    $reg_no = $row['reg_no'];
    $complaint_name = $row['complaint_name'];

    // Close the database connection
    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Close Ticket</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Close Ticket</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="uniqid">Uniqid:</label>
                    <div id="uniqid"><?php echo $uniqid; ?></div>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <div id="name"><?php echo $user_name; ?></div>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <div id="email"><?php echo $email; ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="stakeholders">Stakeholders:</label>
                    <div id="stakeholders"><?php echo $stakeholders; ?></div>
                </div>
                <div class="form-group">
                    <label for="regno">Reg No:</label>
                    <div id="regno"><?php echo $reg_no; ?></div>
                </div>
                <div class="form-group">
                    <label for="complaint_name">Complaint Name:</label>
                    <div id="complaint_name"><?php echo $complaint_name; ?></div>
                </div>
            </div>
        </div>
        <form id="closeTicketForm" action="close_ticket_process.php" method="post">
            <input type="hidden" name="uniqid" value="<?php echo $uniqid; ?>">
            <input type="hidden" name="user_name" value="<?php echo $user_name; ?>">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="hidden" name="stakeholders" value="<?php echo $stakeholders; ?>">
            <input type="hidden" name="reg_no" value="<?php echo $reg_no; ?>">
            <input type="hidden" name="complaint_name" value="<?php echo $complaint_name; ?>">
            <button type="submit" class="btn btn-primary">Close Ticket</button>
        </form>
    </div>
    <script>
        document.getElementById('closeTicketForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            this.submit(); // Submit the form
        });
    </script>
</body>
</html>

<?php
} else {
    // Handle case where no rows are returned, display error message or redirect
    echo "No ticket found with the provided ID.";
    exit();
}
?>
