<?php
// Check if a session is already active
if (session_status() === PHP_SESSION_NONE) {
    // If not, start a new session
    session_start();
}

// Include the database connection file
include('includes/db_connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $user_type = $_POST["user_type"];
    $status = $_POST["status"];
    $password = $_POST["newPassword"];
    $create_date = date('Y-m-d H:i:s'); // Capture current date and time

    // Prepare and execute the query to insert data into the hd_users table
    $insertQuery = "INSERT INTO hd_users (name, email, user_type, status, password, create_date) VALUES ('$name', '$email', '$user_type', '$status', '$password', '$create_date')";
    if ($con->query($insertQuery) === TRUE) {
        // Insertion successful
        echo "Staff member added successfully.";
    } else {
        // Insertion failed
        echo "Error: " . $insertQuery . "<br>" . $con->error;
    }
}

// Close the database connection when done
$con->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff Member</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS for the form */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .card-header {
            background-color: #00796B;
            color: #ffffff;
            padding: 10px;
        }

        .card-header a {
            color: #ffffff;
            text-decoration: none;
        }

        .card-header a:hover {
            text-decoration: underline;
        }

        .card-body {
            padding: 20px;
        }

        .form-label {
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 0;
            border-color: #ccc;
        }

        .btn-primary {
            border-radius: 0;
            padding: 10px 20px;
            cursor: pointer;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="m-0">Add Staff Member</h4>
                    <a href="staff.php" class="btn btn-danger float-end">BACK</a>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
						
						<div class="mb-3">
    						<label for="user_type" class="form-label">User Type</label>
    						<select name="user_type" id="user_type" class="form-control" required>
      							  <option value="admin">Admin</option>
      							  <option value="staff">Staff</option>
    						</select>
						</div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" name="newPassword" id="newPassword" class="form-control">
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="save" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
