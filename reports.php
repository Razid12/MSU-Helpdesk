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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate month
    if (!isset($_POST['month']) || !is_numeric($_POST['month']) || $_POST['month'] < 1 || $_POST['month'] > 12) {
        $error = 'Invalid month selected.';
    } else {
        $month = intval($_POST['month']);
        // Fetch data from assigned_complaints table for the selected month
        $query = "SELECT ac.id, ac.uniqid AS `Complaint ID`, ac.name, ac.stakeholders, ac.email, ac.document_details, hc.name AS `Complaint Name`, 
                    u.name AS `Assigned To`, 
                    ac.assigned_date, ac.status
                  FROM assigned_complaints ac
                  LEFT JOIN hd_complaints hc ON ac.complaints = hc.id
                  LEFT JOIN hd_users u ON ac.assigned_to = u.id
                  WHERE MONTH(ac.assigned_date) = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $month);
        $stmt->execute();
        $result = $stmt->get_result();

        // Create new PDF document with custom page size (e.g., Letter size: 215.9 mm x 279.4 mm)
        $pdf = new TCPDF('P', 'mm', array(215.9, 279.4), true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Month-wise Assigned Complaints Report');
        $pdf->SetSubject('Month-wise Assigned Complaints Report');

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 10);

        // Add data to PDF if result is available
        if ($result && $result->num_rows > 0) {
            $html = '<h1>Month-wise Assigned Complaints Report - Month ' . date('F', mktime(0, 0, 0, $month, 1)) . '</h1>';
            $html .= '<table border="1" cellpadding="10" cellspacing="0">';
            $html .= '<tr><th>ID</th><th>Complaint ID</th><th>Name</th><th>Stakeholders</th><th>Email</th><th>Document Details</th>
                        <th>Complaint Name</th><th>Assigned To</th><th>Assigned Date</th><th>Status</th></tr>';
            while ($row = $result->fetch_assoc()) {
                $html .= '<tr>';
                foreach ($row as $value) {
                    $html .= '<td>' . $value . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</table>';

            // Write HTML content
            $pdf->writeHTML($html, true, false, true, false, '');
        } else {
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'No records found for the selected month.', 0, 1, 'C');
        }

        // Output PDF content
        $pdf_content = $pdf->Output('assigned_complaints_report.pdf', 'S');

        // If PDF content is generated, force download
        if (!empty($pdf_content)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="assigned_complaints_report.pdf"');
            echo $pdf_content;
            exit;
        }
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
        <h1>Generate Month-wise Assigned Complaints Report</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="month">Select Month:</label>
            <select name="month" id="month">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit">Generate Report</button>
        </form>
    </div>
</body>
</html>
