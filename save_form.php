<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "letters_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file upload
$midway_activity = $_FILES['midway_activity'];
$uploadFile = $uploadDir . basename($midway_activity['name']);
$fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

// Check if the file is a PDF
if ($fileType == 'pdf') {
    if (move_uploaded_file($midway_activity['tmp_name'], $uploadFile)) {
        // File uploaded successfully
        $midway_activity_path = $uploadFile;
    } else {
        $midway_activity_path = '';
        $message = "Error uploading file.";
    }
} else {
    $midway_activity_path = '';
    $message = "Only PDF files are allowed.";
}


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $letter_no = $_POST['letter_no'];
    $letter_by = $_POST['letter_by'];
    $recepiet_no = $_POST['recepiet_no'];
    $dealt_by = $_POST['dealt_by'];
    $midway_activity = $_POST['midway_activity'];
    $status = $_POST['status'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO letters (date, letter_no, letter_by, recepiet_no, dealt_by, midway_activity, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $date, $letter_no, $letter_by, $recepiet_no, $dealt_by, $midway_activity, $status);

    // Execute the query
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>