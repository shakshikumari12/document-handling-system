<?php
// Enable error reporting for debugging
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

// Fetch data from database
$sql = "SELECT * FROM letters_db";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Stored Letters</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Letter No</th>
                <th>Letter By Whom</th>
                <th>Receipt No</th>
                <th>Dealt By</th>
                <th>Midway Activity</th>
                <th>Status</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['letter_no']}</td>
                            <td>{$row['letter_by']}</td>
                            <td>{$row['recepiet_no']}</td>
                            <td>{$row['dealt_by']}</td>
                            <td>{$row['midway_activity']}</td>
                            <td>{$row['status']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No records found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
