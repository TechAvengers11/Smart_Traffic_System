<?php
// Step 1: Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "police_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Query the button_table to fetch officer_name and button of the latest entry
$sql = "SELECT officer_name, button FROM button_table ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

// Step 3: Check if there is a result
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $officer_name = $row['officer_name'];
    $button = $row['button'];

    // Step 4: Check if the button value is "Low" and display a popup message accordingly
    if ($button === "Medium" || $button === "High" || $button === "Emergency") {
        $popupMessage = "Officer Name: $officer_name, Button: $button";
        echo "<script>alert('$popupMessage');</script>";
    }
} 

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Button Click Example</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

   

        <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            background-image: url("background.jpg");
            background-position: center;
            background-size: 100%; 
        }

        h1 {
            color: #fff;
            text-align: center;
            font-size: 48px;
            margin-top: 20px;
        }

        .logo-form2 {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 140px;
            height: 140px;
        }

        .police-logo {
            width: 150px;
            height: auto;
            margin: 0 auto;
            display: block;
            margin-top: 100px;
        }

        /* Button Styles */
        .Btn {
            display: inline-block;
            width: 200px;
            height: 60px;
            margin: 20px;
            font-size: 24px;
            color: #fff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .Btn:hover {
            filter: brightness(110%);
        }

        /* Colorful buttons */
        .Btn.Low {
            background-color: #007bff;
            width: 900px;
        }

        .Btn.Medium {
            background-color: #28a745;
            width: 900px;

        }

        .Btn.High {
            background-color: #ffc107;
            width: 900px;

        }

        .Btn.Emergency {
            background-color: #dc3545;
            width: 900px;

        }

        /* CSS Styles */
.logout-btn {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #6c757d;
    color: #fff;
    padding: 12px 24px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    z-index: 999; /* Ensure the button stays above other elements */
}

.logout-btn:hover {
    background-color: #495057;
}


        /* Container Styles */
        .container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
    </style>
</head>
<body>
  
<img src="logo1.png" alt="" class="logo-form2">

  <img src="logo.png" alt="Police Logo" class="police-logo">
<h1>Police Officer Portal!</h1><br><br>
</button>
   <div class="container">
        <img src="logo1.png" alt="" class="logo-form2">
        
        <button class="Btn Low" onclick="sendButtonClick('Low')">Low</button>
        <button class="Btn Medium" onclick="sendButtonClick('Medium')">Medium</button>
        <button class="Btn High" onclick="sendButtonClick('High')">High</button>
        <button class="Btn Emergency" onclick="sendButtonClick('Emergency')">Emergency</button>
    </div>

    <button class="logout-btn" onclick="logout()">Logout</button>

    <script>
        // Function to handle logout
        function logout() {
            $.ajax({
                url: 'logout.php',
                type: 'POST',
                success: function(response) {
                    console.log(response);
                    // Redirect to login page after successful logout
                    window.location.href = 'po.php';
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error occurred. Check console for details.');
                }
            });
        }

        // Function to display the popup message
        function sendPopup(message) {
            alert(message);
            // You can customize how you want to display the popup here
        }

        // Function to handle button clicks
        function sendButtonClick(buttonType) {
            alert(buttonType); // Display button label in a popup
            $.ajax({
                url: 'update_login.php',
                type: 'POST',
                data: { button: buttonType },
                success: function(response) {
                    console.log(response);
                    // No need for alert(response); here
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error occurred. Check console for details.');
                }
            });
        }
// Automatic page refresh every 60 seconds
setTimeout(function() {
            location.reload();
        }, 20000); // 60 seconds
    </script>
</body>
</html>
