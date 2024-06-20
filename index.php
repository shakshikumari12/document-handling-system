<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('img/letters.avif') no-repeat center center fixed;
            background-size: cover;
            text-align: center;
            color: white;
        }
        .container {
            background: rgb(0 10 67 / 82%);
            padding: 21px 146px;
            border-radius: 10px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: #ffffff;
            background-color: #4a4d50de;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
	
    <div class="container">
        <h1>Welcome</h1>
        <ul>
            <li><a href="summary.php">A</a></li>
            <li><a href="link2.php">B</a></li>
            <li><a href="link3.php">C</a></li>
            <li><a href="link4.php">D</a></li>
        </ul>
    </div>
</body>
</html>
