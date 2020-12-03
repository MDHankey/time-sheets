<?php
/* Manage Activity 
 * admin page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
session_start();

// Function to get all of the details for activities 
function getActivitiesTable($dbConn, $loggedIn = null){
  // Try to carry out the database entries
  try{
    $sqlQuery = "SELECT activity_id,
                        activity_type
                    FROM timesheets_activity
                ORDER BY activity_type ASC";
  
    // Prepare the query
    $stmt = $dbConn->prepare($sqlQuery);
  
    // Execute the query
     $stmt->execute();
  
    // Set the mode to associative
     $stmt->setFetchMode(PDO::FETCH_ASSOC);
  
    // Place all of the records in variable
    $activityResults = $stmt->fetchAll();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $activities =  "<div class='table-responsives'>
                        <table class='table table-dark' >
                          <thead>
                            <tr>
                              <th>Activities</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>";

      foreach ($activityResults as $result){
        // Activity Id
        $activity_id = htmlspecialchars($result['activity_id']);
      
      
        // Activity type
        $activity_type = htmlspecialchars($result['activity_type']);    
        $activities .= "<tr>
                          <td>$activity_type</td>
                          <td class='actions'>
                            <a href='edit-activity.php?activity_id=$activity_id' title='Edit'><i class='fas fa-pencil-alt action-icon' ></i></a>
                            <a href='delete-activity.php?activity_id=$activity_id' title='Delete'><i class='fas fa-times action-icon'></i></a>
                          </td>
                        </tr>";

      }                   
      // Close the table and div
      $activities .= "</tbody>
                    </table>
                  </div>";
          
      } else{
        $activities = null;
      }   

  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
      $activities = null;
  }

  return $activities;
}

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Manage Activity';  

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
    <div class="col-sm-12">
    <?php

    // If not logged in show text
    if (isset($errorText)){
      echo "<p>$errorText</p>";

    } else{
      echo $content = !empty(getActivitiesTable($dbConn, $loggedIn)) ? getActivitiesTable($dbConn, $loggedIn) : "<p class='no-results'>There are currently no activities in the system.</p>";

    }

    ?>
    </div>
  </div>
</div>
<?php
getHTMLEnd();