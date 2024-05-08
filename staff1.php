<?php
session_start();
include('includes/db_connection.php');

// Check if user is logged in
if(!isset($_SESSION['id'])) {
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

        /* Adjust image size */
        .img-fluid {
            max-width: 100px; /* Set maximum width for images */
            height: auto; /* Maintain aspect ratio */
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
                        <?php include('menus.php');?>
                        <h4>User's Uploaded Complaints Details</h4>
                    </div>
                    <div class="card-body thread">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ticket ID</th>
                                    <th>User Name</th>
                                    <th>Complaint Name</th>
                                    <th>Complaint Details</th>
                                    <th>Uploaded Document</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $query = "SELECT tc.id, tc.uniqid, u.name AS user_name, hc.name AS complaint_name, tc.Details, tc.document_path 
                                          FROM ticket_complaints tc 
                                          INNER JOIN users u ON tc.user_id = u.id
                                          INNER JOIN hd_complaints hc ON tc.complaints = hc.id";
                                $query_run = mysqli_query($con, $query);

                                if(mysqli_num_rows($query_run) > 0)
                                {
                                    while($row = mysqli_fetch_assoc($query_run)) {
                                        if(!empty($row['document_path'])) {
                                            ?>
                                            <tr>
                                                <td><?= $row['id']; ?></td>
                                                <td><?= $row['uniqid']; ?></td>
                                                <td><?= $row['user_name']; ?></td>
                                                <td><?= $row['complaint_name']; ?></td>
                                                <td><?= $row['Details']; ?></td>
                                                <td>
                                                    <?php if(!empty($row['document_path'])): ?>
                                                        <img src="<?= $row['document_path']; ?>" class="img-fluid" alt="Uploaded Document">
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if(!empty($row['document_path'])): ?>
                                                        <a href="<?= $row['document_path']; ?>" class="btn btn-primary" download>Download </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                else
                                {
                                    echo "<tr><td colspan='7'>No Record Found</td></tr>";
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
