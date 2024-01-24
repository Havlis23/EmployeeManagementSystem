<?php

//connect to database
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

// Create task name and post data
$task_id = $_POST["task_id"];
$employee_ids = $_POST["employee_id"];

foreach ($employee_ids as $employee_id) {
    $sql = "INSERT INTO zakazky_zamestnanci (zakazky_id, zamestnanci_id) VALUES ($task_id, $employee_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Task assigned successfully to employee $employee_id<br>";
    } else {
        echo "Error assigning task to employee $employee_id: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
