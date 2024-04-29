<?php
session_start(); // Start session

// Check if user is logged in
if (isset($_SESSION["officer_name"])) {
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

    // Update exitted_at time in login table for the current session
    $officer_name = $_SESSION["officer_name"];
    $exit_time = date("Y-m-d H:i:s");
    $sql_update = "UPDATE login SET exitted_at='$exit_time' WHERE officer_name='$officer_name' AND exitted_at IS NULL";
    if ($conn->query($sql_update) === TRUE) {
        // Unset session variables and destroy session
        session_unset();
        session_destroy();
        echo "Logout successful.";
    } else {
        echo "Error updating exit time.";
    }

    $conn->close();
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
?>
