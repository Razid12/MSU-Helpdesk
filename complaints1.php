<?php
// Check if a session is already active
if (session_status() === PHP_SESSION_NONE) {
    // If not, start a new session
    session_start();
}
// Check if the user is not logged in
if (!isset($_SESSION['id'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit(); // Terminate script execution after redirection
}
// Include the database connection file
include('includes/db_connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Custom CSS for complaints.php */

        /* Style for topmenu */
        .topmenu {
            background-color: #00796B;
            color: #ffffff;
            padding: 10px;
            display: flex;
            justify-content: flex-start;
        }

        .topmenu a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 10px;
        }

        .topmenu a:hover {
            text-decoration: underline;
        }

        /* Style for table */
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color:  #057fe8;
            color: #fff;
        }

        /* Style for modal */
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 0.3rem;
            outline: 0;
        }

        .modal-header {
            padding: 15px;
            border-bottom: 1px solid #e5e5e5;
            background-color: #00796B;
            color: #fff;
        }

        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: 10px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            padding: 15px;
            border-top: 1px solid #e5e5e5;
            background-color: #f5f5f5;
        }

        /* Style for Add New button container */
        .add-staff-btn-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px; /* Add margin to separate button from table */
        }

        /* Style to align button to the right */
        .btn-right {
            margin-left: auto;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2><img src="images/logo.jpg" alt="MSU Logo" style="height: 50px; vertical-align: middle;">MSU Helpdesk</h2>
                    <?php include('menus.php'); ?>
                </div>
                <div class="card-body">
                    <h3 class="panel-title">Complaints List</h3>
                    <div class="add-staff-btn-container">
                        <a href="addcomplaints.php" class="btn btn-warning btn-sm btn-right">Add Complaint</a>
                    </div>
                    <table id="listComplaints" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch records from hd_complaints table
                            $query = "SELECT * FROM hd_complaints";
                            $result = mysqli_query($con, $query);

                            // Check if records exist
                            if (mysqli_num_rows($result) > 0) {
                                // Output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . ($row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>') . "</td>";
                                    echo "<td>
                                    <a href='editcomplaint.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>
                                    <a href='deletecomplaint.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                  </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No complaints found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complaint Modal -->


<?php include('inc/footer.php'); ?>
</body>
</html>
