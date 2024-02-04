<?php

session_start();

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

// Get the current URL for the navbar
$currentUrl = $_SERVER['REQUEST_URI'];

// Fetch data from the database based on the provided schema
$prace_id = $_POST["prace_id"]; // Retrieve the prace id from the form submission
$zakazky_id = $_POST["zakazky_id"]; // Retrieve the zakazky id from the form submission

// Select only from the prace table
$sql = "SELECT prace.*, zakazky.name as zakazky_name, zakazky.description as zakazky_description
        FROM zakazky
        INNER JOIN prace_zakazky ON zakazky.id = prace_zakazky.zakazky_id
        INNER JOIN prace ON prace_zakazky.prace_id = prace.id
        WHERE zakazky.id = $zakazky_id";
$result = $conn->query($sql);

$htmlOutput = <<<HTML
<style>
 form {
        display: table;
        margin: auto;
        border-collapse: collapse;
        border: 1px solid #ccc;
    }

    label {
        display: table-cell;
        padding: 10px;
        font-size: 1em;
        text-align: right;
        vertical-align: middle;
    }

    input {
        display: table-cell;
        padding: 10px;
        font-size: 1em;
    }

    input[type='submit'] {
        display: table-footer-group;
        margin: 10px auto;
        padding: 10px 20px;
    }

       table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    /* Styles for the table header */
    th {
        background-color: #2c3c4d;
        color: white;
        padding: 10px;
        text-align: left;
    }

    /* Styles for the table data */
    td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: left;
    }
    .prace-details {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .prace-details th, .prace-details td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: left;
    }

    .prace-details th {
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

    form {
        text-align: center;
        padding: 40px;
        border: 4px solid #ccc;
        border-radius: 16px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        margin-top: 20px;
    }

    label {
        font-size: 1.5em;
        display: block;
        margin-bottom: 20px;
        font-weight: bold;
        text-decoration: underline;
        color: #2c3c4d;
    }

    input {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        font-size: 1.2em;
        box-sizing: border-box;
    }

    input[type='submit'] {
        background-color: #2c3c4d;
        color: white;
        cursor: pointer;
        padding: 15px 20px;
        font-size: 1.2em;
        border: none;
        border-radius: 4px;
    }

    input[type='submit']:hover {
        background-color: #24313f;
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

$htmlOutput .= "<div class='navbar'>
    <a href='/'>Přihlášení</a>
    <a href='test.php?employee_id=" . $_SESSION['employee_id'] . "'>Detail zaměstnance</a>
    <a href='#'>Něco</a>
</div>";

if ($result->num_rows > 0) {
    // Start the table
    $htmlOutput .= "<table>";
    $htmlOutput .= "<tr><th>Prace Name</th><th>Prace Description</th><th>Zakazky Name</th><th>Zakazky Description</th></tr>";

    // Fetch the data and display it on the new site
    while ($row = $result->fetch_assoc()) {
        $htmlOutput .= "<tr>";
        $htmlOutput .= "<td>{$row['name']}</td>";
        $htmlOutput .= "<td>{$row['description']}</td>";
        $htmlOutput .= "<td>{$row['zakazky_name']}</td>";
        $htmlOutput .= "<td>{$row['zakazky_description']}</td>";
        $htmlOutput .= "</tr>";
    }

    // End the table
    $htmlOutput .= "</table>";
} else {
    $htmlOutput .= "Prace not found.";
}

function getReferer()
{
    return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.html'; // Replace 'index.php' with your default page
}

$htmlOutput .= "<script>
function goBack() {
  window.history.back();
}
</script>";

$conn->close();

echo $htmlOutput;
?>
