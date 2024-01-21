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
