<?php
/* csv download
 * admin page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
require_once 'functions/reportQueries.php';
session_start();

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Csv Report Download ';

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

$departments = getDepartments($dbConn, $loggedIn);
$projects = getProjects($dbConn, $loggedIn);

$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;
$departmentSet = isset( $_GET['department']) &&  $_GET['department'] != "all";
$projectSet    = isset( $_GET['project']) &&  $_GET['project'] != "all";


if ($departmentSet && $projectSet &&  isset( $_GET['startDate']) && isset( $_GET['endDate'])){
    array_to_csv_download(sqlQueryToPhpArray($dbConn, $loggedIn, getDepartmentProjectEmployeeTimeBetweenTwoDates($_GET['department'], $_GET['project'], $_GET['startDate'],  $_GET['endDate'])),"TimeSheetsReportData.csv",",");
}
else if ($projectSet &&  isset( $_GET['startDate']) && isset( $_GET['endDate'])){
    array_to_csv_download(sqlQueryToPhpArray($dbConn, $loggedIn, getProjectEmployeeTimeBetweenTwoDates($_GET['project'], $_GET['startDate'],  $_GET['endDate'])),"TimeSheetsReportData.csv",",");
}
else if ($departmentSet &&  isset( $_GET['startDate']) && isset( $_GET['endDate'])){
    array_to_csv_download(sqlQueryToPhpArray($dbConn, $loggedIn, getDepartmentEmployeeTimeBetweenTwoDates($_GET['department'], $_GET['startDate'],  $_GET['endDate'])),"TimeSheetsReportData.csv",",");
}
else if ( isset( $_GET['startDate']) && isset( $_GET['endDate'])){
    array_to_csv_download(sqlQueryToPhpArray($dbConn, $loggedIn, getAllEmployeeTimeBetweenTwoDates($_GET['startDate'],  $_GET['endDate'])),"TimeSheetsReportData.csv",",");
}
else {
    array_to_csv_download(sqlQueryToPhpArray($dbConn, $loggedIn, getAllEmployeeTime()),"TimeSheetsReportData.csv",",");
}