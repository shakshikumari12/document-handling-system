<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "letters";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$message = "";

// Directory to store uploaded files
$uploadDir = 'uploads/';

// Ensure the uploads directory exists and is writable
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Base URL for the uploaded files
$baseURL = 'http://localhost/loginpage/uploads/';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $letter_no = $_POST['letter_no'];
    $letter_by = $_POST['letter_by'];
    $recepiet_no = $_POST['recepiet_no'];
    $dealt_by = $_POST['dealt_by'];
    $status = $_POST['status'];
    $letter_type = $_POST['letter_type'];

    // Initialize file path variables with empty strings
    $file_path = "";
    $issued_file = "";
    $midway_activity = "";
    $final_reply = "";

    // Handle file upload
    // Handle file upload
$file = $_FILES['file'];
if (isset($file) && $file['error'] == 0) {
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];

    // Check if the file is an allowed type
    if (in_array($fileType, $allowedTypes)) {
        $uniqueFileName = uniqid() . '-' . $date . '.' . $fileType;
        $uniqueFilePath = $uploadDir . $uniqueFileName;
        if (move_uploaded_file($file['tmp_name'], $uniqueFilePath)) {
            // File uploaded successfully
            $file_path = $uniqueFileName; // Store the filename with the date
        } else {
            $message = "Error uploading file.";
        }
    } else {
        $message = "Only PDF and image files are allowed.";
    }
}


    // Check if the letter number already exists
    $stmt = $conn->prepare("SELECT letter_no, issued_file, midway_activity, final_reply FROM letters WHERE letter_no = ?");
    $stmt->bind_param("s", $letter_no);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($existing_letter_no, $issued_file, $midway_activity, $final_reply);
        $stmt->fetch();
        // Append new file path to existing paths if applicable
        if ($letter_type == "issued") {
            $issued_file = $issued_file? $issued_file. ','. $file_path : $file_path;
        } elseif ($letter_type == "midway_activity") {
            $midway_activity = $midway_activity? $midway_activity. ','. $file_path : $file_path;
        } else {
            $final_reply = $final_reply? $final_reply. ','. $file_path : $file_path;
        }

        // Update the existing record
        $update_stmt = $conn->prepare("UPDATE letters SET date = ?, letter_by = ?, recepiet_no = ?, dealt_by = ?, issued_file = ?, midway_activity = ?, final_reply = ?, status = ? WHERE letter_no = ?");
        if ($update_stmt) {
            $update_stmt->bind_param("sssssssss", $date, $letter_by, $recepiet_no, $dealt_by, $issued_file, $midway_activity, $final_reply, $status, $letter_no);
            if ($update_stmt->execute()) {
                $message = "Record updated successfully";
            } else {
                $message = "Error updating record: " . $update_stmt->error;
            }
            $update_stmt->close();
        } else {
            $message = "Error preparing update statement: " . $conn->error;
        }
    } else {
        // Insert new record if not exists
        $insert_stmt = $conn->prepare("INSERT INTO letters (date, letter_no, letter_by, recepiet_no, dealt_by, issued_file, midway_activity, final_reply, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($insert_stmt) {
            // Determine which field to fill based on letter type
            if ($letter_type == "issued") {
                $issued_file = $file_path;
            } elseif ($letter_type == "midway_activity") {
                $midway_activity = $file_path;
            } elseif ($letter_type == "final_reply") {
                $final_reply = $file_path;
            }

            $insert_stmt->bind_param("sssssssss", $date, $letter_no, $letter_by, $recepiet_no, $dealt_by, $issued_file, $midway_activity, $final_reply, $status);
            if ($insert_stmt->execute()) {
                $message = "New record created successfully";
            } else {
                $message = "Error executing query: " . $insert_stmt->error;
            }
            $insert_stmt->close();
        } else {
            $message = "Error preparing insert statement: " . $conn->error;
        }
    }
    $stmt->close();
}

// Fetch data from database
$sql = "SELECT * FROM letters";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form and Display Data</title>
    <link rel="stylesheet" href="css/formstyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        
        display: flex;
        justify-content: center;
        align-items: flex-start;
        flex-direction: column;
    }
    .form-container {
        background-color: #ffffff;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 500px; /* Set a max-width to make the form container smaller */
        margin-left: auto;
        margin-right: auto;
    }
    .table-container {
        background-color: #ffffff;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        width: 96%; /* Make table container full width */
    }
    h2 {
        text-align: center;
    }
    .form-group {
        margin-bottom: 10px; /* Reduce the margin between form groups */
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    .form-group input, .form-group select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }
    .btn-save {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #007BFF;
        color: #ffffff;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }
    .btn-save:hover {
        background-color: #0056b3;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 18px;
        text-align: left;
    }
    table, th, td {
        border: 1px solid #dddddd;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
    }
    .status-pending {
        background-color: red;
        color: white;
        text-align: center;
    }
    .status-completed {
        background-color: green;
        color: white;
        text-align: center;
    }
</style>
</head>
<body>
    <div class="form-container">
        <h2>Enter Letter Details</h2>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="letter_no">Letter No</label>
                <input type="text" id="letter_no" name="letter_no" required>
            </div>
            <div class="form-group">
                <label for="letter_by">Letter By Whom</label>
                <input type="text" id="letter_by" name="letter_by" required>
            </div>
            <div class="form-group">
                <label for="recepiet_no">Receipt No</label>
                <input type="text" id="recepiet_no" name="recepiet_no" required>
            </div>
            <div class="form-group">
                <label for="dealt_by">Dealt By</label>
                <select id="dealt_by" name="dealt_by" required>
               
                    <option value="Bharat Bhuskar">Bharat Bhuskar</option>
                    <option value="Ravi Prakash">ravi prakash</option>
                    <option value="Mithilesh Kr Choudhary">Mithilesh Kr Choudhary</option>
                    <option value="Madhuri Kumari">Madhuri Kumari</option>
                    <option value="Kumar Ravi">Kumar Ravi</option>
                    <option value="Anisha Kumari">Anisha Kumari</option>
                    <option value="Saket Kumar">Saket Kumar</option>
                    </select>
            </div>
            <div class="form-group">
                <label for="letter_type">Letter Type</label>
                <select id="letter_type" name="letter_type" required>
                    <option value="issued">Issued</option>
                    <option value="midway_activity">Midway Activity</option>
                    <option value="final_reply">Final Reply</option>
                </select>
            </div>
            <div class="form-group">
                <label for="file">File</label>
                <input type="file" id="file" name="file" accept="application/pdf" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="btn-save">Save</button>
        </form>
    </div>

    <div class="table-container">
        <h2>Letters Data</h2>
        <table>
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Letter No</th>
        <th>Letter By</th>
        <th>Receipt No</th>
        <th>Dealt By</th>
        <th>Midway Activity</th>
        <th>Final Reply</th>
        <th>Status</th>
    </tr>
    <?php
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $issuedPaths = isset($row['issued_file']) ? explode(',', $row['issued_file']) : [];
            $midwayPaths = isset($row['midway_activity']) ? explode(',', $row['midway_activity']) : [];
            $finalPaths = isset($row['final_reply']) ? explode(',', $row['final_reply']) : [];
            $statusClass = !empty($finalPaths) && $finalPaths[0] != '' ? 'status-completed' : 'status-pending';

            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['date']}</td>
                    <td>";
            if (!empty($issuedPaths) && $issuedPaths[0] != '') {
                $fileUrl = $baseURL . $issuedPaths[0];
                $fileName = basename($issuedPaths[0]);
                $fileDate = substr($fileName, strpos($fileName, '-') + 1, 10);
                if (file_exists($uploadDir . $issuedPaths[0])) {
                    echo "<a href='$fileUrl' target='_blank'>{$row['letter_no']}   (Uploaded on: $fileDate)</a><br>";
                } else {
                    echo $row['letter_no'];
                }
            } else {
                echo $row['letter_no'];
            }
            echo "</td>
                    <td>{$row['letter_by']}</td>
                    <td>{$row['recepiet_no']}</td>
                    <td>{$row['dealt_by']}</td>
                    <td>";
            if (!empty($midwayPaths)) {
                foreach ($midwayPaths as $filePath) {
                    if ($filePath != '') {
                        $fileUrl = $baseURL . $filePath;
                        $fileName = basename($filePath);
                        $fileDate = substr($fileName, strpos($fileName, '-') + 1, 10);
                        if (file_exists($uploadDir . $filePath)) {
                            echo "<a href='$fileUrl' target='_blank'><i class='fas fa-file-pdf'></i> $fileName  (Uploaded on: $fileDate)</a><br>";
                        }
                    }
                }
            }
            echo "</td><td>";
            if (!empty($finalPaths)) {
                foreach ($finalPaths as $filePath) {
                    if ($filePath != '') {
                        $fileUrl = $baseURL . $filePath;
                        $fileName = basename($filePath);
                        $fileDate = substr($fileName, strpos($fileName, '-') + 1, 10);
                        if (file_exists($uploadDir . $filePath)) {
                            echo "<a href='$fileUrl' target='_blank'><i class='fas fa-file-pdf'></i> $fileName (Uploaded on: $fileDate)</a><br>";
                        }
                    }
                }
            }
            echo "</td>
            <td class='$statusClass'>{$row['status']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No records found</td></tr>";
    }
    $conn->close();
    ?>
</table>

    </div>
</body>
</html>
