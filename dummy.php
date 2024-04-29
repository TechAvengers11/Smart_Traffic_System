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

// Fetch officer names from button_table
$sql = "SELECT officer_name FROM button_table";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $officer_name = $row["officer_name"];
        
        // Check if officer_name exists in alloted_data table
        $sql_alloted = "SELECT area FROM alloted_data WHERE officer_name = '$officer_name'";
        $result_alloted = $conn->query($sql_alloted);

        if ($result_alloted->num_rows > 0) {
            // Officer name exists in alloted_data table, fetch area
            $row_alloted = $result_alloted->fetch_assoc();
            $area = $row_alloted["area"];
            
            // Display officer name, area, and button in a popup
            echo "<div>";
            echo "<p>Officer Name: $officer_name</p>";
            echo "<p>Area: $area</p>";
            echo "<button onclick=\"displayInfo('$officer_name', '$area')\">Show Info</button>";
            echo "</div>";
        } else {
            // Officer name does not exist in alloted_data table
            echo "<div>";
            echo "<p>Officer Name: $officer_name</p>";
            echo "<p>No area alloted</p>";
            echo "</div>";
        }
    }
} else {
    echo "No officer names found in button_table";
}

$conn->close();
?>
