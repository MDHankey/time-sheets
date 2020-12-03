<?php
/* Edit Project 
 * admin page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'functions/project-functions.php';
require_once 'class/PDODB.php';
session_start();

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Edit Project';  

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

// Check to see if a valid project id has been passed in
if(isset($_REQUEST['project_id'])){
    // Validation Checks
    if(is_numeric($_REQUEST['project_id'])){
        if(!empty(getProject($dbConn, $_REQUEST['project_id']))){
            $project_name = getProject($dbConn, $_REQUEST['project_id']);
        
        } else{
            $errorText = "You have not chosen a valid project to edit.  Please select a project <a href='manage-project.php'>here</a> to change it's details";
        
        }

    } else {
        $errorText = "You have not chosen a valid project to edit.  Please select a project <a href='manage-project.php'>here</a> to change it's details";
   
    }

} else{
    $errorText = "You have not chosen a valid project to edit. Please select a project <a href='manage-project.php'>here</a> to change it's details";
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

    } else if (isset($_POST['update_project_name']) && isset($_POST['project_id'])){
      // Validate
      list($input, $errors) = validateUpdateProjectForm($dbConn);
    
      if (empty($errors)){
        // Update
        if (setProject($dbConn, $input)){
          $updateSuccess = "<h3>You have successfully edited the project. To update another please click <a href='manage-project.php'>here</a>.</h3>";
        }

      } 

    } else{
      $input = array();
      $errors = array();
      $input['project_id'] = isset($_GET['project_id']) ? $_GET['project_id'] : null;
      $input['update_project_name'] = isset($project_name) ? $project_name : null;
      

    } 

    if(isset($updateSuccess)){
      echo $updateSuccess;

    }

    if (isset($input, $errors) && !isset($updateSuccess)){
      ?>
      <div class="col-sm-3"></div>
      <div class="col-sm-6">
        <h3 class="text-center">You are currently updating <?= $project_name; ?></h3>
        <form action="edit-project.php" name="updateForm" method="POST">
          <div class="form-group">
            <label for="project">Project:</label>
            <input type="text" class="form-control" placeholder="Project" name="update_project_name" value="<?= $input['update_project_name']; ?>" required/>
            <input type="hidden" name="project_id" value="<?=$input['project_id'];?>">
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
getHTMLEnd();