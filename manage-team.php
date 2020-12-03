<?php
/* Manage Team
 * admin page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
session_start();

// Function to get all of the details for Teams
function getTeamsTable($dbConn, $loggedIn = null){
  // Try to carry out the database entries
  try{
    $sqlQuery = "SELECT timesheets_team.department_id,
                        team_id,
                        team_name,
                        timesheets_department.department_name
                   FROM timesheets_team
                   JOIN timesheets_department 
                     ON timesheets_department.department_id = timesheets_team.department_id
               ORDER BY team_name ASC";
  
    // Prepare the query
    $stmt = $dbConn->prepare($sqlQuery);
  
    // Execute the query
     $stmt->execute();
  
    // Set the mode to associative
     $stmt->setFetchMode(PDO::FETCH_ASSOC);
  
    // Place all of the records in variable
    $teamResults = $stmt->fetchAll();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $teams =  "<div class='table-responsives' style='overflow: scroll;'>
                  <table class='table table-dark' >
                    <thead>
                      <tr>
                        <th>Teams</th>
                        <th>Departments</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                  <tbody>";

      foreach ($teamResults as $result){
        // Team Id
        $team_id = htmlspecialchars($result['team_id']);

        $team_name = htmlspecialchars($result['team_name']);
      
      
        // Department name
        $department_name = htmlspecialchars($result['department_name']);    
        $teams .= "<tr>
                    <td>$team_name</td>
                    <td>$department_name</td>
                    <td class='actions'>
                      <a href='edit-team.php?team_id=$team_id' title='Edit'><i class='fas fa-pencil-alt action-icon' ></i></a>
                      <a href='delete-team.php?team_id=$team_id' title='Delete'><i class='fas fa-times action-icon'></i></a>
                    </td>
                  </tr>";

      }                   
      // Close the table and div
      $teams .= "</tbody>
                    </table>
                  </div>";
          
      } else{
        $teams = null;
      }   

  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
      $teams = null;
  }

  return $teams;
}

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Manage Team';  

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
      echo $content = !empty(getTeamsTable($dbConn, $loggedIn)) ? getTeamsTable($dbConn, $loggedIn) : "<p class='no-results'>There are currently no teams in the system.</p>";

    }

    ?>
    </div>
  </div>
</div>
<?php
echo getHTMLFooter();
getHTMLEnd();