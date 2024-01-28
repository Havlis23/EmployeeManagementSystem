<?php

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

// Sanitize form data
$name = $conn->real_escape_string($_POST['name']);
$description = $conn->real_escape_string($_POST['description']);
$date_of_issue = $conn->real_escape_string($_POST['date_of_issue']);
$date_of_delivery = $conn->real_escape_string($_POST['date_of_delivery']);
$quantity = $conn->real_escape_string($_POST['quantity']);
$prace = $_POST['prace'];

// Insert into zakazky table
$sqlZakazky = "INSERT INTO zakazky (name, description, date_of_issue, date_of_delivery, quantity)
               VALUES ('$name', '$description', '$date_of_issue', '$date_of_delivery', '$quantity')";
if ($conn->query($sqlZakazky) === TRUE) {
    $last_id = $conn->insert_id;

    // Insert into prace_zakazky table
    foreach ($prace as $prace_id) {
        $sqlPraceZakazky = "INSERT INTO prace_zakazky (prace_id, zakazky_id)
                            VALUES ('$prace_id', '$last_id')";
        echo "všechno ok ";
        if ($conn->query($sqlPraceZakazky) !== TRUE) {
            echo "Error: " . $sqlPraceZakazky . "<br>" . $conn->error;
        }
    }
} else {
    echo "Error: " . $sqlZakazky . "<br>" . $conn->error;
}

$conn->close();


exit();
