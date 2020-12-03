<?php
/* Manage Project
 * admin page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
session_start();

// Function to get all of the details for Project 
function getProjectsTable($dbConn, $loggedIn = null){
  // Try to carry out the database entries
  try{
    $sqlQuery = "SELECT project_name,
                        project_id
                   FROM timesheets_project
               ORDER BY project_name ASC";
  
    // Prepare the query
    $stmt = $dbConn->prepare($sqlQuery);
  
    // Execute the query
     $stmt->execute();
  
    // Set the mode to associative
     $stmt->setFetchMode(PDO::FETCH_ASSOC);
  
    // Place all of the records in variable
    $projectResults = $stmt->fetchAll();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $projects =  "<div class='table-responsives' style='overflow: scroll;'>
                  <table class='table table-dark' >
                    <thead>
                      <tr>
                        <th>Projects</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                  <tbody>";

      foreach ($projectResults as $result){
        // Project Id
        $project_id = htmlspecialchars($result['project_id']);

        $project_name = htmlspecialchars($result['project_name']);
      
        $projects .= "<tr>
                    <td>$project_name</td>
                    <td class='actions'>
                      <a href='edit-project.php?project_id=$project_id' title='Edit'><i class='fas fa-pencil-alt action-icon' ></i></a>
                      <a href='delete-project.php?project_id=$project_id' title='Delete'><i class='fas fa-times action-icon'></i></a>
                    </td>
                  </tr>";

      }                   
      // Close the table and div
      $projects .= "</tbody>
                    </table>
                  </div>";
          
      } else{
        $projects = null;
      }   

  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
      $projects = null;
  }

  return $projects;
}

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Manage Project';  

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
      echo $content = !empty(getProjectsTable($dbConn, $loggedIn)) ? getProjectsTable($dbConn, $loggedIn) : "<p class='no-results'>There are currently no projects in the system.</p>";

    }

    ?>
    </div>
  </div>
</div>
<?php
echo getHTMLFooter();
getHTMLEnd();