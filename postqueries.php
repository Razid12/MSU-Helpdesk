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

// Fetch data from the database including complaint names for the logged-in staff member
$staff_id = $_SESSION['id'];
$query = "SELECT ac.id, ac.name AS user_name, ac.email, ac.uniqid, hc.name AS complaint_name 
          FROM assigned_complaints ac 
          JOIN hd_complaints hc ON ac.complaints = hc.id 
          WHERE ac.assigned_to = '$staff_id'";

$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Panel</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Upload Document and Notify User</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>Ticket ID</th>
                    <th>User Email</th>
                    <th>Complaint Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['uniqid']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['complaint_name']; ?></td>
                    <td><a href="uploaddocument.php?id=<?php echo $row['id']; ?>&email=<?php echo $row['email']; ?>&complaint=<?php echo $row['complaint_name']; ?>">Upload Document</a></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($con);
?>
