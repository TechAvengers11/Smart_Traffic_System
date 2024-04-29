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

// SQL query to fetch coordinates and officer's name from the alloted_data table
$sql = "SELECT c.latitude, c.longitude, a.officer_name
        FROM coordinates AS c
        INNER JOIN alloted_data AS a ON c.area = a.area";

$result = $conn->query($sql);

$coordinates = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $coordinates[] = [
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'officer_name' => $row['officer_name']
        ];
    }
} else {
    echo "0 results";
}

$conn->close();

// Return coordinates as JSON
echo json_encode($coordinates);
?>
