<?php
// Start session
session_start();

// Check if the button data and officer name are sent through POST
if(isset($_POST['button']) && isset($_SESSION['officer_name'])) {
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

    // Get the button value and officer name from POST data
    $button = $_POST['button'];
    $officer_name = $_SESSION['officer_name'];

    // Debugging: Echo the button value and officer name
    echo "Button value: $button, Officer name: $officer_name <br>";

    // Get the current timestamp
    $clicked_at = date("Y-m-d H:i:s");

    // Insert the button value, officer name, and click time into the button_table
    $insertSql = "INSERT INTO button_table (officer_name, button, clicked_at) 
                  VALUES ('$officer_name', '$button', '$clicked_at')";

    // Debugging: Echo the SQL query for inspection
    echo "SQL Query: $insertSql <br>";

    if ($conn->query($insertSql) === TRUE) {
        echo "Button data inserted successfully.<br>";

        // Get the latitude and longitude of the clicked location from POST data
        $clicked_lat = $_POST['clicked_lat']; // Latitude of the clicked location
        $clicked_lon = $_POST['clicked_lon']; // Longitude of the clicked location

        // Query to fetch officers' coordinates from the coordinates table
        $sql = "SELECT officer_name, latitude, longitude FROM coordinates";
        $result = $conn->query($sql);

        // Loop through officers' coordinates and calculate distance
        while ($row = $result->fetch_assoc()) {
            $officer_name = $row['officer_name'];
            $officer_lat = $row['latitude'];
            $officer_lon = $row['longitude'];

            // Calculate distance between clicked location and officer's location
            $distance = calculateDistance($clicked_lat, $clicked_lon, $officer_lat, $officer_lon);

            // Debugging: Echo distance for inspection
            echo "Distance to $officer_name: $distance km<br>";

            // Check if the distance satisfies criteria and send pop-up message to the respective officer
            if ($distance <= 2) { // Assuming Low button is for 2km radius
                // Fetch the officer's name from the links table based on the officer_name
                $officer_query = "SELECT officer_name FROM links WHERE officer_name = '$officer_name'";
                $officer_result = $conn->query($officer_query);

                if ($officer_result->num_rows > 0) {
                    $officer_row = $officer_result->fetch_assoc();
                    $popup_officer_name = $officer_row['officer_name'];
                    $popupMessage = "Low: Officer $popup_officer_name is within 2km radius.";

                    // Send pop-up message
                    echo "<script>sendPopup('$popupMessage');</script>";
                } else {
                    echo "Officer not found in the links table.<br>";
                }
            }
        }
    } else {
        echo "Error inserting button data: " . $conn->error . "<br>";
    }

    $conn->close();
} else {
    echo "Button data or officer name not received.<br>";
}

// Function to calculate distance using Haversine formula
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $R = 6371; // Radius of the Earth in kilometers
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $R * $c; // Distance in kilometers
    return $distance;
}
?>
