<?php

$servername = "192.168.0.222";
$username = "remote";
$password = "password";
$dbname = "dev";

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
$sql = "SELECT p.*, CONCAT(z.fname, ' ', z.lname) AS employee_name
        FROM prace p
        JOIN zamestnanci z ON p.edited_by = z.id";
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
            <tr><th>ID</th><th>Název</th><th>Popis</th><th>Checkbox</th><th>Naposledy upravil</th></tr>
HTML;

    while ($row = $result->fetch_assoc()) {
        $htmlOutput .= "<tr>";
        $htmlOutput .= "<td>{$row["id"]}</td>";
        $htmlOutput .= "<td>{$row["name"]}</td>";
        $htmlOutput .= "<td>{$row["description"]}</td>";
        $htmlOutput .= "<td>" . ($row["checkbox"] == 1 ? "Hotovo" : "Nehotovo") . "</td>";
        $htmlOutput .= "<td>{$row["employee_name"]}</td>";
        $htmlOutput .= "</tr>";
    }

    $htmlOutput .= "</table>";
} else {
    $htmlOutput = "<h2>No records found.</h2>";
}

$conn->close();

echo $htmlOutput;
?>
