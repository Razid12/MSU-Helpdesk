<?php
session_start();
include('includes/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

$staff_id = $_SESSION['id']; // Assuming you store staff ID in session

// Fetch assigned complaints for the staff member including user's name and complaint name
$query = "SELECT ac.id, hc.name AS complaint_name,ac.name, ac.reg_no, ac.stakeholders, ac.email, ac.complaints AS user_complaint, hu.name AS assigned_to_name, ac.assigned_date, ac.uniqid, ac.status 
          FROM assigned_complaints ac 
          JOIN hd_complaints hc ON ac.complaints = hc.id 
          JOIN hd_users hu ON ac.assigned_to = hu.id 
          WHERE ac.assigned_to = '$staff_id'";

$result = mysqli_query($con, $query);
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Internal CSS for thread class -->
    <style>
        .thread {
            font-size: 12px; /* Adjust the font size as per your requirement */
        }

        /* Adjust body size */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Adjust container size */
        .container {
            flex: 1;
        }
    </style>

    <title>Staff Panel - Assigned Complaints</title>
</head>

<body>
    <div class="container-fluid mt-5"> <!-- Use container-fluid for full width -->

        <?php include('message.php'); ?>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h2><img src="images/logo.jpg" alt="MSU Logo" style="height: 50px; vertical-align: middle;">MSU Helpdesk Staff Panel</h2>    
                        <?php include('menus.php');?>
                        <h4>Assigned Complaints</h4>
                    </div>
                    <div class="card-body thread">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Ticket ID</th> <!-- Added column for Complaint Uniqid -->
                                    <th>Register Number</th>
                                    <th>Stakeholders</th>
                                    <th>Emails</th>
                                    <th>User's Complaint</th>
                                    <th>Assigned to</th>
                                    <th>Assigned Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['uniqid']; ?></td> <!-- Display Complaint Uniqid -->
                                        <td><?php echo $row['reg_no']; ?></td>
                                        <td><?php echo $row['stakeholders']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['complaint_name']; ?></td>
                                        <td><?php echo $row['assigned_to_name']; ?></td>
                                        <td><?php echo $row['assigned_date']; ?></td>
                                        <td>
                                            <?php 
                                                if ($row['status'] == 'open') {
                                                    echo '<button class="btn btn-success btn-sm">Open</button>';
                                                } else {
                                                    echo '<button class="btn btn-danger btn-sm">Close</button>';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <!-- Button to trigger document upload via email -->
                                            <a href="postqueries.php?id=<?= $row['id']; ?>&email=<?= $row['email']; ?>" class="btn btn-danger btn-sm">Post Queries</a>
                                        </td>
                                        <td>
                                            <!-- Button to trigger document upload via email -->
                                            <a href="closeticket.php?id=<?= $row['id']; ?>&email=<?= $row['email']; ?>" class="btn btn-primary btn-sm">Close Ticket</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script
