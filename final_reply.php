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

// Check if letter_no is set in the URL
if (isset($_GET['letter_no'])) {
    $letter_no = $_GET['letter_no'];

    // Fetch the latest PDF for the given letter number
    $stmt = $conn->prepare("SELECT midway_activity FROM letters WHERE letter_no = ?");
    if (!$stmt) {
        die("Prepare statement failed: " . $conn->error);
    }
    $stmt->bind_param("s", $letter_no);
    $stmt->execute();
    $stmt->bind_result($midway_activity);
    $stmt->fetch();
    $stmt->close();

    if ($midway_activity) {
        $files = explode(',', $midway_activity);
        $last_file = end($files);

        // Display the PDF
        if (file_exists($last_file)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($last_file) . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            readfile($last_file);
            exit;
        } else {
            echo "File not found.";
        }
    } else {
        echo "No files found for this letter number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Reply</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
        
        background-size: cover;
        text-align: center;
        color: #2a2f6aab;
    }

    .new_container {
        background: rgba(0, 10, 67, 0.82);
        padding: 20px 40px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .new_container h1 {
        color: #ffcc00;
        font-size: 2.5em;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .new_container ul {
        padding-left: 0;
        list-style-position: inside;
    }

    .new_container li {
        margin: 15px 0;
    }

    .new_container a {
        text-decoration: none;
        color: white;
        background-color: #061853;
        padding: 15px 25px;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        display: inline-block;
        font-size: 1.2em;
    }

    .new_container a:hover {
        background-color: #0056b3;
        text-decoration: underline;
        transform: scale(1.05);
    }
    </style>
</head>
<body>
    <div class="new_container">
        <h1>Final Reply</h1>
        <ul>
            <?php
            // Fetch all letter numbers from the database
            $sql = "SELECT letter_no FROM letters";
            $result = $conn->query($sql);

            if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <li><a href="final_reply.php?letter_no=<?php echo $row['letter_no']; ?>"><?php echo $row['letter_no']; ?></a></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>No letters found.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>

<?php
$conn->close();
?>
