<?php
// script to get distance run by each Team so far (for leaderboard)

// DB credentials
include "../../DBCred.php";
$db = 'Loughborough2IstanbulCycle';

// Connect to database
$mysqli = new mysqli($host,$user,$pass,$db);
// check connection 
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$sql = "SELECT team,distance FROM total_distances ORDER BY distance DESC";
$stmt = $mysqli->prepare($sql);
$stmt->execute(); // execute sql query
$result = $stmt->get_result(); // get the mysqli result

$data = $result->fetch_all(MYSQLI_ASSOC);

// Assemble response
$response = array(
    "data" => $data // Team and distance data
); 
echo json_encode($response);

$result->close();
$mysqli->close();
?>