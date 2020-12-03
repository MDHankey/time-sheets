<?php
/* Create Account 
 * admin page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
session_start();

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Create Account';

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Get the correcct page header depending on the users current role
// If user is not logged in display message to user telling them to log in
if (!isset($loggedIn)) {
    echo getHTMLHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you must be logged to access this page. Please login <a href='login.php'>here</a> to login to your account. If you don't currently have an account please contact your system administrator to have one created for you.";

} elseif (isset($userRole) && $userRole == 2) {        // User level
    echo getHTMLUserHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you do not have the correct permissions to access this page. Please select a different role <a href='select-role.php'>here</a> to change your account role.";

} elseif (isset($userRole) && $userRole == 1) {        // Admin level
    echo getHTMLAdminHeader($pageTitle, $loggedIn);

} else {
    echo getHTMLHeader($pageTitle, $loggedIn);
    $errorText = "Sorry you do not have the correct permissions to access this page. Please select a different role <a href='select-role.php'>here</a> to change your account role.";
}

?>

    <!-- Get signupFormFunctions.js functions --->
    <script type="text/javascript" src="js/signupFormFunctions.js"></script>
    <div class="jumbotron text-center">
        <h1>Create an account</h1>
    </div>

<?php

// Retrieve role ID's and names from database
$rolesSQL = "SELECT role_id,
                    role_type
                    FROM timesheets_role
                    ORDER BY role_type";

// Execute the statement
$rsRoles = $dbConn->prepare($rolesSQL);
$rsRoles->execute();
$rolesResults = $rsRoles->fetchAll();


// Retrieve department ID's and names from database
$departmentsSQL = "SELECT department_id, 
                          department_name
                     FROM timesheets_department
                 ORDER BY department_name";

// Execute the statement
$rsDepartments = $dbConn->prepare($departmentsSQL);
$rsDepartments->execute();
$departmentResults = $rsDepartments->fetchAll();

// Retrieve team ID's and names from database
$teamsSQL = "SELECT team_id, 
                    department_id, 
                    team_name
               FROM timesheets_team
           ORDER BY team_name";

// Execute the statement
$rsTeams = $dbConn->prepare($teamsSQL);
$rsTeams->execute();
$teamsResults = $rsTeams->fetchAll();


// Retrieve job ID's and names from database
$jobsSQL = "SELECT job_id, 
                   department_id, 
                   job_title
              FROM timesheets_job
          ORDER BY job_title";

// Execute the statement
$rsJobs = $dbConn->prepare($jobsSQL);
$rsJobs->execute();
$jobsResults = $rsJobs->fetchAll();

?>


    <div class="container">
        <h4 class="formPage1 formTitles">Please enter a username and temporary password for the user:</h4>
        <h4 id="formPage2Title" class="formPage2 formTitles" style="display: none">Please enter the user's personal
            information:</h4>
        <h4 class="formPage3 formTitles" style="display: none">Please enter the user's address details:</h4>
        <h4 class="formPage4 formTitles" style="display: none">Please enter the user's employment details:</h4>
        <br>
        <div class="row justify-content-center align-items-center">
            <div class="col-sm-4">
                <form onsubmit="" action="signupProcess.php" id="signUpForm" class="form" name="signupForm"
                      method="POST">
                    <!---------------------- Start form page 1 ----------------------->
                    <div class="formPage1" id="formPage1">
                        <div class="form-group">
                            <input type="text" id="signUp-username" class="form-control" placeholder="Username"
                                   name="username"/>
                            <p id="usernameValidationMsg" class="formPage1 formErrorMessages" style="display: none"></p>
                        </div>
                        <div class="form-group">
                            <input type="password" id="signUp-pass" class="form-control" placeholder="Password"
                                   name="password"/>
                            <p id="passwordValidationMsg" class="formPage1 formErrorMessages" style="display: none">
                                Passwords must be matching &#10008</p>
                        </div>
                        <div class="form-group">
                            <input type="password" id="signUp-confirmPass" class="form-control"
                                   placeholder="Confirm Password" name="confirmPassword"/>
                        </div>
                        <div class="login-error">
                        </div>
                        <br/>
                        <span onclick="goSignUpPage2(); " id="goPage2Button"
                              class="formButtons formPage1">Go to page 2</span>

                    </div>
                    <!--------------------- End form page 1 ----------------------->

                    <!-------------------- Start form page 2 ---------------------->
                    <div class="formPage2" id="formPage2" style="display: none">
                        <div class="form-group">
                            <select class="form-control" name="title">
                                <option value="" disabled selected>Title</option>
                                <option value="Miss">Miss</option>
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Ms">Ms</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" id="signUp-forename" class="form-control" placeholder="Forename"
                                   name="forename"/>
                            <p id="forenameValidationMsg" class="formPage2 formErrorMessages" style="display: none"></p>
                        </div>
                        <div class="form-group">
                            <input type="text" id="signUp-surname" class="form-control" placeholder="Surname"
                                   name="surname"/>
                            <p id="surnameValidationMsg" class="formPage2 formErrorMessages" style="display: none"></p>
                        </div>
                        <div class="form-group">
                            <input type="date" id="signUp-dateOfBirth" class="form-control" placeholder="Date of Birth"
                                   name="dateOfBirth"/>
                            <p id="DOBValidationMsg" class="formPage2 formErrorMessages" style="display: none"></p>
                        </div>
                        <div class="form-group">
                            <input type="text" id="signUp-email" class="form-control" placeholder="Email Address"
                                   name="email"/>
                            <p id="emailValidationMsg" class="formPage2 formErrorMessages" style="display: none"></p>
                        </div>
                        <div class="form-group">
                            <input type="number" id="signUp-phone" class="form-control" placeholder="Phone Number"
                                   name="phone"/>
                            <p id="phoneValidationMsg" class="formPage2 formErrorMessages" style="display: none"></p>
                        </div>
                        <br/>
                        <span onclick="returnPage1()" id="returnPage1Button"
                              class="formButtons formPage2">Go to page 1</span>
                        <span onclick="goSignUpPage3()" id="goPage3Button"
                              class="formButtons formPage2">Go to page 3</span>
                    </div>
                    <!---------------------- End form page 2 ------------------------>

                    <!---------------- Start form page 3 --------------------------->
                    <div class="formPage3" id="formPage3" style="display: none">
                        <div class="form-group">
                            <input type="text" id="signUp-address1" class="form-control" placeholder="Address Line 1"
                                   name="address1"/>
                            <p id="address1ValidationMsg" class="formPage3 formErrorMessages" style="display: none"></p>
                        </div>
                        <div class="form-group">
                            <input type="text" id="signUp-address2" class="form-control"
                                   placeholder="Address Line 2 (Optional)" name="address2"/>
                        </div>
                        <div class="form-group">
                            <input type="text" id="signUp-address3" class="form-control"
                                   placeholder="Address Line 3 (Optional)" name="address3"/>
                        </div>
                        <div class="form-group">
                            <input type="text" id="signUp-address4" class="form-control"
                                   placeholder="Address Line 4 (Optional)" name="address4"/>
                        </div>
                        <div class="form-group">
                            <input type="text" id="signUp-address5" class="form-control"
                                   placeholder="Address Line 5 (Optional)" name="address5"/>
                        </div>
                        <div class="form-group">
                            <input type="text" id="signUp-postcode" class="form-control" placeholder="Post Code"
                                   name="postcode"/>
                            <p id="postCodeValidationMsg" class="formPage3 formErrorMessages" style="display: none"></p>
                        </div>
                        <br/>
                        <span onclick="returnPage2()" id="returnPage2Button"
                              class="formButtons formPage3">Go to page 2</span>
                        <span onclick="goSignUpPage4()" id="goPage4Button"
                              class="formButtons formPage3">Go to page 4</span>
                    </div>
                    <!------------------- End form page 3 ------------------------->

                    <!------------------- Start form page 4 --------------------->
                    <div class="formPage4" id="formPage4" style="display:none">
                        <div class="form-group">
                            <label class="signUpFormLabels">Department</label>
                            <select id="departmentSelect" class="form-control" name="departmentID">

                                <?php
                                // Iterate through department results
                                foreach ($departmentResults as $row) {
                                    $departmentID = $row['department_id'];
                                    $departmentName = $row['department_name'];
                                    echo "<option value='$departmentID'>$departmentName</option>";
                                }
                                ?>

                            </select>
                            <p id="departmentSelectValidationMsg" class="formPage4 formErrorMessages"
                               style="display: none"></p>
                        </div>
                        <div class="form-group">
                            <label class="signUpFormLabels">Team</label>
                            <select id="teamSelect" class="form-control" name="teamID">
                                <option class='teamSelects' value='' selected disabled hidden>Select a Team</option>

                                <?php
                                // Iterate through teams results
                                foreach ($teamsResults as $row) {
                                    $teamID = $row['team_id'];
                                    $teamDepartmentID = $row['department_id'];
                                    $teamName = $row['team_name'];
                                    // Assign team options to classes based on department ID's they belong to
                                    echo "<option class='teamSelects team-department-$teamDepartmentID' value='$teamID'>$teamName</option>";
                                }
                                ?>
                            </select>
                            <p id="teamSelectValidationMsg" class="formPage4 formErrorMessages"
                               style="display: none"></p>
                        </div>
                        <div class="form-group">
                            <label class="signUpFormLabels">Job</label>
                            <select id="jobSelect" class="form-control" name="jobID">
                                <option class='jobSelects' value='' selected disabled hidden>Select a Job</option>

                                <?php
                                // Iterate through jobs results
                                foreach ($jobsResults as $row) {
                                    $jobID = $row['job_id'];
                                    $jobDepartmentID = $row['department_id'];
                                    $jobName = $row['job_title'];
                                    // Assign job options to classes based on department ID's they belong to
                                    echo("<option class='jobSelects job-department-$jobDepartmentID' value='$jobID'>$jobName</option>");
                                }

                                ?>
                            </select>
                            <p id="jobSelectValidationMsg" class="formPage4 formErrorMessages"
                               style="display: none"></p>
                        </div>
                        <div class="form-group">
                            <label class="signUpFormLabels">Contracted Hours</label>
                            <input type="number" min="0" id="contractedHours" class="form-control"
                                   placeholder="Contracted Hours" name="contractedHours"/>
                            <p id="contractedHoursValidationMsg" class="formPage4 formErrorMessages"
                               style="display: none"></p>
                        </div>
                        <div class="form-group">
                            <label class="signUpFormLabels">User Role</label>
                            <select id="roleSelect" class="form-control" name="roleID">
                                <?php
                                // Iterate through roles results
                                foreach ($rolesResults as $row) {
                                    $roleID = $row['role_id'];
                                    $roleType = $row['role_type'];
                                    echo "<option value='$roleID'>$roleType</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <br/>
                        <span onclick="returnPage3()" id="returnPage3Button"
                              class="formButtons formPage4">Go to page 3</span>
                        <span onclick="submitForm()" id="submitFormButton"
                              class="formButtons formPage4">Submit Form</span>

                        <!------------------ End form page 4 --------------------->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-4"></div>
    <div id="formStepsContainer">
        <span style="background-color: #007bff" id="formStep1" class="formSteps"></span>
        <span id="formStep2" class="formSteps"></span>
        <span id="formStep3" class="formSteps"></span>
        <span id="formStep4" class="formSteps"></span>
    </div>
    <script type="text/javascript">
        //Prevents the form from being submitted
        $("#signUpForm").submit(function (e) {
            e.preventDefault();
        });

        // Do initial validation to change form field border colours on page load
        checkUsernameTaken();
        validatePassword();
        validateFormPage2();
        validateFormPage3();
        validateFormPage4();

        // Do initial call to hide non relevant selects
        hideSelectBoxes();

        // Change default selected index of team and job select boxes
        changeDefaultSelection("teamSelect", -1);
        changeDefaultSelection("jobSelect", -1);
    </script>
<?php
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p class='error'>$error</p>";

    }
}
?>
<?php
echo getHTMLEnd();