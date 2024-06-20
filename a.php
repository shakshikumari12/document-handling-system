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
    <title>New Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
        background: url('img/leter.avif') no-repeat center center fixed;
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
        <h1>A</h1>
        <ul>
            <li><a href="rec.php">Letter Received</a></li>
            <li><a href="display_data.php">Letter Issued</a></li>
            <li><a href="final_reply.php">Final Reply</a></li>
        </ul>
    </div>
</body>
</html>
