<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger" role="alert">
            <?php
            $status = isset($_GET['status']) ? $_GET['status'] : 'unknown';
            switch ($status) {
                case 'error':
                    echo "Error occurred while sending email!";
                    break;
                case 'not_found':
                    echo "No complaint found with the provided ID!";
                    break;
                case 'invalid_request':
                    echo "Invalid request method or complaint ID not provided!";
                    break;
                default:
                    echo "An unknown error occurred!";
                    break;
            }
            ?>
        </div>
        <a href="upload_document.php" class="btn btn-primary">Back to Upload Document</a>
    </div>
</body>
</html>
