<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}
// Include the TCPDF library
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

// Include the database connection file
include('includes/db_connection.php');

// Initialize variables
$error = '';
$result = null;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_id'])) {
    // Get the complaint ID from the form
    $complaint_id = $_POST['complaint_id'];

    // Fetch data from assigned_complaints table for the selected complaint name
    $query = "SELECT ac.id, ac.uniqid AS `Complaint ID`, ac.name, ac.stakeholders, ac.email, ac.document_details, hc.name AS `Complaint Name`, 
                    u.name AS `Assigned To`, 
                    ac.assigned_date, ac.status
              FROM assigned_complaints ac
              LEFT JOIN hd_complaints hc ON ac.complaints = hc.id
              LEFT JOIN hd_users u ON ac.assigned_to = u.id
              WHERE hc.name = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Create new PDF document
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Assigned Complaint Report');
    $pdf->SetSubject('Assigned Complaint Report');

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 10);

    // Add data to PDF if result is available
    if ($result && $result->num_rows > 0) {
        $html = '<h1>Assigned Complaints Report for ' . $complaint_id . '</h1>';
        $html .= '<table border="1" cellpadding="10" cellspacing="0">';
        $html .= '<tr><th>ID</th><th>Complaint ID</th><th>Name</th><th>Stakeholders</th><th>Email</th><th>Upload Document Details</th>
                    <th>Complaint Name</th><th>Assigned To</th><th>Assigned Date</th><th>Status</th></tr>';
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            foreach ($row as $key => $value) {
                if ($key === 'Complaint Name') {
                    // Display complaint name instead of ID
                    $html .= '<td>' . $value . '</td>';
                } else {
                    $html .= '<td>' . $value . '</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        // Write HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
    } else {
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'No complaints found with the selected name.', 0, 1, 'C');
    }

    // Output PDF content
    $pdf_content = $pdf->Output('assigned_complaint_report.pdf', 'S');

    // If PDF content is generated, force download
    if (!empty($pdf_content)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="assigned_complaint_report.pdf"');
        echo $pdf_content;
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #c3e6cb, #ffffff);
            /* Dual-color tone background with pale green and white */
            overflow: hidden; /* Hide horizontal scrollbar */
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            /* Gradient background */
            background: linear-gradient(135deg, #c3e6cb, #ffffff);
            /* Dual-color tone background with pale green and white */
            overflow: hidden; /* Hide overflow */
            position: relative;
            z-index: 1;
        }
        .container:before {
            content: '';
            position: absolute;
            top: 0;
            left: -50px;
            width: 100px;
            height: 100%;
            background-color: #70c25c; /* Pale green */
            transform: skewX(-45deg);
            z-index: -1;
        }
        .container:after {
            content: '';
            position: absolute;
            top: 0;
            right: -50px;
            width: 100px;
            height: 100%;
            background-color: #70c25c; /* Pale green */
            transform: skewX(45deg);
            z-index: -1;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        form {
            text-align: center;
            margin-top: 20px;
        }
        label {
            font-weight: bold;
        }
        select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generate Assigned Complaint Report</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="complaint_id">Select Complaint Name:</label>
            <select name="complaint_id" id="complaint_id">
                <!-- Populate the dropdown with complaint names -->
                <?php
                // Fetch distinct complaint names from the database
                $query = "SELECT DISTINCT hc.name AS `Complaint Name` FROM assigned_complaints ac LEFT JOIN hd_complaints hc ON ac.complaints = hc.id";
                $result = $con->query($query);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['Complaint Name'] . '">' . $row['Complaint Name'] . '</option>';
                    }
                }
                ?>
            </select>
            <button type="submit">Generate Report</button>
        </form>
    </div>
</body>
</html>