<?php

$servername = "sql.stredniskola.com";
$username = "it-davidhavel";
$password = "aSdf.1234";
$dbname = "davidhavel";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$edited_by = isset($_SESSION['employee_id']) ? $_SESSION['employee_id'] : null;

if ($edited_by === null) {
    die("No employee ID found in session. Please make sure to log in before editing records.");
}

$prace_id = $_POST['prace_id'];
$name = $_POST['name'];
$description = $_POST['description'];
$checkbox = $_POST['checkbox'];
$edited_by = $_SESSION['employee_id']; // Retrieve the employee's ID from the session

$sql = "UPDATE prace SET name = '$name', description = '$description', checkbox = '$checkbox', edited_by =  '$edited_by' WHERE id = $prace_id";
if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}



//button to reddirect back to test.php with $_SESSION['employee_id']


function getReferer() {
    return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php'; // Replace 'index.php' with your default page
}

echo "<script>
function goBack() {
  window.history.back();
}
</script>";

$conn->close();
?>
