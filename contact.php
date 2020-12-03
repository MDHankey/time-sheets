<?php
require_once 'env/environment.php';
require_once 'functions/functions.php';
session_start();

// Check for logged in user
$loggedIn = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Check the user role of the logged in user
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
// Page title
$pageTitle = 'Contact';

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
<script>
    // Check if the user is on mobile
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent)) {
        var onMobile = true;
    }
</script>
    
<div class="jumbotron text-center">
    <h1>Contact Us</h1>
</div>

<div class="container contact-form">
    <form method="post" id="contactUsForm" action="contactProcess.php">
        <h3>Send us your query</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" id="contactUs-textName" name="txtName" class="form-control" placeholder="Your Name *"
                           value="" required/>
                </div>
                <div class="form-group">
                    <input type="text" id="contactUs-textEmail" name="txtEmail" class="form-control"
                           placeholder="Your Email *" value="" required/>
                </div>
                <div class="form-group">
                    <input type="number" id="contactUs-textPhone" name="txtPhone" class="form-control"
                           placeholder="Your Phone Number *"
                           value="" required/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <textarea id="contactUs-textMessage" name="txtMsg" class="form-control" placeholder="Your Message *"
                              style="width: 100%; height: 150px;" required></textarea>
                </div>
            </div>
        </div>
        <div class="form-group" id="contact-button-flex">
            <input type="submit" name="btnSubmit" id="contact-button" class="btn btn-primary" value="Send Message""/>

        </div>
    </form>
</div>
<?php

echo getHTMLFooter();
?>
<script>
// Change button width to 100% if user on mobile
if (onMobile) {
    document.getElementById("contact-button").style.width = "100%";
    var contactButtonFlex = document.getElementById("contact-button-flex");
    contactButtonFlex.style.justifyContent = "center";
    contactButtonFlex.style.alignItems = "center";
}
</script>
<script>
customFormValidation("contactUs-textName","contactUs-textEmail","contactUs-textPhone",
    "contactUs-textMessage","Please enter your name","Please enter your email address",
    "Please enter your phone number","Please enter the message you would like to send")
</script>
<?php
getHTMLEnd();