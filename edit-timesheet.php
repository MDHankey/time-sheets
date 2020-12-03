<?php
/* Edit timesheet 
 * admin page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'functions/timesheet-functions.php';
require_once 'functions/activity-functions.php';
require_once 'functions/project-functions.php';
require_once 'class/PDODB.php';
session_start();

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Edit Timesheet';

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;


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

// Check to see if a valid acctivity id has been passed in
if(isset($_REQUEST['timesheet_id'])){
    // Validation Checks
    if(is_numeric($_REQUEST['timesheet_id'])){
        if(!empty(getTimesheet($dbConn, $_REQUEST['timesheet_id']))){
            $timesheet_record = getTimesheet($dbConn, $_REQUEST['timesheet_id']);
            $activity_id = getTimesheetActivity($dbConn, $_REQUEST['timesheet_id']);
            $project_id = getTimesheetProject($dbConn, $_REQUEST['timesheet_id']);
            $date = getTimesheetDate($dbConn, $_REQUEST['timesheet_id']);
            $time_in = getTimeIn($dbConn, $_REQUEST['timesheet_id']);
            $time_out = getTimeOut($dbConn, $_REQUEST['timesheet_id']);
            $note = getNote($dbConn, $_REQUEST['timesheet_id']);

            $activity_options = getActivityOptions($dbConn, $activity_id);

            $project_options = getProjectOptions($dbConn, $project_id);

        } else{
            $errorText = "You have not chosen a valid timesheet to edit.  Please select a timesheet <a href='manage-timesheet.php'>here</a> to change it's details";

        }

    } else {
        $errorText = "You have not chosen a valid timesheet to edit.  Please select a timesheet <a href='manage-timesheet.php'>here</a> to change it's details";

    }

} else{
    $errorText = "You have not chosen a valid timesheet to edit. Please select a timesheet <a href='manage-timesheet.php'>here</a> to change it's details";
}

?>
    <div class="jumbotron text-center">
        <h1><?php echo $pageTitle;?></h1>
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center">
            <?php

            // If not logged in show text
            if (isset($errorText)){
                echo "<p>$errorText</p>";

            } else if (isset($_POST['timesheet_id'])){
                // Validate
                list($input, $errors) = validateUpdatetimesheetForm($dbConn);

                if (empty($errors)){
                    // Update
                    if (setTimesheet($dbConn, $input)){
                        if ($userRole ==1 ) {
                            $updateSuccess = "<h3>You have successfully edited the timesheet. To update another please click <a href='manage-timesheet.php'>here</a>.</h3>";
                        } else {
                            $updateSuccess = "<h3>You have successfully edited the timesheet. To update another please click <a href='past-timesheet.php'>here</a>.</h3>";
                        }
                    }

                }

            } else{
                $input = array();
                $errors = array();
                $input['timesheet_id'] = isset($_GET['timesheet_id']) ? $_GET['timesheet_id'] : null;
                $input['update_activity_id'] = isset($activity_id) ? $activity_id : null;
                $input['update_project_id'] = isset($project_id) ? $project_id : null;
                $input['update_date'] = isset($date) ? $date : null;
                $input['update_time_in'] = isset($time_in) ? substr($time_in , 0,5 ) : null;
                $input['update_time_out'] = isset($time_out) ?substr($time_out , 0,5 ) : null;
                $input['update_note'] = isset($note) ? $note : null;

            }

            if(isset($updateSuccess)){
                echo $updateSuccess;
            }

            if (isset($input, $errors) && !isset($updateSuccess)){
                ?>
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <h3 class="text-center">You are currently updating <?= $timesheet_record; ?></h3>
                    <form action="edit-timesheet.php" name="updateForm" method="POST">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" placeholder="Date" name="update_date" value="<?= $input['update_date']; ?>" required/>
                            <div class="form-group">
                                <label for="activity">Activity:</label>
                                <select class="form-control" name="update_activity_id" required="">
                                    <?php
                                    echo $activity_options;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="project">Project:</label>
                                <select class="form-control" name="update_project_id" required="">
                                    <?php
                                    echo $project_options;
                                    ?>
                                </select>
                            </div>
                            <label for="update_time_in">Time In</label>
                            <input type="text" class="form-control" placeholder="Time In" name="update_time_in" value="<?=  $input['update_time_in']; ?>" maxlength="5" onkeypress="return setTimeFormat(this, event)" required/>
                            <label for="update_time_out">Time Out</label>
                            <input type="text" class="form-control" placeholder="Time Out" name="update_time_out" value="<?= $input['update_time_out']; ?>" maxlength="5"  onkeypress="return setTimeFormat(this, event)" required/>
                            <div class="form-group">
                                <label for="update_note">Notes:</label>
                                <textarea class="form-control" rows="5" name="update_note" id="note"><?= $input['update_note']; ?></textarea>
                            </div>
                            <input type="hidden" name="timesheet_id" value="<?= $input['timesheet_id'];?>">
                        </div>
                        <div class="update-error">
                            <?php
                            if (!empty($errors)) {
                                foreach ($errors as $error) {
                                    echo "<p class='error'>$error</p>";

                                }
                            }

                            ?>
                        </div>
                        <button type="submit" id="update-button" class="btn btn-primary">Update</button>
                    </form>
                </div>
                <div class="col-sm-3"></div>

                <?php
            }

            ?>
        </div>
    </div>
<?php

echo getHTMLFooter();
?>
    <script src="js/functions.js"></script>
<?php
getHTMLEnd();