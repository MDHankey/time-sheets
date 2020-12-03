<?php
  require_once 'env/environment.php';
  require_once 'functions/functions.php';
  session_start();

  // Check for logged in user
  $loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

  // Check the user role of the logged in user
  $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
  // Page title
  $pageTitle = 'Pricing';  

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
  <h1>Pricing</h1>
</div>

<div class="container">
  <div class="card-deck mb-3 text-center">
   <div class="card mb-4 shadow-sm">
     <div class="card-header">
       <h4 class="my-0 font-weight-normal">Standard</h4>
     </div>
     <div class="card-body">
       <h1 class="card-title pricing-card-title">£50 <small class="text-muted">/ mo</small></h1>
       <ul class="list-unstyled mt-3 mb-4">
         <li>20 users included</li>
         <li>2 GB of storage</li>
         <li>Email support</li>
         <li>Help center access</li>
       </ul>
         <button type="button" onclick="window.location.href='create-account.php?accountType=1'" class="btn btn-lg btn-block btn-outline-primary">Sign up for free</button>
     </div>
   </div>
   <div class="card mb-4 shadow-sm">
     <div class="card-header">
       <h4 class="my-0 font-weight-normal">Pro</h4>
     </div>
     <div class="card-body">
       <h1 class="card-title pricing-card-title">£150 <small class="text-muted">/ mo</small></h1>
       <ul class="list-unstyled mt-3 mb-4">
         <li>60 users included</li>
         <li>10 GB of storage</li>
         <li>Priority email support</li>
         <li>Help center access</li>
       </ul>
         <button type="button" onclick="window.location.href='create-account.php?accountType=2'" class="btn btn-lg btn-block btn-primary">Get started</button>
     </div>
   </div>
   <div class="card mb-4 shadow-sm">
     <div class="card-header">
       <h4 class="my-0 font-weight-normal">Enterprise</h4>
     </div>
     <div class="card-body">
       <h1 class="card-title pricing-card-title">£250 <small class="text-muted">/ mo</small></h1>
       <ul class="list-unstyled mt-3 mb-4">
         <li>120 users included</li>
         <li>20 GB of storage</li>
         <li>Phone and email support</li>
         <li>Help center access</li>
       </ul>
         <button type="button" onclick="window.location.href='contact.php'" class="btn btn-lg btn-block btn-primary">Contact us</button>
     </div>
   </div>
  </div>
</div>
<?php

echo getHTMLFooter();
echo getHTMLEnd();