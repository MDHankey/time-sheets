<?php
/* Timesheet Reports
 * admin page
 * requires logged on
*/
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
require_once 'functions/reportQueries.php';
session_start();

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Page title
$pageTitle = 'Report Timesheet';  

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;

$departments = getDepartments($dbConn, $loggedIn);
$projects = getProjects($dbConn, $loggedIn);

$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : null;
$departmentSet = isset( $_GET['department']) &&  $_GET['department'] != "all";
$departmentVal = isset($_GET['department']) ? $_GET['department'] : 'all';

$projectSet    = isset( $_GET['project']) &&  $_GET['project'] != "all";
$projectVal = isset($_GET['project']) ? $_GET['project'] : 'all';


// Get the correct page header depending on the users current role
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
  <h1><?php echo $pageTitle?></h1>
</div>
<?php

// If not logged in show text
if (isset($errorText)){
	echo "<p>$errorText</p>";

} else{
	// The main page content if user has correct permissions
	?>
    <div class="container-fluid">
		<div class="row">
			<div id="reportConfigContainer" class="col-lg-2">
				<h4 class="blockquote text-center">Report Data</h4>
				<form name="form" action="" method="get">
					<div class="reportConfig form-group row">
						<label class="input-group-text">Start Date:</label>
						<div class="col-sm-12">
							<input type="date" name="startDate" id="startDate" class="form-control" <?php echo " value='" .  $startDate . "'>"; ?>
						</div>
					</div>
					<div class="reportConfig form-group row">
						<label class="input-group-text">End Date:</label>
						<div class="col-sm-12">
							<input type="date" name="endDate" id="endDate" class="form-control"<?php echo " value='" . $endDate . "'>"; ?>
						</div>
					</div>
					<div class="reportConfig form-group row">
						<div class="input-group-prepend">
							<label class="input-group-text" for="department">Department</label>
						</div>
						<select name="department" class="custom-select" id="department">
							<option value="all">All</option>
							<?php
								// Loop though each of the roles to get type and id
								Foreach ($departments as $key => $value) {
									$selected = $key == $_GET['department'] ? 'selected' : '';
									echo "<option value='" . $key . "' ". $selected . ">" . $value . "</option>";
								}
							?>
						</select>
					</div>
					<div class="reportConfig form-group row">
						<div class="input-group-prepend">
							<label class="input-group-text" for="project">Project</label>
						</div>
						<select name="project" class="custom-select" id="project">
							<option value="all">All</option>
							<?php
							// Loop though each of the roles to get type and id
							Foreach ($projects as $key => $value) {
								$selected = $key == $_GET['project'] ? 'selected' : '';
								echo "<option value='" . $key . "' ". $selected . ">" . $value . "</option>";
							}


							?>
						</select>
					</div>
					<div class="reportConfig form-group row">
						<button id="reportUpdate" class="btn btn-primary btn-lg btn-block">Generate Graph</button>
					</div>

				</form>
			</div>

			<div id="reportCanvas" class="col-lg-9">
				<canvas id="canvas"></canvas>
			</div>


            <!-- graph settings -->
				   <div class="col-lg-1">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#settings">
			  <i class ="modal-title fas fa-cog"></i>
			</button>


           <a target='_blank' style="color:white; margin-top: 15px;" href="csvDownload.php?startDate=<?=$startDate;?>&endDate=<?=$endDate;?>&department=<?=$departmentVal;?>&project=<?=$projectVal;?>" title="CSV Download">
            <button type="button"  style="margin-top:15px;" class="btn btn-primary btn-lg btn-block">
               <i class ="modal-title fas fa-download"></i>
           </button>
           </a>



			<!-- Modal -->
			<div class="modal fade" id="settings" tabindex="-1" role="dialog" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title">Graph Settings</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div id="reportSettings" class="modal-body">
					<div class="reportConfig form-group row">
						<div class="input-group-prepend">
							<label class="input-group-text" for="type">Chart Type</label>
						</div>
						<select class="custom-select" id="type">
							<option value="bar">Vertical Bar</option>
							<option value="line">Line</option>
						</select>
					</div>
					<div class="reportConfig form-group row">
						<legend class="col-form-label col-sm-4 pt-0">High Contrast:</legend>
						<div class="col-sm-8">
							<div class="form-check-inline">
								<input class="form-check-input" type="radio" name="highContrast" id="highContrastTrue">
								<label class="form-check-label">
									Yes
								</label>
							</div>
							<div class="form-check-inline">
								<input class="form-check-input" type="radio" name="highContrast" id="highContrastFalse" checked>
								<label class="form-check-label">
									No
								</label>
							</div>
						</div>
					</div>
					<div class="reportConfig form-group row" id="stackRadio">
						<legend class="col-form-label col-sm-4 pt-0">Stack data:</legend>
						<div class="col-sm-8">
							<div class="form-check-inline">
								<input class="form-check-input" type="radio" name="stack" id="stackTrue" checked>
								<label class="form-check-label">
									Yes
								</label>
							</div>
							<div class="form-check-inline">
								<input class="form-check-input" type="radio" name="stack" id="stackFalse">
								<label class="form-check-label">
									No
								</label>
							</div>
						</div>
					</div>
					<div id ="smoothLines" class="reportConfig form-group row" id="smoothLines">
						<legend class="col-form-label col-sm-4 pt-0">Line Stiffness:</legend>
						<div class="col-sm-8">
							<input id="smoothLineSlider" class="form-control-range" type="range" value="0.5" min="0.25" max="1" step="0.01">
						</div>
					</div>
					<div id ="pointRadius" class="reportConfig form-group row">
						<legend class="col-form-label col-sm-4 pt-0">Point Radius:</legend>
						<div class="col-sm-8">
							<input id="pointRadiusSlider" class="form-control-range" type="range" value="5" min="0" max="10" step="1">
						</div>
					</div>
					<div class="reportConfig form-group row"  id="fillSpace">
						<legend class="col-form-label col-sm-4 pt-0">Fill space:</legend>
						<div class="col-sm-8">
							<div class="form-check-inline">
								<input class="form-check-input" type="radio" name="fillLines" id="fillTrue"  class="form-control" checked>
								<label class="form-check-label">
									Yes
								</label>
							</div>
							<div class="form-check-inline">
								<input class="form-check-input" type="radio" name="fillLines" id="fillFalse" checked>
								<label class="form-check-label">
									No
								</label>
							</div>
						</div>
					</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				  </div>
				</div>
			  </div>
			</div>
		</div>

        </div>





    </div>
    <?php
}

echo getHTMLFooter();

if ($departmentSet && $projectSet &&  isset( $_GET['startDate']) && isset( $_GET['endDate'])){
    echo sqlQuerySearchAndConvertToJson($dbConn, $loggedIn, getDepartmentProjectEmployeeTimeBetweenTwoDates($_GET['department'], $_GET['project'], $_GET['startDate'],  $_GET['endDate']));
}
else if ($projectSet &&  isset( $_GET['startDate']) && isset( $_GET['endDate'])){
    echo sqlQuerySearchAndConvertToJson($dbConn, $loggedIn, getProjectEmployeeTimeBetweenTwoDates($_GET['project'], $_GET['startDate'],  $_GET['endDate']));
}
else if ($departmentSet &&  isset( $_GET['startDate']) && isset( $_GET['endDate'])){
    echo sqlQuerySearchAndConvertToJson($dbConn, $loggedIn, getDepartmentEmployeeTimeBetweenTwoDates($_GET['department'], $_GET['startDate'],  $_GET['endDate']));
}
else if ( isset( $_GET['startDate']) && isset( $_GET['endDate'])){
    echo sqlQuerySearchAndConvertToJson($dbConn, $loggedIn, getAllEmployeeTimeBetweenTwoDates($_GET['startDate'],  $_GET['endDate']));
}
else{
    echo sqlQuerySearchAndConvertToJson($dbConn, $loggedIn, getAllEmployeeTime());
}

?>
<script src="js/utils.js"></script>
<script src="js/libraries/Chart.min.js" type="text/javascript"></script>
<script src="js/graphs.js" type="text/javascript"></script>

<?php
echo getHTMLEnd();