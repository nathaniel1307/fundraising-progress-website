<?php
// script using unique link to get UAS name and runners

// Unique url to access particular db
$access_key = $_GET['access_key'];

// DB credentials
$host = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'Loughborough2IstanbulCycle';


// Connect to database
$mysqli = new mysqli($host,$user,$pass,$db);
/* check connection */
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


// DB request for runner data
$sql = "SELECT DISTINCT cyclists_name FROM $tablename ORDER BY cyclists_name ASC"; // SQL with parameters
$stmt = $mysqli->prepare($sql);
if ($stmt==false) {
    die("Database access failed check your URL, access key: $access_key, sql error: $mysqli->error");
}
$stmt->execute(); // execute sql query
$result = $stmt->get_result(); // get the mysqli result

$runners = $result->fetch_all(MYSQLI_ASSOC);
$rowsReturned = $result->num_rows;


// Assemble response
$response = array(
    "teamname" => $teamname,
    "runners" => $runners
);

echo json_encode($response);

$result->close();
$mysqli->close();
?> 