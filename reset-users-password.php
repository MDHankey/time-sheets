<?php
/* Reset Users password
 * admin page
 * requires logged on
 */
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'functions/email-functions.php';
require_once 'class/PDODB.php';
session_start();

// Function to get all of the details for accounts
function generateNewPassword($dbConn, $username = null, $toemail){
    
    $rand = substr(md5(microtime()),rand(0,26),10);
    $temporaryPassword = password_hash($rand, PASSWORD_DEFAULT);
    
    
    // Try to carry out the database entries
    try{
        $sqlQuery = 'UPDATE timesheets_user SET passwordHash = :temporaryPassword
                                          WHERE username = :username';
        
        // Prepare the query
        $stmt = $dbConn->prepare($sqlQuery);
        
        // Execute the query
        $stmt->execute(array(':temporaryPassword' => $temporaryPassword,
                             ':username' => $username));
        
        if ($stmt){
            $body = "$username,
Your password has been reset by your administrator. 
Your new temporary password is: $rand.
Upon logging in please reset this password with your own personal password under the manage my account section.

Best wishes,
Northumbria Timesheets";
                
            
            sendEmail($toemail, 'Northumbria Timesheets: Temporary Password', $body);
            
            
            return true;
        }
        
        
        
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        
    }
    
    return false;
}

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Reset Users Password';

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Get the correcct page header depending on the users current role
// If user is not logged in display message to user telling them to log in
if (!isset($loggedIn)){
    echo getHTMLHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you must be logged to access this page. Please login <a href='login.php'>here</a> to login to your account. If you don't currently have an account please contact your system administrator to have one created for you.";
    
} elseif (isset($userRole) && $userRole == 2){        // User level
    echo getHTMLUserHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you do not have the correct permissions to access this page. Please select a different role <a href='select-role.php'>here</a> to change your account role.";
    
} elseif (isset($userRole) && $userRole == 1){        // Admin level
    echo getHTMLAdminHeader($pageTitle, $loggedIn);
    
} else{
    echo getHTMLHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you do not have the correct permissions to access this page. Please select a different role <a href='select-role.php'>here</a> to change your account role.";
}

?>
<div class="jumbotron text-center">
  <h1><?php echo $pageTitle;?></h1>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
    <?php

    // If not logged in show text
    if (isset($errorText)){
      echo "<p>$errorText</p>";

    } else{
        echo $content = generateNewPassword($dbConn, $_POST['username'], $_POST['email']) ? "<p>The password has successfully been changed.</p>" : "<p>There was a problem updating the users password.</p>";

    }

    ?>
    </div>
    <div class="col-sm-4"></div>
  </div>
</div>
<?php
echo getHTMLFooter();
getHTMLEnd();