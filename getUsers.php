<?php
// Load functions.php
require_once('functions/functions.php');
require_once ('class/PDODB.php');
$term = isset($_REQUEST['term']) ? $_REQUEST['term'] : null;

// Creates connection to database
$db = PDODB::getConnection();
// SELECT userID and username from timesheets_user
$sqlQuery = "SELECT user_id as ID, 
              username as value
              from timesheets_user 
              order by user_id";
$stmt = $db->prepare($sqlQuery);
// get $term safely from the request stream
$stmt->execute(array(':term' => "%{$term}%"));
$array = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
// Echo the JSON representation of the array
echo json_encode($array);
