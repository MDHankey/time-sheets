<?php
  require_once 'env/environment.php';
  require_once 'functions/functions.php';
  session_start();

  // Check for logged in user
  $loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

  // Check the user role of the logged in user
  $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
  // Page title
  $pageTitle = 'Timesheets';  

  // Get the correcct page header depending on the users role and wheter or not they are logged in
  if (!isset($loggedIn)){
    echo getHTMLHeader($pageTitle, $loggedIn);
    
  } elseif (isset($userRole) && $userRole == 2){        // User level 
    echo getHTMLUserHeader($pageTitle, $loggedIn);
    
  } elseif (isset($userRole) && $userRole == 1){        // Admin level
    echo getHTMLAdminHeader($pageTitle, $loggedIn);
      
  } else{
    echo getHTMLHeader($pageTitle, $loggedIn);
  
  }


?>
<div class="jumbotron text-center">
    <h1>Timesheet Management System</h1>
</div>

<div class="container" id="index-container">
  <div class="col-sm-12">
      <h2>Everything you need to run your business</h2>

      <p>Flexible Time Tracking and Staff Software that is lightweight, dynamic and affordable</p>

      <ul>
          <li>Fully <b>mobile</b> and compatible on all devices</li>
          <li>Drill down into <b>projects</b> by tracking time against specific tasks</li>
          <li>Manage all <b>activities</b> such as overtime and sickness</li>
          <li>Measure performance and resource across <b>departments</b></li>
          <li>View all data in a desired format with our <b>graph</b> functionality</li>
          <li>Integrated <b>role</b> permissions for handling sensitive data</li>
      </ul>

      <div id="container" style="width: 75%;">
          <canvas id="canvas"></canvas>
      </div>
  </div>
</div>
<?php

echo getHTMLFooter();
?>
    <script src="js/utils.js" type="text/javascript"></script>
    <script src="js/libraries/Chart.min.js" type="text/javascript"></script>
    <!--Graph purely for demo purposes on home page-->
    <script>
        var color = Chart.helpers.color;
        var barChartData = {
            labels: ['Rachel', 'Tina', 'Bradley', 'Jon', 'Jo', 'Hannah', 'Paul'],
            datasets: [{
                label: 'Singing',
                backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                borderColor: window.chartColors.red,
                borderWidth: 1,
                data: [
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101)

                ]
            }, {
                label: 'Dancing',
                backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 1,
                data: [
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101),
                    Math.floor(Math.random() * 101)

                ]
            }]

        };

        window.onload = function() {
            var ctx = document.getElementById('canvas').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Example'
                    }
                }
            });

        };

    </script>
    <script src="serviceWorker.js" type="text/javascript"></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('serviceWorker.js').then(function(registration) {
                    // Registration was successful
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    // registration failed :(
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
<?php
getHTMLEnd();