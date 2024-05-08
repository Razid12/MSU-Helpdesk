<?php
session_start();
include('includes/db_connection.php');

// Check if user is logged in
if(!isset($_SESSION['id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['id']; // Assuming you store user ID in session

include('time.php'); // Include the Time class file

// Instantiate Time class
$timeObject = new Time();

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
            font-size: 13px; /* Adjust the font size as per your requirement */
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

    <title>MSU Helpdesk</title>
</head>
<body>
    <div class="container-fluid mt-5"> <!-- Use container-fluid for full width -->

        <?php include('message.php'); ?>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h2><img src="images/logo.jpg" alt="MSU Logo" style="height: 50px; vertical-align: middle;">MSU Helpdesk</h2>    
                        <?php include('menu.php');?>
                        <h4>User's Complaints Details
                            <a href="addticket.php" class="btn btn-primary float-end">Create a Ticket</a>
                        </h4>
                    </div>
                    <div class="card-body thread">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>S No</th>
                                    <th>Ticket ID</th> <!-- Add this column header -->
                                    <th>Name</th>
                                    <th>Register Number</th>
                                    <th>Stakeholders</th>
                                    <th>Email</th>
                                    <th>Nature of Complaints</th>
                                    <th>Details</th>
                                    <th>Created By</th>                 
                                    <th>Created At</th> <!-- Changed to regular table header -->
                                    <th>View</th>
                                    <th>Update</th>
                                    <th>Delete</th>  <!-- Add this column header for actions -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $query = "SELECT * FROM ticket_complaints WHERE user_id = $user_id";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $count = 0;
                                        foreach($query_run as $ticket_complaints)
                                        {
                                            $count++;
                                            ?>
                                            <tr>
                                                <td><?= $count; ?></td>
                                                <td><?= $ticket_complaints['uniqid']; ?></td> <!-- Display the uniqid -->
                                                <td><?= $ticket_complaints['name']; ?></td>
                                                <td><?= $ticket_complaints['reg_no']; ?></td>
                                                <td><?= $ticket_complaints['stakeholders']; ?></td>
                                                <td><?= $ticket_complaints['email']; ?></td>
                                                <td><?= $ticket_complaints['complaints']; ?></td>
                                                <td><?= $ticket_complaints['Details']; ?></td>
                                                <td><?= $ticket_complaints['created_by']; ?></td>
                                                <td><?= isset($ticket_complaints['created_at']) ? $timeObject->ago($ticket_complaints['created_at']) : ''; ?></td> <!-- Using Time class to display human-readable time -->
                                                <td>
                                                 <a href="userscomplaint-view.php?id=<?= $ticket_complaints['id']; ?>" class="btn btn-success btn-sm">View Ticket</a>
                                                </td>
                                                <td>
                                                  <a href="userscomplaint-edit.php?id=<?= $ticket_complaints['id']; ?>" class="btn btn-success btn-sm">Edit Ticket</a>
                                                </td>
                                                <td>
                                                   <a href="userscomplaint-delete.php?id=<?= $ticket_complaints['id']; ?>" class="btn btn-danger btn-sm">Delete Ticket</a>
                                                </td>

                                            </tr>
                                            
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> No Record Found </h5>";
                                    }
                                ?>
                                
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
