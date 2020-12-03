<?php 
/* Select role */
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
session_start();

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Check if new role has been selected
if (isset($_POST['role_id']) && isset($loggedIn)){
  // Check selected role is a role available to the current user
  // $role = validateSelectedRole($_POST['role_id'], $loggedIn);

  $_SESSION['role'] = $_POST['role_id'];
}

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Page title
$pageTitle = 'Select Role';  

// Get the correcct page header depending on the users current role
// If user is not logged in display message to user telling them to log in
if (!isset($loggedIn)){
  echo getHTMLHeader($pageTitle, $loggedIn);
  $errorText = "Sorry you must be logged in to select a user role. Please login <a href='login.php'>here</a> to login to your account. If you don't currently have an account please contact your system administrator to have one created for you.";

} elseif (isset($userRole) && $userRole == 2){        // User level 
  echo getHTMLUserHeader($pageTitle, $loggedIn);

} elseif (isset($userRole) && $userRole == 1){        // Admin level
  echo getHTMLAdminHeader($pageTitle, $loggedIn);

} else{
  echo getHTMLHeader($pageTitle, $loggedIn);

}

?>
<div class="jumbotron text-center">
  <h1><?php echo $pageTitle;?></h1>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm-12">
    <?php

    // If not logged in show text
    if (isset($errorText)){
      echo "<p>$errorText</p>";

    } else{

	  // Get array of available roles
	  $roles = getUserRoles($dbConn, $loggedIn);
	  $userTitle = $userRole;
	  
      // Get the users current role if they have one selected
      if (isset($userRole) && is_array($roles)){
		// Loop though each of the roles to get type and id
        foreach ($roles as $key => $value) {
          // Check the users current role
          if(isset($userRole)){
            if ($userRole == $key){
              $userTitle = $value;
            } 
          }
		}
        ?>
        <div class="row">
          <div class="col-sm-12">
            <h3>Active Role: <?php echo ucfirst($userTitle)?></h3>
          </div>
        </div>
        <?php
      }

      if (is_array($roles)){
        ?>
        <div class="row">
        <?php

        // Loop though each of the roles to get type and id
        foreach ($roles as $key => $value) {
          // Check the users current role
          // Deactivate selection of the same role as the one the user currently has
          $disable = "";

          if(isset($userRole)){
            if ($userRole == $key){
              $disable = "disabled";
            } 
          }

          ?>
          <div class="col-sm-6 text-center">
            <form action="select-role.php" name="role-form" method="POST">
              <input type="hidden" name="role_id" value="<?php echo $key; ?>" />
              <input type="submit" class="btn btn-info btn-lg btn-block btn-select-role" name="role_type" value="<?php echo ucfirst($value); ?>" <?php echo $disable;?>/>
            </form>
          </div>
          <?php
        }

        ?>
        </div>
        <?php

      } else{
        echo "<p>$roles</p>";   // Error Message
      }
    }

    ?>
    </div>
  </div>
</div>
<?php
getHTMLEnd();