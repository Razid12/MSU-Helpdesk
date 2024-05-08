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
    $name = $_POST["name"];
    $password = $_POST["password"];

    // Check if the username and password exist in the database
    $checkQuery = "SELECT * FROM users WHERE name = '$name' AND password = '$password'";
    $checkResult = $con->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Valid credentials, retrieve user_id and store it in the session
        $row = $checkResult->fetch_assoc();
        $Id = $row['id'];
        $_SESSION['id'] = $Id;

        // Redirect to the user dashboard page
        header("Location: user.php");

        exit();
    } else {
        // Invalid credentials, set error message
        $errorMsg = 'Invalid Username or password. Please check.';
    }
}

// Close the database connection when done
$con->close();
?>


<!-- HTML code for the login form with the provided CSS layout -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: linear-gradient(to right, #654ea3, #eaafc8);
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            display: flex;
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            flex-direction: column;
            align-items: center;
        }
        .logo {
            margin-bottom: 20px;
        }
        form {
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input {
            width: calc(100% - 16px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            display: inline-block;
        }
        button {
            width: 100%;
            background-color: #ff6b6b;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #ff4757;
        }
        .message {
            color: #ff6b6b;
            margin-top: 10px;
            text-align: center;
        }
    </style>
    <title>User Login</title>
</head>
<body>

<div class="container">
    
    <form method="post">
    <h2 align="center"><img src="images/logo.jpg" alt="MSU Logo" style="height: 50px; vertical-align: middle;">MSU Helpdesk </h2>	

	<h3 style="text-align: center;">User Login</h3>
        <div class="message"><?php echo $errorMsg; ?></div>
        <label for="name">Username</label>
        <input type="text" id="name" name="name" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" style="background-color: blue; color: white;">Login</button>
        
    </form>
</div>


</body>
</html>
