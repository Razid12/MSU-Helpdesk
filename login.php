<?php
// Check if a session is already active
if (session_status() === PHP_SESSION_NONE) {
    // If not, start a new session
    session_start();
}

// Include the database connection file
include('includes/db_connection.php');

// Initialize the error message variable
$errorMsg = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if the username and password exist in the database
    $checkQuery = "SELECT * FROM hd_users WHERE email = '$email' AND password = '$password'";
    $checkResult = $con->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Valid credentials, retrieve user_id and user_type
        $row = $checkResult->fetch_assoc();
        $userId = $row['id'];
        $userType = $row['user_type'];

        // Check if the user is an admin
        if ($userType === 'admin') {
            // Store user_id in the session
            $_SESSION['id'] = $userId;

            // Redirect to the admin panel page where complaint details are displayed
            header("Location: ticket.php");
            exit();
        } elseif ($userType === 'staff') {
            // Store user_id in the session
            $_SESSION['id'] = $userId;

            // Redirect to the staff panel page
            header("Location: staff_panel.php");
            exit();
        } else {
            // User is not authorized, display an error message
            $errorMsg = 'You are not authorized to access the system.';
        }
    } else {
        // Invalid credentials, set error message
        $errorMsg = 'Invalid Username or password. Please check.';
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
    <title>Helpdesk System with PHP & MySQL</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #00796B, #4CAF50);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0; /* Remove default margin */
        }

        .container.login-container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 100%; /* Take full width */
            max-width: 400px; /* Limit maximum width */
        }

        .panel-heading {
            background-color: #00796B;
            color: white;
            border-radius: 5px 5px 0 0;
        }

        .panel-body {
            padding-top: 30px;
        }

        .form-control {
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .btn-success {
            background-color: #00796B;
            border-color: #00796B;
            color: white;
        }

        .btn-success:hover {
            background-color: #005b4f;
            border-color: #005b4f;
        }

        #login-alert {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container login-container">    
        <h2 class="text-center mb-4"><img src="images/logo.jpg" alt="MSU Logo" style="height: 50px; vertical-align: middle;"> MSU Helpdesk</h2>    
        <div class="row justify-content-center"> <!-- Center the login form horizontally -->
            <div class="col-md-12"> <!-- Use full width -->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Admin & Staff Login</div>                        
                    </div> 
                    <div class="panel-body">
                        <?php if ($errorMsg != '') { ?>
                            <div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $errorMsg; ?></div>                            
                        <?php } ?>
                        <form id="loginform" class="form-horizontal" role="form" method="POST" action="">   
                            <!-- Email input -->
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" style="background:white;" required>
                                    </div>
                                </div>
                            </div>
                            <!-- Password input -->
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit button -->
                            <div class="form-group" style="margin-top:10px">
                                <div class="col-sm-12 text-center"> <!-- Center the submit button -->
                                    <input type="submit" name="login" value="Login" class="btn btn-success">
                                </div>
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
