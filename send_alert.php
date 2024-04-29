<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Message to Police Officers</title>
</head>
<body>
    <h1>Send Message to Police Officers</h1>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <button type="submit" name="low">Low</button>
        <button type="submit" name="medium">Medium</button>
    </form>
    
    <div>
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $dbname = "police_registration";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Function to calculate distance between two points
        function distance($lat1, $lon1, $lat2, $lon2) {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $kilometers = $miles * 1.609344;
            return $kilometers;
        }

        // Check which button was clicked
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["low"])) {
                $radius = 2; // 2 km radius
            } elseif (isset($_POST["medium"])) {
                $radius = 5; // 5 km radius
            }

            // Fetch police officers within the specified radius
            $sql = "SELECT po.officer_name, po.contact, co.longitude AS officer_longitude, co.latitude AS officer_latitude,
                    ad.area AS allotted_area, ad.longitude AS allotted_longitude, ad.latitude AS allotted_latitude
                    FROM po
                    JOIN coordinates co ON po.station_name = co.area
                    JOIN alloted_data ad ON po.officer_name = ad.officer_name";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Calculate distance between police officer and allotted area
                    $distance = distance($row["officer_latitude"], $row["officer_longitude"], $row["allotted_latitude"], $row["allotted_longitude"]);
                    
                    // Check if officer is within the specified radius
                    if ($distance <= $radius) {
                        echo "Message sent to Officer " . $row["officer_name"] . " (Contact: " . $row["contact"] . ") within " . $radius . " km radius. <br>";
                    }
                }
            } else {
                echo "No police officers found.";
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
