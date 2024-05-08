<?php
// Include the database connection file
include('includes/db_connection.php');

$sql = "SELECT id, name FROM hd_complaints WHERE status = 1"; // Fetching only active complaints
$result = mysqli_query($con, $sql);

// Check if query was successful
if ($result) {
    $complaints_options = "";
    while ($row = mysqli_fetch_assoc($result)) {
        $complaints_options .= "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
    }
} else {
    // Handle query error
    $complaints_options = "<option value='' disabled selected>No complaints available</option>";
}
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Add Complaints</title>
</head>
<body>
  
    <div class="container mt-5">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">

                    <div class="card-header">
                        <h4>Add Complaints 
                        <i class="fa fa-plus"></i> <a href="user.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Register Number</label>
                                
                                <input type="tel" name="reg_no" class="form-control">
                            </div>
                            <div class="mb-3">
                            <label for="stakeholders">Stakeholders</label>
                            <select name="stakeholders" id="stakeholders" required>
                                <option value="" disabled selected>Select your stakeholders</option>
                                <option value="University Students">University Students</option>
                                <option value="Research Scholars">Research Scholars</option>
                                <option value="Affiliated College">Affiliated College</option>
                            </select>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Phone Number</label>
                                <input type="tel" name="phn_no" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Nature of Complaints</label>
                                <select id="complaints" name="complaints" class="form-control" placeholder="Department...">
                                  <?php echo $complaints_options; ?>
                                 </select>
                            </div>
                            <div class="mb-3">
                                <label>Complaint Registered Date</label>
                                <input type="date" name="registered_date" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="complaint_copy">Complaint Copy (Image/PDF)</label>
                                <input type="file" name="complaint_copy" class="form-control" accept=".jpg, .jpeg, .png, .pdf">
                            </div>
                            <div class="mb-3">
							
                                <label for="message" class="control-label">Details</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea> <!-- Textarea for details -->						
					        </div>
    
                            <div class="mb-3">
                                <button type="submit" name="save_complaint" class="btn btn-primary">Save</button>
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
