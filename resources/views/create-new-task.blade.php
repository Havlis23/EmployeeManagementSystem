<?php
// Replace these with your database credentials
$servername = "sql.stredniskola.com";
$username = "it-davidhavel";
$password = "aSdf.1234";
$dbname = "davidhavel";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve task name from the form
    $task_name = $_POST["tname"];
    $description = $_POST["description"];
    $prace_id = $_POST["prace_id"];

    // Insert the new task into the database with the current timestamp
    $sql = "INSERT INTO zakazky (name, date_created) VALUES ('$task_name', '$description$','$prace_id', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "Zakázka byla úspěšně vytvořena!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Task</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .navbar {
            background-color: #2c3c4d;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar a.active {
            background-color: #ddd;
            color: black;
        }
    </style>
</head>
<body>
<div class='navbar'>
    <a href='/assigned-employees'>Přiřazené zakázky</a>
    <a href='/assign-new-employee'>Přiřadit zaměstnance</a>
    <a href='work-details'>Práce</a>
    <a href='/create-new-task'>Vytvořit novou zakázku</a>
</div>
<form method="post" action="/create-new-task">
    @csrf
    <h2>Vytvoření nové zakázky</h2>
    <label for="task_name">Název:</label>
    <input type="text" name="task_name" required>
    <label for="description">Popis zakázky</label>
    <input type="text" name="description" required>
    <label for="description">Prace ID</label>
    <input type="text" name="prace_id">
    <input type="submit" value="Vytvořit zakázku">
</form>
</body>
</html>
