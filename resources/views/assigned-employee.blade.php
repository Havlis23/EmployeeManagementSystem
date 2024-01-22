<?php

$servername = "192.168.0.222";
$username = "remote";
$password = "asdf.1234";
$dbname = "dev";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<style>
    body {
        padding: 20px;
        height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    h2 {
        color: #2c3c4d;
        margin-bottom: 10px;
    }

    form {
        text-align: center;
        padding: 20px;
        border: 2px solid #ccc;
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
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #2c3c4d;
        color: white;
    }

    .back {
        background-color: #2c3c4d;
        color: white;
        cursor: pointer;
        padding: 15px 20px;
        font-size: 0.8em;
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
</style>";

// Get the current URL
$currentUrl = $_SERVER['REQUEST_URI'];

// Fetch all tasks
$sqlTasks = "SELECT * FROM zakazky";
$tasks = $conn->query($sqlTasks);

// Fetch all employees
$sqlEmployees = "SELECT * FROM zamestnanci";
$employees = $conn->query($sqlEmployees);

// Fetch assigned tasks
$sqlAssignedTasks = "SELECT z.name AS task_name, e.fname, e.lname
                     FROM zakazky_zamestnanci zz
                     JOIN zakazky z ON zz.zakazky_id = z.id
                     JOIN zamestnanci e ON zz.zamestnanci_id = e.id";
$assignedTasksResult = $conn->query($sqlAssignedTasks);

// Display assigned tasks in a table
if ($assignedTasksResult->num_rows > 0) {

    // Output the navbar with the active class dynamically applied
    echo "<div class='navbar'>
    <a href='/assigned_employees' " . ($currentUrl == '/assigned_employees' ? 'class="active"' : '') . ">Přiřazené zakázky</a>
    <a href='/assign-new-employee' " . ($currentUrl == '/admin_new.php' ? 'class="active"' : '') . ">Přiřadit zaměstnance</a>
    <a href='/work-details' " . ($currentUrl == '/work_details.php' ? 'class="active"' : '') . ">Práce</a>
    </div>";

    echo "<h2>Přiřazené zakázky</h2>";
    echo "<table>";
    echo "<tr><th>Název zakázky</th><th>Přiřazený zaměstnanec</th></tr>";

    $tasks = [];
    while ($assignedTask = $assignedTasksResult->fetch_assoc()) {
        $tasks[$assignedTask["task_name"]][] = $assignedTask["fname"] . " " . $assignedTask["lname"];
    }

    foreach ($tasks as $taskName => $employees) {
        echo "<tr>";
        echo "<td>" . $taskName . "</td>";
        echo "<td>" . implode(", ", $employees) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<button class='back' onclick='window.location.href = \"/assign-new-employee\"'>Přiřadit zaměstnance k zakázce</button>";
} else {
    echo "<h2>No tasks assigned yet.</h2>";
}

$conn->close();
?>
