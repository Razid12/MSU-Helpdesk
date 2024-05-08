<?php
// Start the session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if(isset($_SESSION['id'])) {
    $user_id = $_SESSION['id']; // Assuming you store user ID in session
    // Fetch user details from the database based on $user_id if needed
    // Example: $user_details = fetch_user_details_from_database($user_id);
    // Then you can use $user_details['username'], $user_details['email'], etc. to display user information

    // Fetch admin details from the database based on $user_id
    // Example: $admin_details = fetch_admin_details_from_database($user_id);
    // Then you can use $admin_details['name'], $admin_details['email'], etc. to display admin information
}
?>
<!-- HTML code for the topmenu -->
<div class="topmenu">
    <div class="menubar">
        <a id="home" href="ticket.php"><i class="fa fa-home"></i> Home</a>
        <div class="dropdown">
            <button class="dropbtn"><i class="fa fa-exclamation-triangle"></i> Complaints</button>
            <div class="dropdown-content">
                <a href="assign.php">Assigned Complaints</a>
                <a href="colleges.php">Colleges</a>
                <a href="complaints.php">Complaints</a>
                <a href="department.php">Departments</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn"><i class="fa fa-exclamation-triangle"></i> Users</button>
            <div class="dropdown-content">
                
                <a href="staff.php">Staffs</a>
                <a href="userview.php">Users</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn"><i class="fa fa-exclamation-triangle"></i> Reports</button>
            <div class="dropdown-content">
                <a href="reports.php"><i class="fa fa-exclamation-triangle"></i>Monthwise Reports</a>
                <a href="reports1.php"><i class="fa fa-exclamation-triangle"></i>Department Reports</a> 
            </div>
        </div>
        
        <?php if(isset($_SESSION['id'])): ?>
            <div class="dropdown">
                <button class="dropbtn"><i class="fa fa-user"></i> <?php echo isset($admin_details['name']) ? $admin_details['name'] : 'Admin'; ?></button>
                <div class="dropdown-content">
                    <a href="#"><i class="fa fa-cog"></i> Settings</a>
                    <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php"><i class="fa fa-sign-in"></i> Login</a>
        <?php endif; ?>
    </div>
</div>

<script>
    // JavaScript to toggle the visibility of dropdown content
    document.querySelectorAll('.dropbtn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.nextElementSibling.classList.toggle('show');
        });
    });

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName('dropdown-content');
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>

<style>
    /* CSS for the topmenu */
    .topmenu {
        background-color: #00796B; /* Change the background color as needed */
        color: #ffffff; /* Change the text color as needed */
        padding: 10px;
        display: flex;
        justify-content: flex-start; /* Align items to the start of the container */
    }

    .topmenu a {
        color: #ffffff; /* Change the text color for links as needed */
        text-decoration: none;
        margin: 0 10px;
    }

    .topmenu a:hover {
        text-decoration: underline;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropbtn {
        background-color: #00796B;
        color: white;
        padding: 10px;
        font-size: 16px;
        border: none;
        cursor: pointer;
    }

    .dropbtn:hover {
        background-color: #005b4f;
    }

    /* Add this CSS to show the dropdown content */
    .dropdown-content.show {
        display: block;
    }
</style>
