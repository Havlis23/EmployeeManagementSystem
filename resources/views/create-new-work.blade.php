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

// Fetch all prace
$sqlPrace = "SELECT * FROM prace";
$resultPrace = $conn->query($sqlPrace);

// Close connection
$conn->close();
?>

    <!DOCTYPE html>
<html>
<head>
    <title>Add Zakazky</title>
</head>
<body>
<form action="submit-work" method="post">
    @csrf
    <label for="name">Název zakázky:</label><br>
    <input type="text" id="name" name="name"><br>
    <label for="description">Popis:</label><br>
    <textarea id="description" name="description"></textarea><br>
    <label for="date_of_issue">Datum vystavení:</label><br>
    <input type="text" id="date_of_issue" name="date_of_issue"><br>
    <label for="date_of_delivery">Datum dodání:</label><br>
    <input type="text" id="date_of_delivery" name="date_of_delivery"><br>
    <label for="quantity">Počet ks:</label><br>
    <input type="text" id="quantity" name="quantity"><br>
    <label for="prace">Práce:</label><br>
    <select id="prace" name="prace[]" multiple>
        <?php
        if ($resultPrace->num_rows > 0) {
            // Output each row
            while($row = $resultPrace->fetch_assoc()) {
                echo "<option value='{$row["id"]}'>{$row["name"]}</option>";
            }
        }
        ?>
    </select><br>
    <input type="submit" value="Odeslat">
</form>
</body>
</html>
