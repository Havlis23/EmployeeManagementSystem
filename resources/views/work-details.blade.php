<?php


//load connection from .env file
$servername = env('DB_HOST');
$username = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$dbname = env('DB_DATABASE');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<link rel="stylesheet" href="{{asset('css/assigned-employee.css')}}">

<?php

// Get the current URL for the navbar
$currentUrl = $_SERVER['REQUEST_URI'];

// Fetch details from prace and joined employee names from zamestnanci
$sql = "SELECT p.*, z.*, CONCAT(p.name, ' / ', z.name) AS combined_name, CONCAT(zz.fname, ' ', zz.lname) AS employee_name
        FROM prace p
        JOIN zakazky z ON p.id = z.prace_id
        JOIN zamestnanci zz ON p.edited_by = zz.id";
$result = $conn->query($sql);

// Display details in a table
if ($result->num_rows > 0) {
    $htmlOutput = <<<HTML
    <div class='navbar'>
        <a href='/assigned-employees'>Přiřazené zakázky</a>
        <a href='/assign-new-employee'>Přiřadit zaměstnance</a>
        <a href='work-details'>Práce</a>
        <a href='/create-new-task'>Vytvořit novou zakázku</a>
    </div>
    <h2>Práce</h2>
    <table>
        <tr><th>ID</th><th>Název</th><th>Popis</th><th>Status</th><th>Naposledy upravil</th><th>Zakázka / Práce Název</th><th>Zakázka Popis</th><th>Datum vydání</th><th>Datum dodání</th><th>Datum vytvoření</th><th>Množství</th></tr>
HTML;

    while ($row = $result->fetch_assoc()) {
        $htmlOutput .= "<tr>";
        $htmlOutput .= "<td>{$row["id"]}</td>";
        $htmlOutput .= "<td>{$row["combined_name"]}</td>";
        $htmlOutput .= "<td>{$row["description"]}</td>";
        $htmlOutput .= "<td>" . ($row["checkbox"] == 1 ? "Hotovo" : "Nehotovo") . "</td>";
        $htmlOutput .= "<td>{$row["employee_name"]}</td>";
        $htmlOutput .= "<td>{$row["description"]}</td>";
        $htmlOutput .= "<td>{$row["date_of_issue"]}</td>";
        $htmlOutput .= "<td>{$row["date_of_delivery"]}</td>";
        $htmlOutput .= "<td>{$row["date_created"]}</td>";
        $htmlOutput .= "<td>{$row["quantity"]}</td>";
        $htmlOutput .= "</tr>";
    }

    $htmlOutput .= "</table>";
} else {
    $htmlOutput = "<h2>No records found.</h2>";
}

$conn->close();

echo $htmlOutput;
?>
