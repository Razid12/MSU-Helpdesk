<?php
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

    <title>User's Complaints View</title>
</head>
<body>

    <div class="container mt-5">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>User's Complaints View Details
                            <a href="user.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT tc.*, hc.name AS complaint_name 
                                      FROM ticket_complaints tc 
                                      INNER JOIN hd_complaints hc ON tc.complaints = hc.id
                                      WHERE tc.id='$id'";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $ticket_complaints = mysqli_fetch_array($query_run);
                                ?>
                                    <div class="mb-3">
                                        <label>S No</label>
                                        <p class="form-control">
                                            <?=$ticket_complaints['id'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <p class="form-control">
                                            <?=$ticket_complaints['name'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Reg_no</label>
                                        <p class="form-control">
                                            <?=$ticket_complaints['reg_no'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Stakeholders</label>
                                        <p class="form-control">
                                            <?=$ticket_complaints['stakeholders'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <p class="form-control">
                                            <?=$ticket_complaints['email'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Complaints</label>
                                        <p class="form-control">
                                            <?=$ticket_complaints['complaint_name'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Details</label>
                                        <p class="form-control">
                                            <?=$ticket_complaints['Details'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Complaint Registered Date</label>
                                        <p class="form-control">
                                            <?=$ticket_complaints['complaint_date'];?>
                                        </p>
                                    </div>
                                    <!-- Add other fields here -->
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
