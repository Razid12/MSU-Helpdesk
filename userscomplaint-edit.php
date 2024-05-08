<?php
session_start();
include('includes/db_connection.php');
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Users Complaints Edit</title>
</head>
<body>
  
    <div class="container mt-5">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    <h2><img src="images/logo.jpg" alt="MSU Logo" style="height: 50px; vertical-align: middle;">MSU Helpdesk</h2>   
                        <h4>Users Complaints Edit
                            <a href="user.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM ticket_complaints WHERE id='$id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $ticket_complaints = mysqli_fetch_array($query_run);
                                ?>
                                <form action="code.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label>Ticket_id</label>
                                        <input type="text" name="complaint_id" value="<?= isset($ticket_complaints['id']) ? $ticket_complaints['id'] : '' ?>" class="form-control" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name" value="<?= isset($ticket_complaints['name']) ? $ticket_complaints['name'] : '' ?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Registration Number</label>
                                        <input type="text" name="reg_no" value="<?= isset($ticket_complaints['reg_no']) ? $ticket_complaints['reg_no'] : '' ?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" value="<?= isset($ticket_complaints['email']) ? $ticket_complaints['email'] : '' ?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Upload Document (Image/PDF)</label>
                                        <input type="file" name="document" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="update_complaint" class="btn btn-primary">
                                            Update Complaint
                                        </button>
                                    </div>

                                </form>
                                <?php
                            }
                            else
                            {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
