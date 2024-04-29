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
    <title>Police Station Duty Allotment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url("background.jpg");
            background-size: 100%;
            background-position: center;
        }
        
        h1{
            color: white;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card1,
        .card2 {
            margin-bottom: 20px;
        }

        .card1 {
            width: 1300px;
            height: 370px;
            margin-right: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: GREY;
        }

        .card2 {
            width: 1800px;
            height: 810px;
            background-color: #fff;
            border-radius: 10px;    
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: GREY;
            margin-top: 15px;
        }

        #map {
            width: 100%;
            height: 680px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .form-select {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }


        .card-title {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .police-logo {
        width: 150px;
        height: auto;
        margin: 0 auto;
        display: block;
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
  margin-right: auto;
}
.logo-form2{
            position: fixed;
            top: 0;
            height: 160px;
            width: 160px; 
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
  transform: translate(2px ,2px);
}
/* CSS Styles */
.logout-btn {
    position: fixed;
    top: 20px;
    right: 40px;
    width: 80px;
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
    </style>
</head>

<body>
<img src="logo1.png" alt="" class="logo-form2">

<button class="logout-btn" onclick="redirectToPage()">
  
  <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
  
  <div class="text">Logout</div>
</button>
<img src="logo.png" alt="Police Logo" class="police-logo">
<h1><b>Police Station Portal!</b></h1>
<br><br>
    <div class="container">
        <div class="row">
            <!-- Duty Allotment Card -->
            <div class="card1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><b>Police Station Duty Allotment</b></h4>
                        <form id="dutyForm">
                            <div class="mb-3">
                                <label for="officer_name" class="form-label">Select Police Officer:</label>
                                <select name="officer_name" id="officer_name" class="form-select" required>
                                    <?php
                                    // Database connection
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

                                    // SQL query to select officer names from the po table
                                    $sql = "SELECT officer_name FROM po";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        // Output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row["officer_name"] . "'>" . $row["officer_name"] . "</option>";
                                        }
                                    } else {
                                        echo "<option value='' disabled>No officers found</option>";
                                    }

                                    // Close connection
                                    $conn->close();
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="area" class="form-label">Select Area:</label>
                                <select name="area" id="area" class="form-select" required>
                                    <?php
                                    // Database connection
                                    $conn = new mysqli($servername, $username, $password, $dbname);

                                    // Check connection
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }

                                    // SQL query to select areas from the coordinates table
                                    $sql = "SELECT area FROM coordinates";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        // Output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row["area"] . "'>" . $row["area"] . "</option>";
                                        }
                                    } else {
                                        echo "<option value='' disabled>No areas found</option>";
                                    }

                                    // Close connection
                                    $conn->close();
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn-primary">Allot Duty</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Map Display Card -->
            <div class="card2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Duty Location Map</b></h5>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([19.0760, 72.8777], 6);
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });
        osm.addTo(map);

        var markers = [];

        function fetchCoordinates() {
            fetch('demo.php')
                .then(response => response.json())
                .then(data => {
                    plotCoordinates(data);
                })
                .catch(error => {
                    console.error('Error fetching coordinates:', error);
                });
        }

        function plotCoordinates(coordinates) {
            markers.forEach(function (marker) {
                map.removeLayer(marker);
            });
            markers = [];

            var groupedCoordinates = {};
            coordinates.forEach(function (coord) {
                var key = coord.latitude + ',' + coord.longitude;
                if (!groupedCoordinates[key]) {
                    groupedCoordinates[key] = [];
                }
                groupedCoordinates[key].push(coord.officer_name);
            });

            for (var key in groupedCoordinates) {
                if (groupedCoordinates.hasOwnProperty(key)) {
                    var latLng = key.split(',');
                    var latitude = parseFloat(latLng[0]);
                    var longitude = parseFloat(latLng[1]);
                    var officerNames = groupedCoordinates[key].join('<br>');

                    var marker = L.marker([latitude, longitude]).bindPopup(officerNames);
                    markers.push(marker);
                }
            }

            markers.forEach(function (marker) {
                marker.addTo(map);
            });
        }

        fetchCoordinates();
        setInterval(fetchCoordinates, 5000);

        document.getElementById('dutyForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('process.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    var latitude = parseFloat(data.latitude);
                    var longitude = parseFloat(data.longitude);
                    var officerName = data.officer_name;

                    markers.forEach(function (marker) {
                        map.removeLayer(marker);
                    });
                    markers = [];

                    var newMarker = L.marker([latitude, longitude]).bindPopup(officerName);
                    markers.push(newMarker);
                    newMarker.addTo(map);

                    map.setView([latitude, longitude], 10);

                    alert('Duty successfully allotted!');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
        // Redirect function
        function redirectToPage() {
            // Redirect to desired page URL
            window.location.href = 'ps.php'; 
        }

        
        // Automatic page refresh every 60 seconds
        setTimeout(function() {
            location.reload();
        }, 20000); // 60 seconds
    </script>
</body>

</html>
