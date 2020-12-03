<?php
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
session_start();


// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;


// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Get the current base URL
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
    $baseURL = "http://localhost/timesheets/index.php";
}
else {
        $baseURL = "http://unn-w16030249.newnumyspace.co.uk/timesheets/index.php";
}

$redirect = $baseURL;


// Check for logged in user
// If user is not logged
// redirtect them to where they came from
if (isset($_SESSION['username'])) {
    echo logoutUser($_SESSION['username'], $redirect);

} else {
    header('Location: ' . $redirect);
    exit();
}
echo getHTMLEnd();