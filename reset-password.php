<?php
/* Edit Account
 * admin page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'functions/department-functions.php';
require_once 'functions/job-functions.php';
require_once 'functions/team-functions.php';
require_once 'functions/account-functions.php';
require_once 'functions/role-functions.php';
require_once 'class/PDODB.php';
session_start();

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Reset Password';

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;


// Get the correcct page header depending on the users current role
// If user is not logged in display message to user telling them to log in
if (!isset($loggedIn)) {
    echo getHTMLHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you must be logged to access this page. Please login <a href='login.php'>here</a> to login to your account. If you don't currently have an account please contact your system administrator to have one created for you.";

} elseif (isset($userRole) && $userRole == 2){        // User level
    echo getHTMLUserHeader($pageTitle, $loggedIn);

} elseif (isset($userRole) && $userRole == 1){        // Admin level
    echo getHTMLAdminHeader($pageTitle, $loggedIn);

} else{
    echo getHTMLHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you do not have the correct permissions to access this page. Please select a different role <a href='select-role.php'>here</a> to change your account role.";
}

// Check to see if a valid account/person id has been passed in
if(isset($loggedIn)){


} else{
    $errorText = "You have not chosen a valid account to reset password. Please select an account <a href='manage-account.php'>here</a> to change it's details";
}

?>
    <div class="jumbotron text-center">
        <h1><?php echo $pageTitle;?></h1>
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center">
            <?php

            if (isset($_POST['update_password']) &&  isset($_POST['update_confirm_password'])){
                if($_POST['update_password'] ==  $_POST['update_confirm_password']){
                    $password = trim(filter_has_var(INPUT_POST, 'update_password') ? $_POST['update_password'] : null);
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $updateSuccess = updateUserPassword($dbConn, $loggedIn, $hashedPassword);
                } else {
                    // Error password doesn't match
                    echo '<p>The passwords do not match. Please try again.</p>';

                }
            }


            if (empty($updateSuccess)) {
                if (isset($_POST['update_confirm_password']) && isset($_POST['update_password'])){
                    // Error updating
                }
                ?>
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <form action="reset-password.php" name="updateForm" method="POST">
                        <input type="hidden" name="account_id" value="<?= $input['account_id']; ?>"/>
                        <div class="form-group">
                            <label for="update_password">Password: </label>
                            <input type="password" class="form-control" placeholder="Password" name="update_password"
                                   required/>

                            <label for="update_confirm_password">Confirm Password: </label>
                            <input type="password" class="form-control" placeholder="Confirm Password"
                                   name="update_confirm_password" required/>
                        </div>
                        <button type="submit" id="reset-button" class="btn btn-primary">Reset</button>
                    </form>
                </div>
                <div class="col-sm-3"></div>
                </div>
                </div>
                <?php
            } else {
                echo '<h3>You have successfully reset your password.</h3>';

            }
getHTMLEnd();