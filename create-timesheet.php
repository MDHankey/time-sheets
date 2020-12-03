<?php
/* Create Timesheete
 * admin page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
require_once  'functions/timesheet-functions.php';
require_once 'functions/activity-functions.php';
require_once 'functions/project-functions.php';
require_once 'functions/account-functions.php';

session_start();

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Create Timesheet';

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Get the correcct page header depending on the users current role
// If user is not logged in display message to user telling them to log in
if (!isset($loggedIn)){
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

?>

    <div class="jumbotron text-center">
        <h1><?php echo $pageTitle; ?></h1>
    </div>

<?php

// Get the list of departments
$activityList = getActivityOptions($dbConn);
$projectList = getProjectOptions($dbConn);


// Check if job has been submitted from form
if (isset($_REQUEST['user_id'])) {

    $input = array();
    $errors = array();

    $input['activity_id'] = isset($_POST['activity_id']) ? $_POST['activity_id'] : null;
    $input['project_id'] = isset($_POST['project_id']) ? $_POST['project_id'] : null;
    $input['date'] = isset($_POST['date']) ? $_POST['date'] : null;
    $input['time_in'] = isset($_POST['time_in']) ? $_POST['time_in'] : null;
    $input['time_out'] = isset($_POST['time_out']) ? $_POST['time_out'] : null;
    $input['note'] = isset($_POST['note']) ? $_POST['note'] : null;

    $hourIn = 0;
    $minuteIn = 0;
    $hourOut = 0;
    $minuteOut = 0;

    //checks time in and time out are in the 24hr format of HH:MM
    if (!preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/",  $input['time_in'])){
        $errors[] = "Time In is not in the valid format - HH:MM";
    }
    else{
        $hourIn =  (int)substr( $input['time_in'] , 0 ,2 );
        $minuteIn = (int)substr( $input['time_in'] , -2 );
    }

    if (!preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $input['time_out'] )){
        $errors[] = "Time Out is not in the valid format - HH:MM";
    }
    else {
        $hourOut = (int)substr( $input['time_out'] , 0 ,2 );
        $minuteOut = (int)substr( $input['time_out'] , -2 );
    }

    if ($hourIn >= $hourOut && $minuteIn >= $minuteOut){
        $errors[] = "Time In needs to be before Time Out";
    }


    // Run the SQL
    if (empty($errors) && createTimesheet($dbConn, $input, getUserIDByUsername($dbConn, $loggedIn))) {
        $querySuccessMsg = "<div class= 'row justify-content-center align-items-center'>
                                <h3>You have successfully created a new timesheet record</h3>
                            </div>";
    }
}


// Show success message if query ran/succeeded
if (isset($querySuccessMsg)) {
    echo($querySuccessMsg);
    // Else display form
} else {

    ?>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <h3 class="text-center">Complete the form below</h3>
                <form action="create-timesheet.php" name="createTimesheetForm" method="POST">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" placeholder="Date" name="date" value="" required/>
                        <div class="form-group">
                            <label for="activity">Activity:</label>
                            <select class="form-control" name="activity_id" required>
                                <?php
                                    echo $activityList;
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="project">Project:</label>
                            <select class="form-control" name="project_id" required="">
                                <?php
                                    echo $projectList;
                                ?>
                            </select>
                        </div>
                        <label for="time_in">Time In</label>
                        <input type="text" class="form-control" placeholder="Time In" name="time_in" value="" maxlength="5" onkeypress="return setTimeFormat(this, event)" required/>
                        <label for="time_out">Time Out</label>
                        <input type="text" class="form-control" placeholder="Time Out" name="time_out" value="" maxlength="5" onkeypress="return setTimeFormat(this, event)" required/>
                        <div class="form-group">
                            <label for="note">Notes:</label>
                            <textarea class="form-control" rows="5" name="note" id="note"></textarea>
                        </div>
                        <input type="hidden" name="user_id" value="">
                    </div>
                    <div class="create-error">
                        <?php
                        if (!empty($errors)) {
                            foreach ($errors as $error) {
                                echo "<p class=\'error\'>$error</p>";

                            }
                        }
                        ?>
                    </div>
                    <button type="submit" id="create-button" class="btn btn-primary">Create</button>
                </form>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    }
}
echo getHTMLFooter();
?>
    <script src="js/functions.js"></script>
<?php
echo getHTMLEnd();