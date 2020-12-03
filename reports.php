<?php
  require_once 'env/environment.php';
  require_once 'functions/functions.php';
  session_start();

  // Check for logged in user
  $loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

  // Check the user role of the logged in user
  $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
  // Page title
  $pageTitle = 'Reporting';

  // Get the correcct page header depending on the users role and wheter or not they are logged in
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
<div id="reportMain col-sm-12">
    <div id="reportConfigContainer">
		<h4>Report Configuration</h4>
        <div class="reportConfig">
            Chart Type:
            <select id="type">
                <option value="bar">Vertical Bar</option>
                <option value="line">Line</option>
                <option value="polarArea">Polar Area</option>
                <option value="radar">Radar</option>
            </select>
        </div>
        <div class="reportConfig">
            <form>
                High Contrast Mode:
                <input type="radio" name="highContrast" id="highContrastTrue">Yes <input type="radio" name="highContrast" checked="checked">No
            </form>
        </div>
        <div class="reportConfig">
            <form id ="stackRadio">
                Stack the data:
                <input type="radio" name="stack" id="stackTrue" checked="checked">Yes <input type="radio" name="stack" id="stackFalse">No
            </form>
        </div>
        <div class="reportConfig">
            <form id ="smoothLines">
                Line Stiffness:
                <div class="range-slider">
                    <input id="smoothLineSlider" class="rs-range" type="range" value="0.5" min="0.25" max="1" step="0.01">
                </div>
            </form>
        </div>
        <div class="reportConfig">
            <form id ="pointRadius">
                Point Radius:
                <div class="range-slider">
                    <input id="pointRadiusSlider" class="rs-range" type="range" value="5" min="0" max="10" step="1">
                </div>
            </form>
        </div>
		<div class="reportConfig">
            <form>
                Fill space:
                <input type="radio" name="fillLines" id="fillTrue" checked="checked">Yes <input type="radio" name="fillLines">No
            </form>
        </div>
		<div class="reportConfig">
            <button id="reportUpdate">Generate Graph</button>
        </div>
    </div>
    <div id="reportCanvas">
        <canvas id="canvas"></canvas>
    </div>
</div>
<?php

echo getHTMLFooter();
?>
    <script src="js/utils.js"></script>
    <script src="js/libraries/Chart.min.js" type="text/javascript"></script>
    <script src="js/graphs.js" type="text/javascript"></script>
<?php

echo getHTMLEnd();