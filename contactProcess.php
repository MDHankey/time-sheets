<?php
/**
 * Created by PhpStorm.
 * User: chris.gooch
 * Date: 13/03/2019
 * Time: 13:18
 */

require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
session_start();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
// Page title
$pageTitle = 'Timesheets';

// Get the correct page header depending on the users role and wheter or not they are logged in
if (!isset($loggedIn)) {
    echo getHTMLHeader($pageTitle, $loggedIn);

} elseif (isset($userRole) && $userRole == 1) {
    echo getHTMLUserHeader($pageTitle, $loggedIn);

} elseif (isset($userRole) && $userRole == 2) {
    echo getHTMLAdminHeader($pageTitle, $loggedIn);

} else {
    echo getHTMLHeader($pageTitle, $loggedIn);
}


// Creates connection to database
$dbConn = PDODB::getConnection();

// POST Retrieval of form input
$name = trim(filter_has_var(INPUT_POST, 'txtName') ? $_POST['txtName'] : null);
$email = trim(filter_has_var(INPUT_POST, 'txtEmail') ? $_POST['txtEmail'] : null);
$phone = trim(filter_has_var(INPUT_POST, 'txtPhone') ? $_POST['txtPhone'] : null);
$message = trim(filter_has_var(INPUT_POST, 'txtMsg') ? $_POST['txtMsg'] : null);


// Display error message if any of mandatory fields left blank
if (empty($name) || empty($email) || empty($phone) || empty($message)) {
    echo("<div class='jumbotron text-center'>
        <h1>There was an error handling your request</h1>
    </div>
    
            <div class='container'>
            <div class='col-sm-12'><p>Please check all fields are correct and resubmit your request</p></div>");
    // Else no errors so add query to database & display success message
} else {

    // Create SQL query
    $contactUsSQL = "INSERT INTO timesheets_contact(contact_name, contact_email, contact_number, contact_message)
                      VALUES ('$name', '$email', '$phone', '$message')";
    // Prepare the SQL statement
    $contactUsStatement = $dbConn->prepare($contactUsSQL);
    // Execute the prepared statement
    $contactUsStatement->execute();





    echo("<div class='jumbotron text-center'>
        <h1>Query submitted</h1>
    </div>  
            <div class='container'>
            <div class='col-sm-12'><p>Your query has been successfully submitted and we have received your request</p></div>");
}

// End container div
echo("</div>");


// Display errors for login form
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p class='error'>$error</p>";

    }
}
?>

<?php

echo getHTMLFooter();
getHTMLEnd();

