<?php

$servername = "192.168.0.222";
$username = "remote";
$password = "password";
$dbname = "dev";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve task name from the form
    $task_name = $_POST["name"];
    $description = $_POST["description"];
    $prace_id = $_POST["prace_id"];
    $date_of_issue = date('Y-m-d H:i:s'); // Current timestamp
    $date_of_delivery = date('Y-m-d H:i:s'); // Current timestamp
    $date_created = date('Y-m-d H:i:s'); // Current timestamp
    $quantity = $_POST["quantity"]; // Assuming you have a quantity field in your form

    // Insert the new task into the database with the current timestamp
    $sql = "INSERT INTO zakazky (name, description, prace_id, date_of_issue, date_of_delivery, date_created, quantity)
            VALUES ('$task_name', '$description', '$prace_id', '$date_of_issue', '$date_of_delivery', '$date_created', '$quantity')";

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
    <title>Your Title</title>

    <!-- Importing fonts from Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500&family=Poppins:wght@500&display=swap">

    <style>
        body {
            padding: 20px;
            height: 100vh;
            margin: 0;
            font-family: 'Figtree', sans-serif;
            background-color: #171B34;
            color: #D6D8DF;
            line-height: 23px;
            font-size: 14px;
            font-weight: 400;
        }

        h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            line-height: 30px;
            font-size: 40px;
            color: white;
            margin-bottom: 30px;
        }

        form {
            margin-top: 20px;
            text-align: center;
            padding: 20px;
            border: 0.8px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 1em;
            box-sizing: border-box;
        }

        input[type='submit'] {
            background-color: #2c3c4d;
            color: white;
            cursor: pointer;
            padding: 10px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
        }

        input[type='submit']:hover {
            background-color: #24313f;
        }

        option {
            padding: 5px;
        }

        table {
            background-color: #31324E;
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        th, td {
            border: 0.5px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            /*background-color: #2c3c4d;*/
            color: white;
        }

        .back {
            background-color: transparent;
            color: #D6D8DF;
            cursor: pointer;
            padding: 15px 20px;
            font-size: 0.8em;
            border: 1px solid #D6D8DF;
        }

        .back:hover {
            background-color: #D6D8DF;
            color: #171B34;
        }

        .navbar {
            background-color: #292F4C;
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
        input{
            margin-bottom: 10px;
        }

        @media screen and (max-width: 600px) {
            /* Adjust styles for smaller screens */
            body {
                padding: 10px;
            }

            form {
                padding: 10px;
            }

            table {
                font-size: 0.9em;
            }

            .back {
                padding: 10px 15px;
                font-size: 0.7em;
            }

            .navbar a {
                padding: 10px 8px;
            }

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
    <input type="text" name="task_name" class="input" required>
    <br>
    <label for="description">Popis zakázky</label>
    <input type="text" name="description" required>
    <br>
    <label for="description">Prace ID</label>
    <input type="text" name="prace_id">
    <br>
    <label for="quantity">Počet ks</label>
    <input type="text" name="quantity">
    <br>
    <input type="submit" value="Vytvořit zakázku">
</form>
</body>
</html>
