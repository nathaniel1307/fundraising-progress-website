<?php
// script to add run for a particular team

$access_key = $_GET['access_key']; // Unique url to access particular db
$run_name_raw = $_GET['name']; // runner's full name
$run_dist_raw = $_GET['distance'];  // distance submitted

// DB credentials
include "../../../DBCred.php";
$db = 'Loughborough2IstanbulCycle';

// Connect to database
$mysqli = new mysqli($host,$user,$pass,$db);
// check connection 
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

// DB request for database name from access key
if (isset($access_key)) {
    //$acceptableKeyValues = array('5a1621e4ef94fd61e4dc95c8ea5dac71', '44473ea3203ce874a27f7f8ef3e4a0f9', '0257c95031a6bcb985001bee1bdcaab7');
    $key = mysqli_real_escape_string($mysqli,$access_key);
    //if (!in_array($access_key, $acceptableKeyValues)) {
    //    die("Wrong URL, access key: $access_key not found");
    //}
}
$sql = "SELECT team_name,table_name FROM accesskeys WHERE access_key='$key'"; // SQL with parameters
$stmt = $mysqli->prepare($sql);
$mysqli->error;
$stmt->execute(); // execute sql query
$result = $stmt->get_result(); // get the mysqli result

$row = $result->fetch_assoc();
$teamname = $row["team_name"];
$tablename = $row["table_name"];


// DB request for inserting new run into db
if (isset($run_name_raw)) {
    $run_name = mysqli_real_escape_string($mysqli,$run_name_raw);
} else {
    die("Request invalid: run_name not found");
}
if (isset($run_dist_raw)) {
    $run_dist = mysqli_real_escape_string($mysqli,$run_dist_raw);
} else {
    die("Request invalid: run_dist not found");
}

date_default_timezone_set('Europe/London');
$datetimeObj = new DateTime('now');
$timestr =  $datetimeObj->format('Y-m-d H:i:s');
$stmt = $mysqli->prepare("INSERT INTO $tablename(timestamp,cyclists_name,distance) VALUES ('$timestr','$run_name',$run_dist)");
$stmt->execute();
$stmt->close();

// Assemble response
$response = array(
    "uasname" => $teamname,
    "run_name_raw" => $run_name_raw,
    "run_dist_raw" => $run_dist_raw
); 
echo json_encode($response);


// CALCULATE NEW TOTAL
$sql = "SELECT SUM(distance) as sumdist FROM $tablename";
$stmt = $mysqli->prepare($sql);
$stmt->execute(); // execute sql query
$result = $stmt->get_result(); // get the mysqli result

$row = $result->fetch_assoc();
$sumdist = round($row["sumdist"], 3);

// UPDATE TOTAL
$sql = "UPDATE total_distances SET distance=$sumdist WHERE team='$teamname'";
if ($mysqli->query($sql) === TRUE) {
    //echo "Record updated successfully";
} else {
    //echo "Error updating record: " . $mysqli->error;
}

$result->close();
$mysqli->close();


?>