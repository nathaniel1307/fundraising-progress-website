<?php
// script using unique link to access a particular table


// Read request values (ie values sent by datatables from client)
$draw = $_GET['draw'];
$rowStart = $_GET['start'];
$rowsToDisplay = $_GET['length']; // Rows display per page

$sortColIndex = $_GET['order'][0]['column']; // Column index to sort
$sortOrder = $_GET['order'][0]['dir']; // asc or desc
$sortColData = $_GET['columns'][$sortColIndex]['data']; // sort column data source
$sortColName = $_GET['columns'][$sortColIndex]['name']; // sort column name
// $search = $_GET['search']['value']; // Search value (SEARCHING DIABLED)
// Unique url to access particular db
$access_key = $_GET['access_key'];


// DB credentials
$host = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'typhoonfundraiser';


// Connect to database
$mysqli = new mysqli($host,$user,$pass,$db);
/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

// DB request for database name from access key
if (isset($access_key)) {
    //$acceptableKeyValues = array('44473ea3203ce874a27f7f8ef3e4a0f9', '0257c95031a6bcb985001bee1bdcaab7');
    $key = mysqli_real_escape_string($mysqli,$access_key);
    /*if (!in_array($access_key, $acceptableKeyValues)) {
        die("Wrong URL, access key: $access_key not found");
    } */
}
$sql = "SELECT table_name FROM access_keys WHERE access_key='$key'"; // SQL with parameters
$stmt = $mysqli->prepare($sql);
if ($stmt==false) {
    die("Database access failed check your URL, access key: $access_key, sql error: $mysqli->error");
}
//$mysqli->error;
$stmt->execute(); // execute sql query
$result = $stmt->get_result(); // get the mysqli result

$row = $result->fetch_assoc();
$tablename = $row["table_name"];

// DB request for runner data
// Sanitise input
if (isset($sortColIndex)) {
    $acceptableSortValues = array('run_timestamp', 'run_distance');
    $sort = mysqli_real_escape_string($mysqli,$sortColData);
    if (!in_array($sortColData, $acceptableSortValues)) {
        $sort = 'run_timestamp';
    }
}
else {
    $sort = 'run_timestamp';
}
if (isset($sortOrder)) {
    if ($sortOrder == "asc") {
        $sortDir = 'ASC';
    }
    else {
        $sortDir = 'DESC';
    }
}
else {
    $sortDir = 'ASC';
}
$sql = "SELECT run_timestamp, run_distance FROM $tablename ORDER BY $sort $sortDir"; // SQL with parameters
$stmt = $mysqli->prepare($sql);
if ($stmt==false) {
    die("Database access failed check your URL, access key: $access_key, sql error: $mysqli->error");
}
$mysqli->error;
$stmt->execute(); // execute sql query
$result = $stmt->get_result(); // get the mysqli result

$data = $result->fetch_all(MYSQLI_ASSOC);
$rowsReturned = $result->num_rows;

// Assemble response
$response = array(
    "draw" => intval($draw), // draw counter 
    "recordsTotal" => $rowsReturned, // Total records before filtering (i.e. the total number of records in the database) 
    "recordsFiltered" => $rowsReturned, // Total records, after filtering 
    "data" => $data // data to be displayed in the table
);

echo json_encode($response);

$result->close();
$mysqli->close();
?>