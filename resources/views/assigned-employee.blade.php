<?php

$servername = env('DB_HOST');
$username = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$dbname = env('DB_DATABASE');

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
?>
<link rel="stylesheet" href="{{asset('css/assigned-employee.css')}}">
<?php
// Display assigned tasks in a table
if (count($tasks) > 0) {
    $htmlOutput = <<<HTML

        <!-- Import Poppins font from Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree&display=swap">

        <div class='navbar'>
            <a href='/assigned-employees'}>Přiřazené zakázky</a>
            <a href='/assign-new-employee' }>Přiřadit zaměstnance</a>
            <a href='/work-details'}>Práce</a>
            <a href='/create-new-task'}>Vytvořit novou zakázku</a>
        </div>
        <h2>Přiřazené zakázky</h2>
        <table>
            <tr><th>Název zakázky</th><th>Přiřazený zaměstnanec</th><th>Datum vystavení</th><th>Datum dodání</th><th>Datum vytvoření zakázky</th><th>Počet ks</th></tr>
HTML;

    // Output each row using foreach
    foreach ($tasks as $taskName => $taskDetails) {
        $htmlOutput .= "<tr>";
        $htmlOutput .= "<td>{$taskName}</td>";
        $htmlOutput .= "<td>" . implode(", ", $taskDetails["employees"]) . "</td>";
        $htmlOutput .= "<td>{$taskDetails["date_of_issue"]}</td>";
        $htmlOutput .= "<td>{$taskDetails["date_of_delivery"]}</td>";
        $htmlOutput .= "<td>{$taskDetails["date_created"]}</td>";
        $htmlOutput .= "<td>{$taskDetails["quantity"]}</td>";
        $htmlOutput .= "</tr>";
    }

    $htmlOutput .= "</table>";
    $htmlOutput .= "<button class='back' onclick='window.location.href = \"/assign-new-employee\"'>Přiřadit zaměstnance k zakázce</button>";
} else {
    $htmlOutput = "<h2>No tasks assigned yet.</h2>";
}

$conn->close();

echo $htmlOutput;
?>
