<?php
// begin the session and save the information
// on loclahost or webspace depending on value in
// setEnv.php
function sessionStart($sessionDirectory = SESSION_DIR){
    ini_set("session.save_path", $sessionDirectory);
    return session_start();
}

/* Creating the functions which control the pages */
function getHTMLHeader($pageTitle, $loggedIn){

    $logged = isset($loggedIn) ? '<a class="nav-link" href="logout.php">Log Out</a>' : '<a class="nav-link" href="login.php">Members Login</a>';

    // Create the header placing it in a HEREDOC
    $header = <<<HEADER
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>$pageTitle</title>
    <meta charset="utf-8">
	<meta name="description" content="Timesheet Manager">
    <meta name="keywords" content="Timesheet,Staff,Report">
    <meta name="author" content="DTS">
	<meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="theme-color" content="#b1ddef">
	<link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="manifest.json">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <script src="js/libraries/jquery-3.3.1.min.js"></script>
    <script src="js/libraries/bootstrap.min.js"></script>
    <script src="js/functions.js"></script>
  </head>
  <body>
    <div id="wrapper">
      <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php"><i class="far fa-calendar-alt"></i></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="pricing.php">Pricing</a>
            </li>
            <li class="nav-item">
              $logged
            </li>
          </ul>
        </div>
      </nav>
HEADER;

    return $header;
}

function getHTMLUserHeader($pageTitle, $loggedIn){
  $logged = isset($loggedIn) ? '<a class="nav-link" href="logout.php">Log Out</a>' : '<a class="nav-link" href="login.php">Members Login</a>';

    // Create the header placing it in a HEREDOC
    $header = <<<HEADER
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>$pageTitle</title>
    <meta charset="utf-8">
    <meta name="description" content="Timesheet Manager">
    <meta name="keywords" content="Timesheet,Staff,Report">
    <meta name="author" content="DTS">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="theme-color" content="#b1ddef">
    <meta http-equiv="refresh" content="900;url=logout.php" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="manifest.json">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <script src="js/libraries/jquery-3.3.1.min.js"></script>
    <script src="js/libraries/bootstrap.min.js"></script>
  </head>
  <body>
  <div id="wrapper">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
      <a class="navbar-brand" href="index.php">
        <i class="far fa-calendar-alt"></i>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavigation" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNavigation">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link" href="manage-my-account.php">My Account</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="timesheetDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Timesheets</a>
            <div class="dropdown-menu" aria-labelledby="timesheetDropdown">
              <a class="dropdown-item" href="create-timesheet.php">New Timesheet</a>
              <a class="dropdown-item" href="past-timesheet.php">Past Timesheets</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="select-role.php">Select Role</a>
          </li>
          <li class="nav-item">
            $logged
          </li>
        </ul>
      </div>
    </nav>
HEADER;

  return $header;
}

function getHTMLAdminHeader($pageTitle, $loggedIn){
    $logged = isset($loggedIn) ? '<a class="nav-link" href="logout.php">Log Out</a>' : '<a class="nav-link" href="login.php">Members Login</a>';

    // Create the header placing it in a HEREDOC
    $header = <<<HEADER
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>$pageTitle</title>
    <meta charset="utf-8">
    <meta name="description" content="Timesheet Manager">
    <meta name="keywords" content="Timesheet,Staff,Report">
    <meta name="author" content="DTS">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="theme-color" content="#b1ddef">
    <meta http-equiv="refresh" content="900;url=logout.php" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="manifest.json">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <script src="js/libraries/jquery-3.3.1.min.js"></script>
    <script src="js/libraries/bootstrap.min.js"></script>
  </head>
  <body>
  <div id="wrapper">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
      <a class="navbar-brand" href="index.php">
        <i class="far fa-calendar-alt"></i>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavigation" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNavigation">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Account</a>
            <div class="dropdown-menu" aria-labelledby="accountDropdown">
              <a class="dropdown-item" href="manage-account.php">Manage Account</a>
              <a class="dropdown-item" href="manage-archived-account.php">Manage Archived Account</a>
              <a class="dropdown-item" href="create-account.php">Create Account</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="activityDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Activities</a>
            <div class="dropdown-menu" aria-labelledby="activityDropdown">
              <a class="dropdown-item" href="manage-activity.php">Manage Activities</a>
              <a class="dropdown-item" href="create-activity.php">Create Activity</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="departmentDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Departments</a>
            <div class="dropdown-menu" aria-labelledby="departmentDropdown">
              <a class="dropdown-item" href="manage-department.php">Manage Departments</a>
              <a class="dropdown-item" href="create-department.php">Create Department</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="jobDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Jobs</a>
            <div class="dropdown-menu" aria-labelledby="jobDropdown">
              <a class="dropdown-item" href="manage-job.php">Manage Jobs</a>
              <a class="dropdown-item" href="create-job.php">Create Job</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="projectDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Projects</a>
            <div class="dropdown-menu" aria-labelledby="projectDropdown">
              <a class="dropdown-item" href="manage-project.php">Manage Projects</a>
              <a class="dropdown-item" href="create-project.php">Create Projects</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="teamDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Teams</a>
            <div class="dropdown-menu" aria-labelledby="teamDropdown">
              <a class="dropdown-item" href="manage-team.php">Manage Teams</a>
              <a class="dropdown-item" href="create-team.php">Create Team</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="timesheetDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Timesheets</a>
            <div class="dropdown-menu" aria-labelledby="timesheetDropdown">
              <a class="dropdown-item" href="manage-timesheet.php">Manage Timesheets</a>
              <a class="dropdown-item" href="create-timesheet.php">Create Timesheets</a>
              <a class="dropdown-item" href="report-timesheet.php">Reports</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="select-role.php">Select Role</a>
          </li>
          <li class="nav-item">
            $logged
          </li>
        </ul>
      </div>
    </nav>
HEADER;

  return $header;
}

// Adds footer to page
function getHTMLFooter() {
    return "<div class='push'></div>

    <footer class='page-footer'>&copy;".date('Y')." Timesheets</footer>
    ";

}

// Ends the html page a
function getHTMLEnd() {
    return "
      </div>
    </body>
  </html>";
}

// Function to generate the login form that appears in the top left
// of every page if user is not logged in
function getLoginForm()
{
    // Create the login form
    $loginForm = "
    <div id='login'>
        <form action='loginProcess.php' method='post'>
            <label>Username: </label>
            <input type='text' name='username'  placeholder='Username....'  required>

            <label>Password:</label>
            <input type='password' name='password'  placeholder='Password....'  required>

            <input type='submit' value='Login'  name='loginForm'/>
        </form>";

    if (!empty($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        foreach ($errors as $error) {
            $loginForm .= "<p class='error'>$error</p>";

        }

        unset($_SESSION['errors']);
    }

    // Close div surrounding form
    $loginForm .= "</div>";

    return $loginForm;
}

// Function to validate the users entry into the login form
function validateLoginForm($dbConn)
{
    // Create the input array for the username and password
    $input = array();
    $errors = array();

    // Start the validation on the username and password
    $input['username'] = filter_has_var(INPUT_POST, 'username') ? $_POST['username'] : null;
    $input['username'] = trim($input['username']);

    if (empty($input['username'])) {
        $errors[] = "Your username has not been set.";
    }

    $input['password'] = filter_has_var(INPUT_POST, 'password') ? $_POST['password'] : null;
    $input['password'] = trim($input['password']);

    if (empty($input['password'])) {
        $errors[] = "Your password has not been set.";
    }

    // Query the database to check if username and password are correct
    if (empty ($errors)) {
        // Try to carry out the database search
        try {
            $sqlQuery = "SELECT passwordHash
                           FROM timesheets_user
                          WHERE username = :username";

            $stmt = $dbConn->prepare($sqlQuery);
            $stmt->execute(array(':username' => $input['username']));
            $row = $stmt->fetchObject();

            // Check the query returned some results
            if ($stmt->rowCount() > 0) {
                $passwordHash = $row->passwordHash;
                // If the password is a match
                if (password_verify($input['password'], $passwordHash)) {
                    $_SESSION['username'] = $input['username'];

                } else {
                    $errors[] = "Your username or password is incorrect";

                }
            } else {
                $errors[] = "Your username or password is incorrect";

            }

            // Log the exception in a file elsewhere
        } catch (Exception $e) {
            $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
        }
    }
    // Return an array of the input and errors arrays
    return $errors;
}

// Function to logout user by destroying the users session
function logoutUser($loggedIn, $redirect)
{
    // Destroy the users Session
    unset($loggedIn);
    session_destroy();

    return header('Location: ' . $redirect);
}

// Function to get all of the roles the user can choose from
function getUserRoles($dbConn, $loggedIn){

  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT timesheets_role.role_id,
                        timesheets_role.role_type
                   FROM timesheets_role
                   JOIN timesheets_user_role
                     ON timesheets_user_role.role_id = timesheets_role.role_id
                   JOIN timesheets_user
                     ON timesheets_user.user_id = timesheets_user_role.user_id
                  WHERE timesheets_user.username = :username";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':username' => $loggedIn));
    $rows = $stmt->fetchAll();

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      $role_ids = array();
      $role_types = array();

      // Loop through resultsstmt
      foreach($rows as $row){
        array_push($role_ids, $row['role_id']);
        array_push($role_types, $row['role_type']);
      }

    } else{
      $error = "Sorry, it appears you don't have a role associated with your account. Please contact your admnistrator to receive a role.";
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }

  if (!empty($error)){
    return $error;
  } else{
    return array_combine($role_ids, $role_types);
  }

}

function getDepartments($dbConn, $loggedIn){


    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT timesheets_department.department_id,
                            timesheets_department.department_name
                            FROM timesheets_department";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':username' => $loggedIn));
        $rows = $stmt->fetchAll();

        // Check the query returned some results
        if($stmt->rowCount() > 0){

            $department_ids = array();
            $department_names = array();



            // Loop through resultsstmt
            foreach($rows as $row){
                array_push($department_ids, $row['department_id']);
                array_push($department_names, $row['department_name']);
            }

        } else{
            $error = "Sorry, it appears you don't have a role associated with your account. Please contact your admnistrator to receive a role.";
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    if (!empty($error)){
        return $error;
    } else{
        return array_combine($department_ids, $department_names);
    }

}

function getProjects($dbConn, $loggedIn){


    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT timesheets_project.project_id,
                            timesheets_project.project_name
                            FROM timesheets_project";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':username' => $loggedIn));
        $rows = $stmt->fetchAll();

        // Check the query returned some results
        if($stmt->rowCount() > 0){

            $project_ids = array();
            $project_names = array();



            // Loop through resultsstmt
            foreach($rows as $row){
                array_push($project_ids, $row['project_id']);
                array_push($project_names, $row['project_name']);
            }

        } else{
            $error = "Sorry, it appears you don't have a role associated with your account. Please contact your admnistrator to receive a role.";
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    if (!empty($error)){
        return $error;
    } else{
        return array_combine($project_ids, $project_names);
    }

}

function getActivities($dbConn, $loggedIn){


    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT timesheets_activity.activity_id,
                            timesheets_activity.activity_type
                            FROM timesheets_activity";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':username' => $loggedIn));
        $rows = $stmt->fetchAll();

        // Check the query returned some results
        if($stmt->rowCount() > 0){

            $activity_ids = array();
            $activity_types = array();



            // Loop through resultsstmt
            foreach($rows as $row){
                array_push($activity_ids, $row['activity_id']);
                array_push($activity_types, $row['activity_type']);
            }

        } else{
            $error = "Sorry, it appears you don't have a role associated with your account. Please contact your admnistrator to receive a role.";
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    if (!empty($error)){
        return $error;
    } else{
        return array_combine($activity_ids, $activity_types);
    }

}

function sqlQuerySearchAndConvertToJson($dbConn, $loggedIn, $sqlQuery){

    // Try to carry out the database search
    try{
        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':username' => $loggedIn));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $myJSON = json_encode($rows);
        } else{
            $error = "Sorry, it appears you don't have a role associated with your account. Please contact your admnistrator to receive a role.";
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    if (!empty($error)){
        return "<script>window.data = " . $error . "</script>";
    } else{
        return "<script>window.data = " . $myJSON . "</script>";
    }
}

function sqlQueryToPhpArray($dbConn, $loggedIn, $sqlQuery){

    // Try to carry out the database search
    try{
        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':username' => $loggedIn));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check the query returned some results
        if(!$stmt->rowCount() > 0){
            $error = "Sorry, it appears you don't have a role associated with your account. Please contact your admnistrator to receive a role.";
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    if (!empty($error)){
        return $error;
    } else{
        return $rows;
    }
}

function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');

    $file = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($file, $line, $delimiter);
        fwrite($file,"\r\n");
    }

    fclose($file);
    die;
}


// get the actiity 
/*function getActivity($dbConn, $activity_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT activity_type
                   FROM timesheets_activity
                  WHERE activity_id = :activity_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':activity_id' => $activity_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $activity_type = $rows->activity_type;

    } else{
      $activity_type = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $activity_type = null;
  }

  
  return $activity_type;
  
}*/