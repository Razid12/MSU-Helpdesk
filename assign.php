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

include('time.php'); // Include the Time class file

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

        /* Reduce table size */
        .table-responsive {
            max-height: 70vh; /* Adjust as needed */
        }
    </style>

    <title>MSU Helpdesk Admin Panel</title>
</head>
<body>
    <div class="container-fluid mt-5"> <!-- Use container-fluid for full width -->

        <?php include('message.php'); ?>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h2><img src="images/logo.jpg" alt="MSU Logo" style="height: 50px; vertical-align: middle;">MSU Helpdesk Admin Panel</h2>    
                        <?php include('topmenu.php');?>
                        <h4>Admin's Assigned Complaints Details</h4>
                    </div>
                    <div class="card-body thread">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>S No</th>
                                        <th>Complaint ID</th>
                                        <th>Name</th>
                                        <th>Register Number</th>
                                        <th>Stakeholders</th>
                                        <th>Email</th>
                                        <th>Complaint</th>
                                        <th>Requesting Document</th>
                                        <th>Assigned To</th>
                                        <th>Assigned Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $query = "SELECT ac.id, hc.name AS complaint_name,ac.name, ac.reg_no, ac.stakeholders, ac.email, ac.complaints AS user_complaint, hu.name AS assigned_to_name, ac.assigned_date, ac.uniqid,ac.document_details, ac.status 
                                         FROM assigned_complaints ac 
                                         JOIN hd_complaints hc ON ac.complaints = hc.id 
                                         JOIN hd_users hu ON ac.assigned_to = hu.id";
                                    $query_run = mysqli_query($con, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        $count = 0;
                                        foreach ($query_run as $assigned_complaint) {
                                            $count++;
                                ?>
                                    <tr>
                                        <td><?= $count; ?></td>
                                        <td><?= $assigned_complaint['uniqid']; ?></td>
                                        <td><?= $assigned_complaint['name']; ?></td>
                                        <td><?= $assigned_complaint['reg_no']; ?></td>
                                        <td><?= $assigned_complaint['stakeholders']; ?></td>
                                        <td><?= $assigned_complaint['email']; ?></td>
                                        <td><?= $assigned_complaint['complaint_name']; ?></td>
                                        <td><?= $assigned_complaint['document_details']; ?></td>
                                        <td><?= $assigned_complaint['assigned_to_name']; ?></td>
                                        <td><?= $assigned_complaint['assigned_date']; ?></td>
                                        <td>
                                            <?php 
                                                if ($assigned_complaint['status'] == 'open') {
                                                    echo '<button class="btn btn-primary btn-sm">Open</button>';
                                                } else {
                                                    echo '<button class="btn btn-danger btn-sm">Closed</button>';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <!-- Add your action buttons here -->
                                            <!-- For example, if you want to authorize the complaint -->
                                            <form action="authorize1.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $assigned_complaint['id']; ?>">
                                                <button type="submit" class="btn btn-success btn-sm">Authorize</button>
                                            </form>
                                            <!-- You can add more actions as needed -->
                                        </td>
                                    </tr>
                                <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='12'>No Assigned Complaints Found</td></tr>";
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
