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

// Query to count the total records
$sqlTotal = "SELECT COUNT(*) AS count FROM letters";

// Query to count the final reply letters
$sqlFinal = "SELECT COUNT(*) AS count FROM letters WHERE final_reply != ''";

// Query to count the pending cases (where final reply is not present) grouped by letter_by and dealt_by
$sqlPending = "SELECT COUNT(*) AS count, letter_by, dealt_by FROM letters WHERE final_reply = '' GROUP BY dealt_by, letter_by";

$resultTotal = $conn->query($sqlTotal);
$resultFinal = $conn->query($sqlFinal);
$resultPending = $conn->query($sqlPending);

$countTotal = $resultTotal->fetch_assoc()['count'];
$countFinal = $resultFinal->fetch_assoc()['count'];

$pendingCases = [];
while ($row = $resultPending->fetch_assoc()) {
    $pendingCases[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .summary-container {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            text-align: center;
            color: #333333;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .summary-table th {
            background-color: #f2f2f2;
            color: #333333;
        }
        .button-container {
            margin-top: 20px;
            text-align: center;
        }
        .button-container .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .button-container .btn:hover {
            background-color: #0056b3;
        }
        .sub-heading {
            font-size: 14px;
            color: #555555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="summary-container">
            <h2>Records Summary</h2>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Total Records</th>
                        <th>Final Reply Letters</th>
                        <th>Pending Cases</th>
                        <th colspan="7">Pending With</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>BB</th>
                        <th>RP</th>
                        <th>MK</th>
                        <th>MK</th>
                        <th>KR</th>
                        <th>AK</th>
                        <th>SK</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $countTotal; ?></td>
                        <td><?php echo $countFinal; ?></td>
                        <td>
                            <?php
                            $totalPending = 0;
                            foreach ($pendingCases as $case) {
                                $totalPending += $case['count'];
                            }
                            echo $totalPending;
                            ?>
                        </td>
                        <?php
                        
                        $subColumns = array_fill(0, 7, '');

                       
                        $dropdownOptions = [
                            'Bharat Bhuskar' => 'BB',
                            'Ravi Prakash' => 'RP',
                            'Mithilesh Kr Choudhary' => 'MK',
                            'Madhuri Kumari' => 'MK',
                            'Kumar Ravi' => 'KR',
                            'Anisha Kumari' => 'AK',
                            'Saket Kumar' => 'SK'
                        ];

                        
                        foreach ($pendingCases as $case) {
                            $dealtBy = $case['dealt_by'];
                            $initials = isset($dropdownOptions[$dealtBy]) ? $dropdownOptions[$dealtBy] : '';
                            $index = array_search($initials, array_values($dropdownOptions));
                            if ($index !== false) {
                                $subColumns[$index] .= "<div>{$case['count']}</div>";
                            }
                        }

                       
                        foreach ($subColumns as $subColumn) {
                            echo "<td>{$subColumn}</td>";
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
            <div class="button-container">
                <a href="rec.php" class="btn">Proceed</a>
                <a href="index.php" class="btn">Back</a>
            </div>
        </div>
    </div>
</body>
</html>