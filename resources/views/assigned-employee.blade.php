<?php

$servername = "192.168.0.222";
$username = "remote";
$password = "password";
$dbname = "dev";

$currentUrl = $_SERVER['REQUEST_URI'];


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch assigned tasks
$sqlAssignedTasks = "SELECT z.name AS task_name, e.fname, e.lname, z.date_of_issue, z.date_of_delivery, z.date_created, z.quantity
                     FROM zakazky_zamestnanci zz
                     JOIN zakazky z ON zz.zakazky_id = z.id
                     JOIN zamestnanci e ON zz.zamestnanci_id = e.id";
$assignedTasksResult = $conn->query($sqlAssignedTasks);

// Store the results in an associative array
$tasks = [];
while ($assignedTask = $assignedTasksResult->fetch_assoc()) {
    $tasks[$assignedTask["task_name"]]["employees"][] = $assignedTask["fname"] . " " . $assignedTask["lname"];
    $tasks[$assignedTask["task_name"]]["date_of_issue"] = $assignedTask["date_of_issue"];
    $tasks[$assignedTask["task_name"]]["date_of_delivery"] = $assignedTask["date_of_delivery"];
    $tasks[$assignedTask["task_name"]]["date_created"] = $assignedTask["date_created"];
    $tasks[$assignedTask["task_name"]]["quantity"] = $assignedTask["quantity"];
}

// Display assigned tasks in a table
if (count($tasks) > 0) {
    ?>

<link rel="stylesheet" href="{{asset('css/assigned-employee.css')}}">
<!-- Import Poppins font from Google Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree&display=swap">

    <?php

    echo "<div class='navbar'>
    <a href='/assigned-employees' " . ($currentUrl == '/assigned_employees' ? 'class="active"' : '') . ">Přiřazené zakázky</a>
    <a href='/assign-new-employee' " . ($currentUrl == '/admin_new.php' ? 'class="active"' : '') . ">Přiřadit zaměstnance</a>
    <a href='/work-details' " . ($currentUrl == '/work_details.php' ? 'class="active"' : '') . ">Práce</a>
    <a href='/create-new-task' " . ($currentUrl == '/create-new-task.php' ? 'class="active"' : '') . ">Vytvořit novou zakázku</a>
    </div>";
    echo "<h2>Přiřazené zakázky</h2>";
    echo "<table>";
    echo "<tr><th>Název zakázky</th><th>Přiřazený zaměstnanec</th><th>Datum vystavení</th><th>Datum dodání</th><th>Datum vytvoření zakázky</th><th>Počet ks</th></tr>";

    // Output each row using foreach
    foreach ($tasks as $taskName => $taskDetails) {
        echo "<tr>";
        echo "<td>" . $taskName . "</td>";
        echo "<td>" . implode(", ", $taskDetails["employees"]) . "</td>";
        echo "<td>" . $taskDetails["date_of_issue"] . "</td>";
        echo "<td>" . $taskDetails["date_of_delivery"] . "</td>";
        echo "<td>" . $taskDetails["date_created"] . "</td>";
        echo "<td>" . $taskDetails["quantity"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<button class='back' onclick='window.location.href = \"/assign-new-employee\"'>Přiřadit zaměstnance k zakázce</button>";
} else {
    echo "<h2>No tasks assigned yet.</h2>";
}

$conn->close();


{{--// Get the current URL
$currentUrl = $_SERVER['REQUEST_URI'];

// Fetch all tasks
$sqlTasks = "SELECT * FROM zakazky";
$tasks = $conn->query($sqlTasks);

// Fetch all employees
$sqlEmployees = "SELECT * FROM zamestnanci";
$employees = $conn->query($sqlEmployees);

// Fetch assigned tasks
$sqlAssignedTasks = "SELECT z.name AS task_name, e.fname, e.lname, t.date_of_issue, t.date_of_delivery, t.date_created, t.quantity
                     FROM zakazky_zamestnanci zz
                     JOIN zakazky z ON zz.zakazky_id = z.id
                     JOIN zamestnanci e ON zz.zamestnanci_id = e.id
                     JOIN tdb t ON zz.tdb_id = t.id"; // Assuming zz.tdb_id is the foreign key in zakazky_zamestnanci table that references tdb table
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

$conn->close();--}}
?>
