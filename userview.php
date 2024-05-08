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
    /* Custom CSS for user details page */

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
        padding: 0.3rem; /* Decrease padding */
        vertical-align: top;
        border-top: 1px solid #dee2e6;
        font-size: 13px; /* Decrease font size */
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #057fe8; /* Change color here */
        background-color: #057fe8;
        color: #fff;
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
                    <h3 class="panel-title">User Details</h3>
                    <div class="table-responsive"> <!-- Added this div for responsiveness -->
                        <table id="userDetails" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Reg_No</th>
                                    <th>Stakeholders</th>
                                    <th>College Name</th>
                                    <th>Department</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    
                                    <th>Contact No</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch all user details from database
                                $query = "SELECT * FROM users";
                                $result = $con->query($query);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['reg_no'] . "</td>";
                                        echo "<td>" . $row['stakeholders'] . "</td>";
                                        echo "<td>" . $row['collegename'] . "</td>";
                                        echo "<td>" . $row['department'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . $row['password'] . "</td>";
                                        
                                        echo "<td>" . $row['contact_no'] . "</td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "<td>
                                            <div class='d-flex'>
                                                <a href='user_register.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning me-2'>Register</a>
                                                <a href='user_login.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Login</a>
                                            </div>
                                        </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='12'>No user details found</td></tr>";
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

<?php include('inc/footer.php'); ?>
</body>
</html>
