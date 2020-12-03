<?php
// Setting the location of the sessions file depeneding on if you are 
// on localhost or the newnumyspace server
if (strpos($_SERVER['HTTP_HOST'],'localhost') !== false) {
    define('SESSION_DIR', '../../sessionData');
} else {
    define('SESSION_DIR', '../sessionData');
}

// This is the unique session variable that will be set if 
// a user has successfully logged in.
define('LOGGED_IN_INDICATOR', 'username');