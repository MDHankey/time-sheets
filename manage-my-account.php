<?php
/* Manage my account 
 * user page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'functions/account-functions.php';
require_once 'functions/job-functions.php';
require_once 'functions/team-functions.php';
require_once 'functions/department-functions.php';
require_once 'class/PDODB.php';
session_start();

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Manage My Account';

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Get the correcct page header depending on the users current role
// If user is not logged in display message to user telling them to log in
if (!isset($loggedIn)) {
    echo getHTMLHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you must be logged to access this page. Please login <a href='login.php'>here</a> to login to your account. If you don't currently have an account please contact your system administrator to have one created for you.";

} elseif (isset($userRole) && $userRole == 2) {        // User level
    echo getHTMLUserHeader($pageTitle, $loggedIn);

} elseif (isset($userRole) && $userRole == 1) {        // Admin level
    echo getHTMLAdminHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you must be logged in with a User role in order to access this page. Please select the user role <a href='select-role.php'>here</a> to change your account role.";

} else {
    echo getHTMLHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you must be logged in with a User role in order to access this page. Please select the user role <a href='select-role.php'>here</a> to change your account role.";
}

?>
    <div class="jumbotron text-center">
        <h1><?php echo $pageTitle; ?></h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php

                // If not logged in show text
                if (isset($errorText)) {
                    echo "<p>$errorText</p>";

                } else {
                    // The main page content if user has correct permissions
                    $userID = getUserIDByUsername($dbConn, $_SESSION["username"]);
                    $personID = getPersonIDByUserID($dbConn, $userID);

                    $username = getUsername($dbConn, $personID);
                    $title = getTitle($dbConn, $personID);
                    $forename = getForename($dbConn, $personID);
                    $surname = getSurname($dbConn, $personID);
                    $phoneNumber = getPhoneNumber($dbConn, $personID);
                    $email = getEmail($dbConn, $personID);
                    $addressLine1 = getAddressLine1($dbConn, $personID);
                    $addressLine2 = getAddressLine2($dbConn, $personID);
                    $addressLine3 = getAddressLine3($dbConn, $personID);
                    $addressLine4 = getAddressLine4($dbConn, $personID);
                    $addressLine5 = getAddressLine5($dbConn, $personID);
                    $postCode = getPostcode($dbConn, $personID);
                    $dateOfBirth = getDateOfBirth($dbConn, $personID);
                    $contractedHours = getContractedHours($dbConn, $personID);
                    $job = getJob($dbConn, getJobByAccountID($dbConn, $personID));
                    $team = getTeam($dbConn, getTeamByAccountID($dbConn, $personID));
                    $department = getDepartment($dbConn, getDepartmemtByAccountID($dbConn, $personID));

                    echo("
                    <div class=\"container\">
                        <div class=\"row justify-content-center align-items-center\">
                            <div class='col-sm-6'>
                                <form> 
                                    <h3 class='text-center'>Your Account Details</h3>
                                    <h5 class='manageAccountFormTitles'>Login Details</h5>
                                    <div class='form-row'>
                                        <div class='col'> 
                                            <label for='username'>Username:</label>
                                            <input type='text' class='form-control' name='username' value='$username' readonly />
                                        </div>
                                    </div>   
                                    <h5 class='manageAccountFormTitles'>Personal Information</h5>
                                    <div class='form-row'>
                                        <div class='col-2'> 
                                            <label for='title'>Title:</label>
                                            <input type='text' class='form-control' name='title' value='$title' readonly />
                                        </div>
                                     
                                        <div class='col-5'> 
                                            <label for='forename'>Forename:</label>
                                            <input type='text' class='form-control' name='forename' value='$forename' readonly />
                                        </div>
                                        
                                        <div class='col-5'> 
                                            <label for='surname'>Surname:</label>
                                            <input type='text' class='form-control' name='surname' value='$surname' readonly />
                                        </div>
                                    </div>   
                                    <div class='form-row'>
                                    <div class='col'> 
                                            <label for='dateOfBirth'>Date of Birth:</label>
                                            <input type='date' class='form-control' name='dateOfBirth' value='$dateOfBirth' readonly />
                                        </div>
                                    </div> 
                                    <h5 class='manageAccountFormTitles'>Contact Information</h5>
                                    <div class='form-row'>
                                        <div class='col-6'> 
                                            <label for='phoneNumber'>Phone Number:</label>
                                            <input type='text' class='form-control' name='phoneNumber' value='$phoneNumber' readonly />
                                        </div>
                                        
                                        <div class='col-6'> 
                                            <label for='email'>Email:</label>
                                            <input type='text' class='form-control' name='email' value='$email' readonly />
                                        </div>
                                    </div>   
                                    <h5 class='manageAccountFormTitles'>Address Details</h5>
                                    <div class='form-row'>
                                        <div class='col-6'> 
                                            <label for='addressLine1'>Address Line 1:</label>
                                            <input type='text' class='form-control' name='addressLine1' value='$addressLine1' readonly />
                                        </div>
                                        
                                        <div class='col-6'> 
                                            <label for='addressLine2'>Address Line 2:</label>
                                            <input type='text' class='form-control' name='addressLine2' value='$addressLine2' readonly />
                                        </div>
                                    </div>
                                    <div class='form-row'>    
                                        <div class='col-6'> 
                                            <label for='addressLine3'>Address Line 3:</label>
                                            <input type='text' class='form-control' name='addressLine3' value='$addressLine3' readonly />
                                        </div>    
                                        <div class='col-6'> 
                                            <label for='addressLine4'>Address Line 4:</label>
                                            <input type='text' class='form-control' name='addressLine4' value='$addressLine4' readonly />
                                        </div>
                                    </div>  
                                    <div class='form-row'>  
                                        <div class='col-6'> 
                                            <label for='addressLine5'>Address Line 5:</label>
                                            <input type='text' class='form-control' name='addressLine5' value='$addressLine5' readonly />
                                        </div>
                                        
                                        <div class='col-6'> 
                                            <label for='postCode'>Post Code:</label>
                                            <input type='text' class='form-control' name='postCode' value='$postCode' readonly />
                                        </div>
                                    </div>    
                                    <h5 class='manageAccountFormTitles'>Employment Information</h5>
                                    <div class='form-row'>   
                                        <div class='col-6'> 
                                            <label for='job'>Job:</label>
                                            <input type='text' class='form-control' name='job' value='$job' readonly />
                                        </div>
                                        <div class='col-6'> 
                                            <label for='contractedHours'>Contracted Hours:</label>
                                            <input type='number' class='form-control' name='contractedHours' value='$contractedHours' readonly />
                                        </div>
                                         
                                    </div>    
                                    <div class='form-row'>
                                        <div class='col-6'> 
                                            <label for='team'>Team:</label>
                                            <input type='text' class='form-control' name='team' value='$team' readonly />
                                        </div>
                                        
                                        <div class='col-6'> 
                                            <label for='department'>Department:</label>
                                            <input type='text' class='form-control' name='department' value='$department' readonly />
                                        </div>
                                    </div>    
                                </form>
                            </div>
                        </div>
                        
                        <div style='text-align: center; padding-top: 15px' >
                            <a href='reset-password.php'>Reset your password</a>
                            <p>Please contact your administrator if any of the above details are incorrect</p>
                        </div>
                    </div>
                    
                    ");


                }

                ?>
            </div>
        </div>
    </div>
<?php
echo getHTMLFooter();
getHTMLEnd();