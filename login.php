<?php
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
session_start();

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

//Check that the person has filled out the login form
// Call the functions which carry out validation and the query
if (isset($_POST['username']) || isset($_POST['password'])) {
    $errors = validateLoginForm($dbConn);
}

// Check for logged in user
echo $loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Page title
$pageTitle = 'Login';

// Get the correcct page header depending on the users role and wheter or not they are logged in
if (!isset($loggedIn)) {
    echo getHTMLHeader($pageTitle, $loggedIn);

} elseif (isset($userRole) && $userRole == 2) {        // User level
    echo getHTMLUserHeader($pageTitle, $loggedIn);

} elseif (isset($userRole) && $userRole == 1) {        // Admin level
    echo getHTMLAdminHeader($pageTitle, $loggedIn);

} else {
    echo getHTMLHeader($pageTitle, $loggedIn);

}

// Get the current base URL
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
//   $baseURL = "http://localhost/w16014936.github.io";
    $baseURL = "http://unn-w16030249.newnumyspace.co.uk/timesheets";
//    $baseURL = "http://unn-w16038628.newnumyspace.co.uk/Year3/GroupAssignment/timesheets";

} else {
//       $baseURL = "https://w16014936.github.io";
    $baseURL = "http://unn-w16030249.newnumyspace.co.uk/timesheets";
//     $baseURL = "http://unn-w16038628.newnumyspace.co.uk/Year3/GroupAssignment/timesheets";
}


// First check to see if $_SERVER['HTTP_REFERER'] is set
// and check that it is an expected source
if (isset($_SERVER['HTTP_REFERER'])) {
    if (strpos($_SERVER['HTTP_REFERER'], $baseURL . '/login.php') !== FALSE) {
        $redirect = $baseURL;

    } else if (strpos($_SERVER['HTTP_REFERER'], $baseURL) !== FALSE) {
        $redirect = $_SERVER['HTTP_REFERER'];

    } else {
        $redirect = $baseURL;

    }
} else {
    $redirect = $baseURL;

}

// If user is logged in currently redirect them back to where they've just came from
if (isset($loggedIn)) {
    // Check if role has been set
    if ($isset['role']) {
        header('Location: ' . $redirect);
        exit();

    } else {
        $redirect .= '/select-role.php';
        header('Location: ' . $baseURL . '/select-role.php');
        exit();

    }
    header('Location: ' . $redirect);
    exit();
}

?>

    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <form action="login.php" name="loginForm" method="POST">
                    <div class="icon-div">
                        <i class="far fa-calendar-alt"></i>
                        <h1>Login</h1>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Username" name="username"
                               value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="password"
                               value="<?php echo isset($_POST['username']) ? $_POST['password'] : ''; ?>" required/>
                    </div>
                    <div class="login-error">
                        <?php
                        if (!empty($errors)) {
                            foreach ($errors as $error) {
                                echo "<p class='error'>$error</p>";

                            }
                        }
                        ?>
                    </div>
                    <button type="submit" id="login-button" class="btn btn-primary">login</button>
                </form>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
<?php

echo getHTMLEnd();