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
        width: 80%;
        border-collapse: collapse;
        margin-top: 20px;
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
</style>";

// Get the current URL for the navbar
$currentUrl = $_SERVER['REQUEST_URI'];

// Fetch all tasks
$sqlTasks = "SELECT * FROM zakazky";
$tasks = $conn->query($sqlTasks);

// Fetch all employees
$sqlEmployees = "SELECT * FROM zamestnanci";
$employees = $conn->query($sqlEmployees);

// Convert employees result to an array for easy use in the form
$employeesArray = [];
while ($employee = $employees->fetch_assoc()) {
    $employeesArray[] = $employee;
}

// Fetch assigned tasks
$sqlAssignedTasks = "SELECT z.name AS task_name, e.fname, e.lname
                     FROM zakazky_zamestnanci zz
                     JOIN zakazky z ON zz.zakazky_id = z.id
                     JOIN zamestnanci e ON zz.zamestnanci_id = e.id";
$assignedTasksResult = $conn->query($sqlAssignedTasks);

// Display assigned tasks in a table
/* if ($assignedTasksResult->num_rows > 0) {
    echo "<h2>Already Assigned Tasks</h2>";
    echo "<table>";
    echo "<tr><th>Task Name</th><th>Assigned Employees</th></tr>";
    while ($assignedTask = $assignedTasksResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $assignedTask["task_name"] . "</td>";
        echo "<td>" . $assignedTask["fname"] . " " . $assignedTask["lname"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<h2>No tasks assigned yet.</h2>";
} */

// Display unassigned tasks with the form for assignment
if ($tasks->num_rows > 0) {
    // Output the navbar with the active class dynamically applied
    echo "<div class='navbar'>
    <a href='/assigned-employees' " . ($currentUrl == '/assigned_employees.php' ? 'class="active"' : '') . ">Přiřazené zakázky</a>
    <a href='/assign-new-employee' " . ($currentUrl == '/admin_new.php' ? 'class="active"' : '') . ">Přiřadit zaměstnance</a>
    <a href='/work-details' " . ($currentUrl == '/work_details.php' ? 'class="active"' : '') . ">Práce</a>
    </div>";
    while ($task = $tasks->fetch_assoc()) {
        echo "<div>";
        echo "<h2>Task: " . $task["name"] . "</h2>";
        echo "<form method='POST' action='/assign-task'>";?>
        @csrf
        <?php
        echo "<input type='hidden' name='task_id' value='" . $task["id"] . "'>";
        echo "<select name='employee_id[]' multiple>";
        foreach ($employeesArray as $employee) {
            echo "<option value='" . $employee["id"] . "'>" . $employee["fname"] . " " . $employee["lname"] . "</option>";
        }
        echo "</select>";
        echo "<br>";
        echo "<input type='submit' value='Assign Task'>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "<h2>No tasks found.</h2>";
}

$conn->close();
?>
