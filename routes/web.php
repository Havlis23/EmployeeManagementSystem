<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::post('/employee', function () {
    return view('employee');
});

Route::post('/details', function () {
    return view('details');
});

Route::post('/update', function () {
    return view('update');
});

Route::get('/assigned-employees', function () {
    return view('assigned-employee');
});

Route::get('/assign-new-employee', function () {
    return view('assign-new-employee');
});

Route::get('/work-details', function () {
    return view('work-details');
});

Route::post('/assign-task', function () {
    return view('assign-task');
});

Route::get('/create-new-task', function () {
    return view('create-new-task');
});
Route::post('/create-new-task', function () {
    $servername = "sql.stredniskola.com";
    $username = "it-davidhavel";
    $password = "aSdf.1234";
    $dbname = "davidhavel";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $task_name = $_POST["task_name"];
    $description = $_POST["description"];
    $prace_id = $_POST["prace_id"];

    $sql = "INSERT INTO zakazky (name, description, prace_id, date_created) VALUES ('$task_name', '$description', '$prace_id', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "New task added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
});
