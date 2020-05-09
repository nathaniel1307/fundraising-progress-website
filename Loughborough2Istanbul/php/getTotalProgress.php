<?php
// script to get total distance run so far

// DB credentials
$host = '';
$user = '';
$pass = '';
$db = '';

// Connect to database
$mysqli = new mysqli($host,$user,$pass,$db);
// check connection 
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$sql = "SELECT SUM(distance) as total FROM total_distances";
$stmt = $mysqli->prepare($sql);
$stmt->execute(); // execute sql query
$result = $stmt->get_result(); // get the mysqli result

$row = $result->fetch_assoc();
$totaldist = round($row["total"], 1);

// Assemble response
$response = array(
    "totaldist" => $totaldist
); 
echo json_encode($response);

$result->close();
$mysqli->close();
?>