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

// Rest of your code...
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
        /* Custom CSS for colleges.php */

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
            border-bottom: 2px solid #057fe8; /* Change color here */
            background-color: #057fe8;
            color: #fff;
        }

        /* Style for Add Staff button */
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
                    <?php include('topmenu.php'); ?>
                    
                </div>
               
                <div class="card-body">
                    
                    <h3 class="panel-title">Staff and Admin List</h3>
                    <div class="add-staff-btn-container"> <!-- Container for Add Staff button -->
                        <a href="addstaff.php" class="btn btn-warning btn-sm btn-right">Add Staff</a>
                    </div>
                    <table id="listStaff" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch records from hd_college table
                            $query = "SELECT * FROM hd_users";
                            $result = mysqli_query($con, $query);

                            // Check if records exist
                            if (mysqli_num_rows($result) > 0) {
                                // Output data of each row
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . ($row['user_type'] == 'admin' ? '<span class="badge bg-success">Admin</span>' : '<span class="badge bg-primary">Staff</span>') . "</td>";

                                    echo "<td>" . ($row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>') . "</td>";
                                    echo "<td>" . $row['create_date'] . "</td>";
                                    echo "<td>
                                            <div class='btn-group'>
                                                <a href='editstaff.php?id=" . $row['id'] . "' class='btn btn-sm btn-info'>Edit</a>
                                                <a href='deletestaff.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No colleges found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- College Modal -->


<?php include('inc/footer.php'); ?>
</body>
</html>
