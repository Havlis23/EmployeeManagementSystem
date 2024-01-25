<?php

session_start();

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

// Get the current URL for the navbar
$currentUrl = $_SERVER['REQUEST_URI'];

// Fetch data from the database based on the provided schema
$prace_id = $_POST["prace_id"]; // Retrieve the prace id from the form submission

// Select only from the prace table
$sql = "SELECT * FROM prace WHERE id = $prace_id";
$result = $conn->query($sql);

$htmlOutput = <<<HTML
<style>
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
    // Fetch the data and display it on the new site
    while ($row = $result->fetch_assoc()) {
        // Display the data from the prace table

        $htmlOutput .= "<h2>Prace Details </h2>";
        $htmlOutput .= "<h3>Session id:</h3>";
        $htmlOutput .= $_SESSION['employee_id'];
        $htmlOutput .= "<form action='/update' method='post'>";
        $htmlOutput .= csrf_field();
        $htmlOutput .= "<label for='name'>Name:</label>";
        $htmlOutput .= "<input type='text' id='name' name='name' value='{$row['name']}'><br>";
        $htmlOutput .= "<label for='description'>Description:</label>";
        $htmlOutput .= "<input type='text' id='description' name='description' value='{$row['description']}'><br>";
        $htmlOutput .= "<label for='checkbox'>Checkbox:</label>";
        $htmlOutput .= "<input type='text' id='checkbox' name='checkbox' value='{$row['checkbox']}'><br>";
        $htmlOutput .= "<input type='hidden' name='prace_id' value='{$row['id']}'>";
        $htmlOutput .= "<input type='submit' value='Update'>";
        $htmlOutput .= "<a href='" . getReferer() . "' class='back'>Zpět</a>";
        $htmlOutput .= "</form>";
    }
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
