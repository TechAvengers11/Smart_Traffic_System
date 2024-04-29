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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $officer_name = $_POST['officer_name'];
    $area = $_POST['area'];

    // Check if officer_name already exists in alloted_data table
    $check_query = "SELECT * FROM alloted_data WHERE officer_name = '$officer_name'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // Officer already allotted, send response to display message
        echo json_encode(array("message" => "Officer Already Allotted!"));
    } else {
        // SQL query to insert data into the alloted_data table
        $insert_query = "INSERT INTO alloted_data (officer_name, area) VALUES ('$officer_name', '$area')";

        if ($conn->query($insert_query) === TRUE) {
            // Retrieve coordinates for the allotted area
            $get_coords_query = "SELECT latitude, longitude FROM coordinates WHERE area = '$area'";
            $coords_result = $conn->query($get_coords_query);

            if ($coords_result->num_rows > 0) {
                $row = $coords_result->fetch_assoc();
                $latitude = $row["latitude"];
                $longitude = $row["longitude"];

                // Return JSON response with latitude, longitude, and officer name
                echo json_encode(array(
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                    "officer_name" => $officer_name
                ));
            } else {
                echo json_encode(array("error" => "Error fetching coordinates for the area!"));
            }
        } else {
            echo json_encode(array("error" => "Error: " . $insert_query . "<br>" . $conn->error));
        }
    }
}

// Close connection
$conn->close();
?>
