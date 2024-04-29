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
    if ($button === "High" || $button === "Emergency") {
        $popupMessage = "Officer Name: $officer_name, Button: $button";
        echo "<script>alert('$popupMessage');</script>";
    } else {
        echo "<script>alert('No latest Updates!');</script>";
    }
} else {
    echo "No data found in the button_table.";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CP Office Map</title>

    <!-- leaflet css  -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url("background.jpg");
            background-size: cover;
            background-position: center;
        }

        .card {
            width: 900px; /* Set initial width */
            height: 600px; /* Set initial height */
            margin: 20px auto; /* Center the card */
            margin-top: 20px;
        }

        #map {
            width: 100%;
            height: 100%; /* Use 100% height of parent */
        }

        .police-logo {
            width: 150px;
            height: auto;
            margin: 0 auto;
            display: block;
        }

        .logo-form2 {
            position: fixed;
            top: 0;
            height: 150px;
            width: 150px;
            margin-left: auto;
        }

        h1 {
            text-align: center;
            color: aliceblue;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #333;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .Btn {
            --black: #000000;
            --ch-black: #141414;
            --eer-black: #1b1b1b;
            --night-rider: #2e2e2e;
            --white: #ffffff;
            --af-white: #f3f3f3;
            --ch-white: #e1e1e1;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 45px;
            height: 45px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition-duration: .3s;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
            background-color: var(--af-white);
            margin-left: auto;
        }

        /* plus sign */
        .sign {
            width: 100%;
            transition-duration: .3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sign svg {
            width: 17px;
        }

        .sign svg path {
            fill: var(--night-rider);
        }

        /* text */
        .text {
            position: absolute;
            right: 0%;
            width: 0%;
            opacity: 0;
            color: var(--night-rider);
            font-size: 1.2em;
            font-weight: 600;
            transition-duration: .3s;
        }

        /* hover effect on button width */
        .Btn:hover {
            width: 125px;
            border-radius: 5px;
            transition-duration: .3s;
        }

        .Btn:hover .sign {
            width: 30%;
            transition-duration: .3s;
            padding-left: 20px;
        }

        /* hover effect button's text */
        .Btn:hover .text {
            opacity: 1;
            width: 70%;
            transition-duration: .3s;
            padding-right: 10px;
        }

        /* button click effect*/
        .Btn:active {
            transform: translate(2px, 2px);
        }
    </style>
</head>

<body>
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
    ?>

    <img src="logo1.png" alt="" class="logo-form2">
    <button class="Btn" onclick="redirectToPage()">

        <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>

        <div class="text">Logout</div>
    </button>
    <img src="logo.png" alt="Police Logo" class="police-logo">
    <h1><b>CP Office Portal!</b></h1><br>
    <div class="container">
        <div class="card">
            <div id="map"></div>
        </div>
    </div>

    <!-- leaflet js  -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Map initialization 
        var map = L.map('map').setView([19.0760, 72.8777], 6);

        //osm layer
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });
        osm.addTo(map);

        var markers = [];
        var positionCounter = 1;

        function plotCoordinates(coordinates) {
            // Clear previous markers
            markers.forEach(function(marker) {
                map.removeLayer(marker);
            });
            markers = [];

            // Group coordinates by location
            var groupedCoordinates = {};
            coordinates.forEach(function(coord) {
                var key = coord.latitude + ',' + coord.longitude;
                if (!groupedCoordinates[key]) {
                    groupedCoordinates[key] = [];
                }
                groupedCoordinates[key].push(coord.officer_name);
            });

            // Plot new markers
            for (var key in groupedCoordinates) {
                if (groupedCoordinates.hasOwnProperty(key)) {
                    var latLng = key.split(',');
                    var latitude = parseFloat(latLng[0]);
                    var longitude = parseFloat(latLng[1]);
                    var officerNames = groupedCoordinates[key].join('<br>'); // Concatenate officer names with line breaks

                    var marker = L.marker([latitude, longitude]).bindPopup(officerNames);
                    markers.push(marker);
                }
            }

            // Add markers to the map
            markers.forEach(function(marker) {
                marker.addTo(map);
            });
            positionCounter++;
        }

        // Plot the initial coordinates fetched from PHP
        plotCoordinates(<?php echo json_encode($coordinates); ?>);

        // Fetch updated coordinates every 5 seconds
        setInterval(function() {
            plotCoordinates(<?php echo json_encode($coordinates); ?>);
        }, 5000);

        // Redirect function
        function redirectToPage() {
            // Redirect to desired page URL
            window.location.href = 'cp.php';
        }

        // Automatic page refresh every 60 seconds
        setTimeout(function() {
            location.reload();
        }, 20000); // 60 seconds
    </script>
</body>

</html>
