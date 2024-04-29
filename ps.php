<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Station Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url("background.jpg");
            background-size: cover;
            background-position: center;
        }

        .class1 {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-form {
            background-color: rgba(7, 6, 6, 0.664);
            padding: 20px 60px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            backdrop-filter: blur(40px);
            text-decoration: #ccc;
            border-radius: 60px;
        }

        .logo-form2{
            position: fixed;
            top: 0;
            height: 160px;
            width: 160px; 
            margin-left: auto;
        }
        .police-logo {
            width: 150px;
            height: auto;
            margin: 0 auto;
            display: block;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: white;
        }

        input[type="text"],
        input[type="password"],
        input[type="station_name"] {
            width: 95%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 20px;
            box-shadow: inset 2px 5px 10px rgb(44, 40, 40);

        }

        select {
            width: calc(103% - 18px); /* Subtract padding and border */
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 20px;
            box-shadow: inset 2px 5px 10px rgb(44, 40, 40);

        }

        button {
            background-color: #e1440f;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            display: block;
            margin-top: 15px;
        }

        button:hover {
            background-color: #856829;
        }

        .centered {
            text-align: center;
        }

        .rainbow-hover {
            font-size: 16px;
            font-weight: 700;
            color: #ff7576;
            background-color: #293461;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 12px 24px;
            position: relative;
            line-height: 24px;
            border-radius: 9px;
            box-shadow: 0px 1px 2px #2B3044,
                0px 4px 16px #2B3044;
            transform-style: preserve-3d;
            transform: scale(var(--s, 1)) perspective(600px)
                rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg));
            perspective: 600px;
            transition: transform 0.1s;
        }

        .rainbow-hover:active {
            transition: 0.3s;
            transform: scale(0.93);
        }
    </style>
</head>
<body>
<img src="logo1.png" alt="" class="logo-form2">

<div class="class1" id="container">
    <div class="login-form" id="login-form">
        <img src="logo.png" alt="Police Logo" class="police-logo">
        <h1>Police Station Login</h1>

        <form id="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username"><b>Police Station Name:</b></label>
                <select name="station_name" id="station_name" required>
                    <option value="" id="option" disabled selected>-- Select Station Name --</option>
                    <?php
                    // Database connection parameters
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "police_registration";

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Fetch station names from ps table
                    $sql = "SELECT station_name FROM ps";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $station_name = $row["station_name"];
                            echo "<option value='$station_name'>$station_name</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No station names found</option>";
                    }

                    $conn->close();
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="pass"><b>Password:</b></label>
                <input type="password" id="pass" name="pass" placeholder="-- Enter password --" required>
            </div>
<br>
            <button type="submit" id="submit" class="rainbow-hover">Login</button>
        </form>
    </div>
</div>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "police_registration";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get input values
    $station_name = $_POST["station_name"];
    $password = $_POST["pass"];

    // SQL to fetch password for the selected station name
    $sql = "SELECT password FROM ps WHERE station_name='$station_name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $db_password = $row["password"];

        // Verify password
        if ($password === $db_password) {
            echo "<script>alert('Login successful!');</script>";
            echo "<script>window.location.href = 'ps_map.php';</script>";
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Station name not found.');</script>";
    }

    $conn->close();
}
?>
