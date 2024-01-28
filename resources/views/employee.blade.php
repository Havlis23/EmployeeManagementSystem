<?php
session_start(); // Start the session if it's not already started

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

$employee_id = $_POST["employee_id"]; // Retrieve the employee id from the form submission

$sql = "SELECT zakazky.*, zamestnanci.fname, zamestnanci.lname, zamestnanci.employee_number
        FROM zakazky
        JOIN zakazky_zamestnanci ON zakazky.id = zakazky_zamestnanci.zakazky_id
        JOIN zamestnanci ON zakazky_zamestnanci.zamestnanci_id = zamestnanci.id
        WHERE zamestnanci.id = $employee_id";

$result = $conn->query($sql);

$_SESSION['employee_id'] = $employee_id;

$htmlOutput = <<<HTML
<style>
    .block {
    margin-bottom: 20px;
}
label {
    font-weight: bold;
}
.not-found-message {
    text-align: center;
    color: #ff0000; /* Red color for emphasis */
    font-size: 1.2em;
    font-weight: bold;
    margin-top: 20px;
}
.zakazky-details {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.zakazky-details th, .zakazky-details td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: left;
}

.zakazky-details th {
    background-color: #2c3c4d;
    color: white;
}
.back{
    background-color: #2c3c4d;
    color: white;
    cursor: pointer;
    padding: 15px 20px;
    font-size: 0.8em;
}
label {
    font-weight: bold;
    color: #2c3c4d; /* Dark blue color for labels */
}
p {
    color: #333; /* Slightly darker text color */
    margin-top: 5px; /* Adjust the margin for better spacing */
}
h2 {
    color: #2c3c4d; /* Dark blue color for headings */
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

</style>
HTML;

if ($result->num_rows > 0) {
    $htmlOutput .= "<div class='navbar'>
    <a href='/'>Přihlášení</a>
    <a href='test.php?employee_id={$_SESSION['employee_id']}'>Detail zaměstnance</a>
    <a href='#'>Něco</a>
    </div>";

    $row = $result->fetch_assoc();
    $htmlOutput .= "<h2>Detail zaměstnance</h2>";
    $htmlOutput .= "<div class='block'>";
    $htmlOutput .= "<label>Jméno zaměstnance:</label>";
    $htmlOutput .= "<p>{$row['fname']} {$row['lname']}</p>";
    $htmlOutput .= "</div>";
    $htmlOutput .= "<div class='block'>";
    $htmlOutput .= "<label>ID zaměstnance:</label>";
    $htmlOutput .= "<p>{$row['employee_number']}</p>";
    $htmlOutput .= "</div>";
    $htmlOutput .= "<div class='block'>";
    $htmlOutput .= "<label>Zaměstnanecké číslo:</label>";
    $htmlOutput .= "<p>{$row['employee_number']}</p>";
    $htmlOutput .= "</div>";

    $htmlOutput .= "<h2>Zakazky</h2>";
    $htmlOutput .= "<table class='zakazky-details'>";
    $htmlOutput .= "<tr><th>Jméno</th><th>Popis</th><th>Prace ID</th></tr>";

    do {
        $htmlOutput .= "<tr>";
        $htmlOutput .= "<td>{$row['name']}</td>";
        $htmlOutput .= "<td>{$row['description']}</td>";
        $htmlOutput .= "<td>{$row['prace_id']}</td>";
        $htmlOutput .= "<td>";
        $htmlOutput .= "<form action='/details' method='post'>";
        $htmlOutput .= csrf_field();
        $htmlOutput .= "<input type='hidden' name='prace_id' value='{$row['prace_id']}'>";
        $htmlOutput .= "<button type='submit' class='back'>Zobrazit práci</button>";
        $htmlOutput .= "</form>";
        $htmlOutput .= "</td>";
        $htmlOutput .= "</tr>";
    } while ($row = $result->fetch_assoc());

    $htmlOutput .= "</table>";
    $htmlOutput .= "<button class='back' onclick='goBack()'>Zpět</button>";
} else {
    $htmlOutput .= "<div class='not-found-message'>";
    $htmlOutput .= "<p>Zaměstnanec s číslem $employee_id nenalezen.</p>";
    $htmlOutput .= "<button class='back' onclick='goBack()'>Zpět</button>";
    $htmlOutput .= "</div>";
}

$htmlOutput .= "<script>
function goBack() {
  window.history.back();
}
</script>";

$conn->close();

echo $htmlOutput;
?>
