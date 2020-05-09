<?php
// script to get distance run by each UAS so far (for leaderboard)

// DB credentials
$host = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'typhoonfundraiser';

// Connect to database
$mysqli = new mysqli($host,$user,$pass,$db);
// check connection 
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$teamIDArr = array();
$teamNameArr = array();
$teamDistArr = array();

$sql = "SELECT TeamID, TeamName FROM Teams";
$stmt = $mysqli->prepare($sql);
$stmt->execute(); // execute sql query
$result = $stmt->get_result(); // get the mysqli result
while ($row = mysqli_fetch_row($result)) {
    array_push($teamIDArr, $row[0]);
    array_push($teamNameArr, $row[1]);
}



for($x=0;$x<(count($teamIDArr));$x++){
    $sql = "SELECT SUM(distance) FROM Activities WHERE AthleteID IN (SELECT AthleteID FROM Athletes WHERE TeamID=$teamIDArr[$x])";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    array_push($teamDistArr,$row[0]);
}

echo $teamIDArr[0];
echo $teamNameArr[0];
echo $teamDistArr[0];
echo $teamIDArr[1];
echo $teamNameArr[1];
echo $teamDistArr[1];









// Assemble response
$response = array(
    //"TeamID" => $TeamID,
    //"TeamName" => $TeamName, // Team and distance data
    //"Distance" => $Distance
); 
//echo json_encode($response);

$result->close();
$mysqli->close();
?>