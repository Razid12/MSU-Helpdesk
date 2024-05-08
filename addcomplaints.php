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
    $complaintName = $_POST["complaintName"];
    $complaintStatus = $_POST["complaintStatus"];

    // Prepare and execute the query to insert data into the hd_complaints table
    $insertQuery = "INSERT INTO hd_complaints (name, status) VALUES ('$complaintName', '$complaintStatus')";
    if ($con->query($insertQuery) === TRUE) {
        // Insertion successful
        echo "Complaint added successfully.";
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
    <title>Add Complaints</title>
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
                    <h4 class="m-0">Add Complaints</h4>
                    <a href="complaints.php" class="btn btn-danger float-end">BACK</a>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="mb-3">
                            <label for="complaintName" class="form-label">Name</label>
                            <input type="text" name="complaintName" id="complaintName" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="complaintStatus" class="form-label">Status</label>
                            <select name="complaintStatus" id="complaintStatus" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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

