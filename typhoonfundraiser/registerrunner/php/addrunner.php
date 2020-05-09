<?php
$team_name = $_GET['team_name'];

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

}if (isset($team_name)) {
    $team_name = mysqli_real_escape_string($mysqli,$team_name);
} else {
    die("Request invalid: team_name not found");
}
$access_key = rand(1,9999);
$sql = "SELECT access_key FROM access_keys";
$stmt = $mysqli->prepare($sql);
$stmt->execute(); // execute sql query
$result = $stmt->get_result(); // get the mysqli result
$access_keys = array();
while($row = $result->fetch_assoc()) {
    array_push($access_keys,$row['access_key']);
}

$repeat = true;
while($repeat == true){
    $repeat = false;
    for($x = 0; $x < (count($access_keys)); $x++) {
        if($access_key === $access_keys[$x]){
            echo "Repeat Access Key";
            $access_key = rand(1,9999);
            $repeat = true;
            break;
        }
    }
}

$table_name = str_replace(' ', '', $team_name);
$table_name = substr($table_name, 1, 30);


$sql = "INSERT INTO access_keys(access_key,team_name,table_name) VALUES ($access_key,'$team_name','$table_name')";
$stmt = $mysqli->prepare($sql);
$mysqli->error;
$stmt->execute();
$stmt->close();
// Assemble response
$response = array(
    "team_name" => $teamname,
    "table_name" => $tablename
);
echo json_encode($response);
$stmt->close();

$sql = "CREATE TABLE $table_name (
                         id int NOT NULL auto_increment,
                         run_timestamp timestamp default CURRENT_TIMESTAMP() NOT NULL,
                         run_distance float NOT NULL,
                         PRIMARY KEY (id)
);
";
echo $sql;
$stmt = $mysqli->prepare($sql);
$mysqli->error;
$stmt->execute();
$stmt->close();
$mysqli->close();

