<?php
include('includes/db_connection.php');

// Function to validate register number
function validateRegisterNumber($reg_no) {
    // Check if register number contains 13 to 15 digits
    return preg_match('/^\d{13,15}$/', $reg_no);
}

// Function to validate phone number
function validatePhoneNumber($phone) {
    // Check if phone number contains exactly 10 digits
    return preg_match('/^\d{10}$/', $phone);
}

// Fetch college options
$sql = "SELECT id, name FROM hd_college WHERE status = 1"; // Fetching only active colleges
$result_colleges = mysqli_query($con, $sql);

// Check if query was successful
if ($result_colleges) {
    $college_options = "";
    while ($row = mysqli_fetch_assoc($result_colleges)) {
        $college_options .= "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
    }
} else {
    // Handle query error
    $college_options = "<option value='' disabled selected>No colleges available</option>";
}

// Fetch department options
$sql_departments = "SELECT id, name FROM hd_department WHERE status = 1"; // Fetching only active departments
$result_departments = mysqli_query($con, $sql_departments);

// Check if department query was successful
if ($result_departments) {
    $department_options = "";
    while ($row = mysqli_fetch_assoc($result_departments)) {
        $department_options .= "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
    }
} else {
    // Handle department query error
    $department_options = "<option value='' disabled selected>No departments available</option>";
}

// Handle form submission
if(isset($_POST['submit'])) {
    // Retrieve form data
    $name = $_POST["name"];
    $reg_no = $_POST["reg_no"];
    $stakeholders = $_POST["stakeholders"];
    $collegename = $_POST["collegename"];
    $department = $_POST["department"];
    $email = $_POST["email"];
    $password = $_POST["password"]; // Password is stored as plain text
    $confirmpassword = $_POST["confirmpassword"];
    $contact_no = $_POST["contact_no"];
    $status = $_POST["status"];

    // Validate register number and phone number
    if (!validateRegisterNumber($reg_no)) {
        echo "<script>alert('Register number should contain 13 to 15 digits');</script>";
        exit;
    }

    if (!validatePhoneNumber($contact_no)) {
        echo "<script>alert('Phone number should contain exactly 10 digits');</script>";
        exit;
    }

    // Check if passwords match
    if ($password !== $confirmpassword) {
        echo "<script>alert('Passwords do not match');</script>";
        exit;
    }

    // Check if email already exists
    $ret = mysqli_query($con, "SELECT name FROM users WHERE name='$name'");
    $result = mysqli_fetch_array($ret);

    if($result > 0) {
        echo "<script>alert('This username already associated with another account');</script>";
    } else {
        // Insert user data into database without hashing password
        $query = mysqli_query($con, "INSERT INTO users(name, reg_no, Stakeholders, CollegeName, Department, Email, Password, contact_no, Status) 
                                    VALUES ('$name', '$reg_no', '$stakeholders', '$collegename', '$department', '$email', '$password', '$contact_no', '$status')");
        if ($query) {
            echo "<script>alert('You have successfully registered');</script>";
            echo "<script>window.location.href ='user_login.php'</script>";
        } else {
            echo "<script>alert('Something Went Wrong. Please try again');</script>";
            echo "<script>window.location.href ='user.php'</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Your existing CSS styles here */
        body {
            background: linear-gradient(135deg, #4a90e2 50%, #75c7ff 50%);
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
            height: 100vh;
            overflow-y: auto;
            flex-direction: column;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.8); /* White with slight transparency */
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Shadow effect */
        }
        form {
            background-color: transparent;
            width: 100%;
        }
        label,
        input,
        select,
        button {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-sizing: border-box;
            display: inline-block;
            border: none;
            background-color: rgba(255, 255, 255, 0.8); /* White with slight transparency */
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333; /* Dark gray */
        }
        button {
            background-color: #ff6f61; /* Coral */
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #ff4f40; /* Lighter coral on hover */
        }
        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
            width: 100%;
        }
        .logo {
            margin-bottom: 20px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 20px;
            transition-duration: 0.4s;
        }
        .btn:hover {
            background-color: #45a049;
            color: white;
        }
        h3.form-title {
            font-family: 'Times New Roman', Times, serif;
            color: #333; /* Dark gray */
            margin-top: 0; /* Remove default margin */
        }
    </style>
    <title>Registration Form</title>
</head>
<body>

<div class="container">
    
    <form method="post" action="user_register.php">
        <h2 align="center"><img src="images/logo.jpg" alt="MSU Logo" style="height: 50px; vertical-align: middle;">MSU Helpdesk </h2>	
        <h3 style="text-align: center;">User Registration</h3>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your Name"required>

        <label for="reg_no">Register Number</label>
        <input type="text" id="reg_no" name="reg_no" placeholder="Enter your Register Number" required>

        <label for="stakeholders">Stakeholders</label>
        <select name="stakeholders" id="stakeholders" required>
            <option value="" disabled selected>Select your stakeholders</option>
            <option value="University Students">University Students</option>
            <option value="Research Scholars">Research Scholars</option>
            <option value="Affiliated College">Affiliated College</option>
        </select>
        <label for="collegename">College Name</label>
        <select id="collegename" name="collegename" class="form-control" required>
            <?php echo $college_options; ?>
        </select>
            				
        <label for="department">Department</label>
        <select name="department" id="department" required>
            <?php echo $department_options; ?>
        </select>

        <label for="email">Email</label>
        <input type="email" id="email" name="email"placeholder="Enter your Email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="confirmpassword">Confirm Password</label>
        <input type="password" id="confirmpassword" name="confirmpassword" required>

        <label for="contact_no">Contact Number</label>
        <input type="text" id="contact_no" name="contact_no" placeholder="Enter your Contact Number" required>

        <label for="status">Current Status</label>
        <select name="status" id="status"required>
			    <option value="Studying">Studying</option>				
				<option value="Passed out">Passed Out</option>	
		</select>
        
		<div class="p-t-20">
        <button class="btn btn--radius btn--green" type="submit" name="submit">Submit</button>
        </div>
        <br>
            <a href="user_login.php" style="color: red">Already have an account? Signin</a>
    </form>
   
</div>
<script src="/javascript/form_validation.js"></script>
</body>
</html>
