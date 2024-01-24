<?php

$servername = "sql.stredniskola.com";
$username = "it-davidhavel";
$password = "aSdf.1234";
$dbname = "davidhavel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


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

    ?>

<link rel="stylesheet" href="{{asset('css/assigned-employee.css')}}">
<!-- Import Poppins font from Google Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree&display=swap">

    <?php

    // Output the navbar with the active class dynamically applied
    echo "<div class='navbar'>
    <a href='/assigned-employees' " . ($currentUrl == '/assigned_employees' ? 'class="active"' : '') . ">Přiřazené zakázky</a>
    <a href='/assign-new-employee' " . ($currentUrl == '/admin_new.php' ? 'class="active"' : '') . ">Přiřadit zaměstnance</a>
    <a href='/work-details' " . ($currentUrl == '/work_details.php' ? 'class="active"' : '') . ">Práce</a>
    <a href='/create-new-task' " . ($currentUrl == '/create-new-task.php' ? 'class="active"' : '') . ">Vytvořit novou zakázku</a>
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
