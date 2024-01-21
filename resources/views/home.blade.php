<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Input Form</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        form {
            text-align: center;
            padding: 40px;
            border: 4px solid #ccc;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        label {
            font-size: 1.5em;
            display: block;
            margin-bottom: 20px;
            font-weight: bold;
            text-decoration: underline;
        }

        input {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 1.2em;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #2c3c4d;
            color: white;
            cursor: pointer;
            padding: 15px 20px;
            font-size: 1.2em;
        }

        input[type="submit"]:hover {
            background-color: #24313f;
        }
    </style>
</head>

<body>
    <form action="employee" method="post">
        @csrf
        <label for="employee_id">Vložte zaměstnanecké číslo:</label>
        <input type="text" id="employee_id" name="employee_id">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
