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


echo "<style>
    body {
        padding: 20px;
        height: 100vh;
        margin: 0;
        font-family: 'Arial', sans-serif;
    }

    h2 {
        color: #2c3c4d;
        margin-bottom: 10px;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #2c3c4d;
        color: white;
    }

    h2, h2 + table {
        margin-bottom: 20px;
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

// Fetch details from prace and joined employee names from zamestnanci
$sql = "SELECT p.*, CONCAT(z.fname, ' ', z.lname) AS employee_name
        FROM prace p
        JOIN zamestnanci z ON p.edited_by = z.id";
$result = $conn->query($sql);

// Display details in a table
if ($result->num_rows > 0) {

    // Output the navbar with the active class dynamically applied
    echo "<div class='navbar'>
    <a href='/assigned-employees' " . ($currentUrl == '/assigned-employees.php' ? 'class="active"' : '') . ">Přiřazené zakázky</a>
    <a href='/assign-new-employee' " . ($currentUrl == '/admin-new.php' ? 'class="active"' : '') . ">Přiřadit zaměstnance</a>
    <a href='work-details' " . ($currentUrl == '/work-details.php' ? 'class="active"' : '') . ">Práce</a>
    <a href='/create-new-task' " . ($currentUrl == '/create-new-task.php' ? 'class="active"' : '') . ">Vytvořit novou zakázku</a>
    </div>";
    ?>
    <h2>Práce</h2>
    <table>
        <tr><th>ID</th><th>Název</th><th>Popis</th><th>Checkbox</th><th>Naposledy upravil</th></tr>
    <?php
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . ($row["checkbox"] == 1 ? "Hotovo" : "Nehotovo") . "</td>";
        echo "<td>" . $row["employee_name"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<h2>No records found.</h2>";
}

$conn->close();
?>
