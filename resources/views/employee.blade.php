<?php
session_start(); // Start the session if it's not already started

// Database connection
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

// Fetch data from the database based on the provided schema
$employee_id = $_POST["employee_id"]; // Retrieve the employee id from the form submission

$sql = "SELECT zakazky.*, zamestnanci.fname, zamestnanci.lname, zamestnanci.employee_number
        FROM zakazky
        JOIN zakazky_zamestnanci ON zakazky.id = zakazky_zamestnanci.zakazky_id
        JOIN zamestnanci ON zakazky_zamestnanci.zamestnanci_id = zamestnanci.id
        WHERE zamestnanci.id = $employee_id";

$result = $conn->query($sql);

$_SESSION['employee_id'] = $employee_id;

echo "<style>
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

</style>";



if ($result->num_rows > 0) {
    echo "<div class='navbar'>
    <a href='/'>Přihlášení</a>
    <a href='test.php?employee_id=" . $_SESSION['employee_id'] . "'>Detail zaměstnance</a>
    <a href='#'>Něco</a>
    </div>";
    // Render employee details only once
    $row = $result->fetch_assoc();

        echo "<h2>Detail zaměstnance</h2>";
        //echo $_SESSION['employee_id'];
        echo "<div class='block'>";
        echo "<label>Jméno zaměstnance:</label>";
        echo "<p>{$row['fname']} {$row['lname']}</p>";
        echo "</div>";
        echo "<div class='block'>";
        echo "<label>ID zaměstnance:</label>";
        echo "<p>{$row['employee_number']}</p>";
        echo "</div>";
        echo "<div class='block'>";
        echo "<label>Zaměstnanecké číslo:</label>";
        echo "<p>{$row['employee_number']}</p>";
        echo "</div>"; // Set the flag to true after rendering details


    // Render zakazky details for each row
    echo "<h2>Zakazky</h2>";
    echo "<table class='zakazky-details'>";
    echo "<tr><th>Jméno</th><th>Popis</th><th>Prace ID</th></tr>";

    // Loop through all rows
    do {
        echo "<tr>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['description']}</td>";
        echo "<td>{$row['prace_id']}</td>";
        echo "<td>";
        echo "<form action='/details' method='post'>";?>
        @csrf
        <?php
        echo "<input type='hidden' name='prace_id' value='{$row['prace_id']}'>";
        echo "<button type='submit' class='back'>Zobrazit práci</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    } while ($row = $result->fetch_assoc());

    echo "</table>";
    echo "<button class='back', onclick='goBack()'>Zpět</button>";
} else {
    echo "<div class='not-found-message'>";
    echo "<p>Zaměstnanec s číslem $employee_id nenalezen.</p>";
    echo "<button class='back', onclick='goBack()'>Zpět</button>";
    echo "</div>";
}



echo "<script>
function goBack() {
  window.history.back();
}
</script>";

$conn->close();
?>
