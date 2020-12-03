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
$pageTitle = 'Edit Account';  

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

// Check to see if a valid account/person id has been passed in
if(isset($_REQUEST['account_id'])){
    // Validation Checks
    if(is_numeric($_REQUEST['account_id'])){
        if(!empty(getAccount($dbConn, $_REQUEST['account_id']))){
        
            $username = getUsername($dbConn, $_REQUEST['account_id']);

            $title = getTitle($dbConn, $_REQUEST['account_id']);

            $forename = getForename($dbConn, $_REQUEST['account_id']);

            $surname = getSurname($dbConn, $_REQUEST['account_id']);

            $phone_number = getPhoneNumber($dbConn, $_REQUEST['account_id']);

            $email = getEmail($dbConn, $_REQUEST['account_id']);

            $address_line_1 = getAddressLine1($dbConn, $_REQUEST['account_id']);
            
            $address_line_2 = getAddressLine2($dbConn, $_REQUEST['account_id']);
            
            $address_line_3 = getAddressLine3($dbConn, $_REQUEST['account_id']);
            
            $address_line_4 = getAddressLine4($dbConn, $_REQUEST['account_id']);
            
            $address_line_5 = getAddressLine5($dbConn, $_REQUEST['account_id']);
            
            $postcode = getPostcode($dbConn, $_REQUEST['account_id']);

            $date_of_birth = getDateOfBirth($dbConn, $_REQUEST['account_id']);

            $contracted_hours = getContractedHours($dbConn, $_REQUEST['account_id']);

            $job_id = getJobByAccountID($dbConn, $_REQUEST['account_id']);
            $job_options = getJobOptions($dbConn, $job_id);

            $team_id = getTeamByAccountID($dbConn, $_REQUEST['account_id']);
            $team_options = getTeamOptions($dbConn, $team_id);

            $department_id = getDepartmemtByAccountID($dbConn, $_REQUEST['account_id']);
            $department_options = getDepartmemtOptions($dbConn, $department_id);
            
            $role_id = getRoleByAccountID($dbConn, $_REQUEST['account_id']);
            $role_options = getRoleOptions($dbConn, $role_id);
        
        } else{
            $errorText = "You have not chosen a valid account to edit.  Please select an account <a href='manage-account.php'>here</a> to change it's details";
        
        }

    } else {
        $errorText = "You have not chosen a valid account to edit.  Please select an account <a href='manage-account.php'>here</a> to change it's detailSs";
   
    }

} else{
    $errorText = "You have not chosen a valid account to edit. Please select an account <a href='manage-account.php'>here</a> to change it's details";
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

    } else if (isset($_POST['account_id'])){
      // Validate
      list($input, $errors) = validateUpdateAccountForm($dbConn);


      if (empty($errors)){
        // Update
        if (setAccount($dbConn, $input)){
          $updateSuccess = "<h3>You have successfully edited the account. To update another please click <a href='manage-account.php'>here</a>.</h3>";
        }

      } 

    } else{
      $input = array();
      $errors = array();

      $input['account_id'] = isset($_REQUEST['account_id']) ? $_REQUEST['account_id'] : '';

      $input['update_username'] = isset($username) ? $username : '';

      $input['update_title'] = isset($title) ? $title : '';

      $input['update_forename'] = isset($forename) ? $forename : '';

      $input['update_surname'] = isset($surname) ? $surname : '';

      $input['update_phone_number'] = isset($phone_number) ? $phone_number : '';

      $input['update_email'] = isset($email) ? $email : '';

      $input['update_address_line_1'] = isset($address_line_1) ? $address_line_1 : '';

      $input['update_address_line_2'] = isset($address_line_2) ? $address_line_2 : '';

      $input['update_address_line_3'] = isset($address_line_3) ? $address_line_3 : '';

      $input['update_address_line_4'] = isset($address_line_4) ? $address_line_4 : '';

      $input['update_address_line_5'] = isset($address_line_5) ? $address_line_5 : ''; 

      $input['update_postcode'] = isset($postcode) ? $postcode : '';

      $input['update_date_of_birth'] = isset($date_of_birth) ? $date_of_birth : '';

      $input['update_contracted_hours'] = isset($contracted_hours) ? $contracted_hours : '';

    } 

    if(isset($updateSuccess)){
      echo $updateSuccess;

    }

    if (isset($input, $errors) && !isset($updateSuccess)){
      ?>
      <div class="col-sm-3"></div>
      <div class="col-sm-6">
        <form action="edit-account.php" name="updateForm" method="POST">
          <input type="hidden" name="account_id" value="<?= $input['account_id']; ?>"/>
          <div class="update-error">
            <?php
            if (!empty($errors)) {
              foreach ($errors as $error) {
                echo "<p class='error'>$error</p>";

              }
            }
            ?>
          </div>
          <div class="form-group">
            <label for="title">Username: </label>
            <input type="text" class="form-control" placeholder="Username" name="update_username" value="<?= $input['update_username']; ?>" required/>

            <label for="title">Title: </label>
            <input type="text" class="form-control" placeholder="Title" name="update_title" value="<?= $input['update_title']; ?>" required/>

            <label for="forename">Forename: </label>
            <input type="text" class="form-control" placeholder="Forename" name="update_forename" value="<?= $input['update_forename']; ?>" required/>

            <label for="surname">Surname: </label>
            <input type="text" class="form-control" placeholder="Surname" name="update_surname" value="<?= $input['update_surname']; ?>" required/>

            <label for="phone_number">Phone number: </label>
            <input type="text" class="form-control" placeholder="Phone number" name="update_phone_number" value="<?= $input['update_phone_number']; ?>" required/>

            <label for="email">Email: </label>
            <input type="text" class="form-control" placeholder="Email" name="update_email" value="<?= $input['update_email']; ?>" required/>

            <label for="address_line_1">Address Line 1: </label>
            <input type="text" class="form-control" placeholder="Address Line 1" name="update_address_line_1" value="<?= $input['update_address_line_1']; ?>" required/>

            <label for="address_line_2">Address Line 2: </label>
            <input type="text" class="form-control" placeholder="Address Line 2" name="update_address_line_2" value="<?= $input['update_address_line_2']; ?>" required/>

            <label for="town">Town/City: </label>
            <input type="text" class="form-control" placeholder="Town/City" name="update_address_line_3" value="<?= $input['update_address_line_3']; ?>" />

            <label for="County">County: </label>
            <input type="text" class="form-control" placeholder="County" name="update_address_line_4" value="<?= $input['update_address_line_4']; ?>" />

            <label for="Country">Country: </label>
            <input type="text" class="form-control" placeholder="Country:" name="update_address_line_5" value="<?= $input['update_address_line_5']; ?>" />

            <label for="Postcode">Postcode: </label>
            <input type="text" class="form-control" placeholder="Postcode" name="update_postcode" value="<?= $input['update_postcode']; ?>" required/>

            <label for="dob">Date Of Birth: </label>
            <input type="date" class="form-control" placeholder="Date Of Birth" name="update_date_of_birth" value="<?= $input['update_date_of_birth']; ?>" required/>

            <label for="hours">Contracted Hours: </label>
            <input type="text" class="form-control" placeholder="Contracted Hours" name="update_contracted_hours" value="<?= $input['update_contracted_hours']; ?>" required/>
          </div>

          <div class="form-group">
            <label for="Job">Job:</label>
            <select class="form-control" name="job_id" required>
              <?php
              echo $job_options;
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="Department">Department:</label>
            <select class="form-control" name="department_id" required>
        			<?php
        			echo $department_options;
        			?>
    		    </select>
          </div>

          <div class="form-group">
            <label for="Team">Team:</label>
            <select class="form-control" name="team_id" required>
              <?php
              echo $team_options;
              ?>
            </select>
          </div>
			
		  <div class="form-group">
            <label for="Role">Role:</label>
            <select class="form-control" name="role_id" required>
              <?php
              echo $role_options;
              ?>
            </select>
          </div>
          
	
          <button type="submit" id="update-button" class="btn btn-primary">Update</button>
        </form>
        <?php 
        if (isset($input['update_username']) && isset($input['update_email'])){
          ?>
          <form action="reset-users-password.php" name="resetForm" method="POST">
            <div class="form-group">
          	  <p style="margin-top:20px; margin-bottom:0px;">RESET USER'S PASSWORD</p>
           	  <input type="hidden" name="username" value="<?= $input['update_username']; ?>" />
           	  <input type="hidden" name="email" value="<?= $input['update_email']; ?>" />
           	  <button type="submit" id="reset-button" class="btn btn-danger">RESET</button>
          	</div>
          </form>
          <?php 
        }
        ?>
      </div>
      <div class="col-sm-3"></div>

      <?php
    }
      
    ?>
  </div>
</div>
<?php
echo getHTMLFooter();
getHTMLEnd();