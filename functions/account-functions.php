<?php
  // get the account id
  function getAccount($dbConn, $account_id){
    // Try to carry out the database search
    try{
      $sqlQuery = "SELECT person_id
                     FROM timesheets_person
                    WHERE person_id = :account_id";

      $stmt = $dbConn->prepare($sqlQuery);
      $stmt->execute(array(':account_id' => $account_id));
      $rows = $stmt->fetchObject();

      // Check the query returned some results
      if($stmt->rowCount() > 0){
        $account = $rows->person_id;

      } else{
        $account = null;
      }

    // Log the exception
    } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
      $account = null;
    }


    return $account;

  }


  // Validate post data
  function validateUpdateAccountForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    $validAccountIDs = getAccountIDs($dbConn);

    $validTeamIDs = getTeamIDs($dbConn);

    /* Get the array of job ids */
    $validJobIDs = getJobIDs($dbConn);

    /* Get the array of department ids */
    $validDepartmentIDs = getDepartmentIDs($dbConn);

    /* Get the array of department ids */
    $validRoleIDs = getRoleIDs($dbConn);

    $input['account_id'] = filter_has_var(INPUT_POST, 'account_id') ? $_POST['account_id']: null;
    $input['account_id'] = trim($input['account_id']);
    $input['account_id'] = filter_var($input['account_id'], FILTER_VALIDATE_INT) ? $input['account_id'] : null;
    $input['account_id'] = in_array($input['account_id'], $validAccountIDs) ? $input['account_id']  : null;

    if(empty($input['account_id'])){
        $errors[] = "There is a problem with the account you are trying to edit.";

    }

    $input['update_username'] = filter_has_var(INPUT_POST, 'update_username') ? $_POST['update_username']: null;
    $input['update_username'] = trim($input['update_username']);

    if(empty($input['account_id'])){
        $errors[] = "There is a problem with the account you are trying to edit.";

    }

    /* Team id validation */
    $input['team_id'] = filter_has_var(INPUT_POST, 'team_id') ? $_POST['team_id']: null;
    $input['team_id'] = trim($input['team_id']);
    $input['team_id'] = filter_var($input['team_id'], FILTER_VALIDATE_INT) ? $input['team_id'] : null;
    $input['team_id'] = in_array($input['team_id'], $validTeamIDs) ? $input['team_id']  : null;

    if(empty($input['team_id'])){
        $errors[] = "There is a problem with the team you are trying to set.";

    }


    /* Job id validation */
    $input['job_id'] = filter_has_var(INPUT_POST, 'job_id') ? $_POST['job_id']: null;
    $input['job_id'] = trim($input['job_id']);
    $input['job_id'] = filter_var($input['job_id'], FILTER_VALIDATE_INT) ? $input['job_id'] : null;
    $input['job_id'] = in_array($input['job_id'], $validJobIDs) ? $input['job_id']  : null;

    if(empty($input['job_id'])){
        $errors[] = "There is a problem with the Job you are trying to set.";

    }


    /* Department id validation */
    $input['department_id'] = filter_has_var(INPUT_POST, 'department_id') ? $_POST['department_id']: null;
    $input['department_id'] = trim($input['department_id']);
    $input['department_id'] = filter_var($input['department_id'], FILTER_VALIDATE_INT) ? $input['department_id'] : null;
    $input['department_id'] = in_array($input['department_id'], $validDepartmentIDs) ? $input['department_id']  : null;

    if(empty($input['department_id'])){
        $errors[] = "There is a problem with the Department you are trying to set.";

    }

    /* Department id validation */
    $input['role_id'] = filter_has_var(INPUT_POST, 'role_id') ? $_POST['role_id']: null;
    $input['role_id'] = trim($input['role_id']);
    $input['role_id'] = filter_var($input['role_id'], FILTER_VALIDATE_INT) ? $input['role_id'] : null;
    $input['role_id'] = in_array($input['role_id'], $validRoleIDs) ? $input['role_id']  : null;

    if(empty($input['role_id'])){
        $errors[] = "There is a problem with the Role you are trying to set.";

    }

    $input['update_title'] = filter_has_var(INPUT_POST, 'update_title') ? $_POST['update_title']: null;
    $input['update_title'] = trim($input['update_title']);
    if(empty($input['update_title'])){
        $errors[] = "You have not entered a valid title.";

    } else if (strlen ($input['update_title']) > 10){
      $errors[] = "You have entered a title that is too long. The character limit is 10.";
      $input['update_title'] = "";

    }

    $input['update_forename'] = filter_has_var(INPUT_POST, 'update_forename') ? $_POST['update_forename']: null;
    $input['update_forename'] = trim($input['update_forename']);
    if(empty($input['update_forename'])){
        $errors[] = "You have not entered a valid forename.";

    } else if (strlen ($input['update_forename']) > 50){
      $errors[] = "You have entered a forename that is too long. The character limit is 50.";
      $input['update_forename'] = "";

    }

    $input['update_surname'] = filter_has_var(INPUT_POST, 'update_surname') ? $_POST['update_surname']: null;
    $input['update_surname'] = trim($input['update_surname']);
    if(empty($input['update_surname'])){
        $errors[] = "You have not entered a valid surname.";

    } else if (strlen ($input['update_surname']) > 50){
      $errors[] = "You have entered a surname that is too long. The character limit is 50.";
      $input['update_surname'] = "";

    }

    $input['update_phone_number'] = filter_has_var(INPUT_POST, 'update_phone_number') ? $_POST['update_phone_number']: null;
    $input['update_phone_number'] = trim($input['update_phone_number']);
    $input['update_phone_number'] = filter_var($input['update_phone_number'], FILTER_SANITIZE_NUMBER_INT);
    $input['update_phone_number'];
    if (strlen($input['update_phone_number']) < 10 || strlen($input['update_phone_number']) > 14) {
      $errors[] = "You have entered an invalid phone number.";

    } else if  (empty($input['update_phone_number'])){
      $errors[] = "You have entered an invalid phone number.";

    }


    $input['update_email'] = filter_has_var(INPUT_POST, 'update_email') ? $_POST['update_email']: null;
    $input['update_email'] = trim($input['update_email']);
    if (!filter_var($input['update_email'], FILTER_VALIDATE_EMAIL)) {
      $errors[] = "You have entered an invalid email.";
    }

    $input['update_address_line_1'] = filter_has_var(INPUT_POST, 'update_address_line_1') ? $_POST['update_address_line_1']: null;
    $input['update_address_line_1'] = trim($input['update_address_line_1']);
    if(empty($input['update_address_line_1'])){
        $errors[] = "You have not entered a valid address line 1.";

    }

    $input['update_address_line_2'] = filter_has_var(INPUT_POST, 'update_address_line_2') ? $_POST['update_address_line_2']: null;
    $input['update_address_line_2'] = trim($input['update_address_line_2']);

    $input['update_address_line_3'] = filter_has_var(INPUT_POST, 'update_address_line_3') ? $_POST['update_address_line_3']: null;
    $input['update_address_line_3'] = trim($input['update_address_line_3']);
    if(empty($input['update_address_line_3'])){
        $errors[] = "You have not entered a valid town/city.";

    }

    $input['update_address_line_4'] = filter_has_var(INPUT_POST, 'update_address_line_4') ? $_POST['update_address_line_4']: null;
    $input['update_address_line_4'] = trim($input['update_address_line_4']);
    if(empty($input['update_address_line_4'])){
        $errors[] = "You have not entered a valid county.";

    }

    $input['update_address_line_5'] = filter_has_var(INPUT_POST, 'update_address_line_5') ? $_POST['update_address_line_5']: null;
    $input['update_address_line_5'] = trim($input['update_address_line_5']);
    if(empty($input['update_address_line_5'])){
        $errors[] = "You have not entered a valid country.";

    }

    $input['update_postcode'] = filter_has_var(INPUT_POST, 'update_postcode') ? $_POST['update_postcode']: null;
    $input['update_postcode'] = trim($input['update_postcode']);
    if(empty($input['update_postcode'])){
        $errors[] = "You have not entered a valid postcode.";

    }


    $input['update_date_of_birth'] = filter_has_var(INPUT_POST, 'update_date_of_birth') ? $_POST['update_date_of_birth']: null;
    $input['update_date_of_birth'] = trim($input['update_date_of_birth']);
    if(empty($input['update_date_of_birth'])){
        $errors[] = "You have not entered a valid date of birth.";

    }

    $input['update_contracted_hours'] = filter_has_var(INPUT_POST, 'update_contracted_hours') ? $_POST['update_contracted_hours']: null;
    $input['update_contracted_hours'] = trim($input['update_contracted_hours']);
    if(empty($input['update_contracted_hours'])){
        $errors[] = "You have not entered valid contract hours.";

    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}


function validateDeleteAccountForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of job ids*/
    $validAccountIDs = getArchivedAccountIDs($dbConn);

    /* ID validation */
    $input['account_id'] = filter_has_var(INPUT_POST, 'account_id') ? $_POST['account_id']: null;
    $input['account_id'] = trim($input['account_id']);
    $input['account_id'] = filter_var($input['account_id'], FILTER_VALIDATE_INT) ? $input['account_id'] : null;
    $input['account_id'] = in_array($input['account_id'], $validAccountIDs) ? $input['account_id']  : null;

    if(empty($input['account_id'])){
        $errors[] = "There is a problem with the account you are trying to delete.";

    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}



function validateArchiveAccountForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of job ids*/
    $validAccountIDs = getUnarchivedAccountIDs($dbConn);

    /* ID validation */
    $input['account_id'] = filter_has_var(INPUT_POST, 'account_id') ? $_POST['account_id']: null;
    $input['account_id'] = trim($input['account_id']);
    $input['account_id'] = filter_var($input['account_id'], FILTER_VALIDATE_INT) ? $input['account_id'] : null;
    $input['account_id'] = in_array($input['account_id'], $validAccountIDs) ? $input['account_id']  : null;

    if(empty($input['account_id'])){
        $errors[] = "There is a problem with the account you are trying to archive.";

    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}


function validateRestoreAccountForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of job ids*/
    $validAccountIDs = getArchivedAccountIDs($dbConn);

    /* ID validation */
    $input['account_id'] = filter_has_var(INPUT_POST, 'account_id') ? $_POST['account_id']: null;
    $input['account_id'] = trim($input['account_id']);
    $input['account_id'] = filter_var($input['account_id'], FILTER_VALIDATE_INT) ? $input['account_id'] : null;
    $input['account_id'] = in_array($input['account_id'], $validAccountIDs) ? $input['account_id']  : null;

    if(empty($input['account_id'])){
        $errors[] = "There is a problem with the account you are trying to restore.";

    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}


function getAccountIDs($dbConn){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT person_id
                   FROM timesheets_person";



    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $account_ids = array();

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        array_push($account_ids, $row['person_id']);

      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }


  return $account_ids;
}

function getArchivedAccountIDs($dbConn){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT person_id
                   FROM timesheets_person
                  WHERE archive = 1";



    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $account_ids = array();

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        array_push($account_ids, $row['person_id']);

      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }


  return $account_ids;
}

function getUnarchivedAccountIDs($dbConn){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT person_id
                   FROM timesheets_person
                  WHERE archive = 0";



        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $account_ids = array();

        // Check the query returned some results
        if($stmt->rowCount() > 0){

            // Loop through resultsstmt
            foreach($rows as $row){
                array_push($account_ids, $row['person_id']);

            }
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }


    return $account_ids;
}

function setUsernameByAccountID($dbConn, $input){
  $account_id = $input['account_id'];
  $username = $input['update_username'];

  // Try to carry out the database entries
  try{
    $sqlInsert = "UPDATE timesheets_user 
                    JOIN timesheets_person 
                      ON timesheets_person.user_id = timesheets_user.user_id
                     SET username = :username
                   WHERE timesheets_person.person_id = :account_id";

    $stmt = $dbConn->prepare($sqlInsert);

    $stmt->execute(array(':username' => $username,
                         ':account_id' => $account_id));


    // If the query worked display message to user
    if ($stmt){

      return true;
    }

  } catch(Exception $e){
      echo $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }

  return false;
}

function deleteUserRolesByAccountID($dbConn, $account_id){
    
    // Try to carry out the database entries
    try{
        $sqlDelete = "DELETE FROM timesheets_user_role
                            WHERE user_id = :account_id";
        
        $stmt = $dbConn->prepare($sqlDelete);
        $stmt->execute(array(':account_id' => $account_id));
        
        // If the query worked display message to user
        if ($stmt){
            return true;
        }
        
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }
    
    return false;
    
 
}


function setUserRoleByAccountID($dbConn, $input){
    $account_id = $input['account_id'];
    $role_id = $input['role_id'];
    
    if ($role_id == 1){
        // Delete all roles the user has
        deleteUserRolesByAccountID($dbConn, $account_id);
        insertAdminRole($dbConn, $account_id);
        
    } else if ($role_id == 2){
        // Delete all roles the user has
        deleteUserRolesByAccountID($dbConn, $account_id);
        insertUserRole($dbConn, $account_id);
        
    }
    
}

function setAccount($dbConn, $input){
  $account_id = $input['account_id'];
  $job_id = $input['job_id'];
  $department_id = $input['department_id'];
  $team_id = $input['team_id'];
  $role_id = $input['role_id'];
  $title = $input['update_title'];
  $forename = $input['update_forename'];
  $surname = $input['update_surname'];
  $phone_number = $input['update_phone_number'];
  $email = $input['update_email'];
  $address_line_1 = $input['update_address_line_1'];
  $address_line_2 = $input['update_address_line_2'];
  $address_line_3 = $input['update_address_line_3'];
  $address_line_4 = $input['update_address_line_4'];
  $address_line_5 = $input['update_address_line_5'];
  $postcode = $input['update_postcode'];
  $date_of_birth = $input['update_date_of_birth'];
  $contracted_hours = $input['update_contracted_hours'];

  // Try to carry out the database entries
  try{
    $sqlInsert = "UPDATE timesheets_person 
                     SET job_id = :job_id,
                         department_id = :department_id,
                         team_id = :team_id,
                         contracted_hours = :contracted_hours,
                         title = :title,
                         forename = :forename,
                         surname = :surname,
                         phone_number = :phone_number,
                         email = :email,
                         address_line_1 = :address_line_1,
                         address_line_2 = :address_line_2,
                         address_line_3 = :address_line_3,
                         address_line_4 = :address_line_4,
                         address_line_5 = :address_line_5,
                         post_code = :post_code,
                         date_of_birth = :date_of_birth
                   WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlInsert);

    $stmt->execute(array(':job_id' => $job_id,
                         ':department_id' => $department_id,
                         ':team_id' => $team_id,
                         ':contracted_hours' => $contracted_hours,
                         ':title' => $title,
                         ':forename' => $forename,
                         ':surname' => $surname,
                         ':phone_number' => $phone_number,
                         ':email' => $email,
                         ':address_line_1' => $address_line_1,
                         ':address_line_2' => $address_line_2,
                         ':address_line_3' => $address_line_3,
                         ':address_line_4' => $address_line_4,
                         ':address_line_5' => $address_line_5,
                         ':post_code' => $postcode,
                         ':date_of_birth' => $date_of_birth,
                         ':account_id' => $account_id));


    // If the query worked display message to user
    if ($stmt){
      setUsernameByAccountID($dbConn, $input);
      setUserRoleByAccountID($dbConn, $input);

      return true;
    }

  } catch(Exception $e){
      echo $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }

  return false;
}


function deleteAccount($dbConn, $input){
  $account_id = $input['account_id'];

  // Try to carry out the database entries
  try{
    $sqlDelete = "DELETE FROM timesheets_person
                        WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlDelete);
    $stmt->execute(array(':account_id' => $account_id));

    // If the query worked display message to user
    if ($stmt){
      return true;
    }

  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }

  return false;
}

function archiveAccount($dbConn, $input){
    $account_id = $input['account_id'];

    // Try to carry out the database entries
    try{
        $sqlUpdate = "UPDATE timesheets_person
                         SET archive = 1
                       WHERE person_id = :account_id";

        $stmt = $dbConn->prepare($sqlUpdate);
        $stmt->execute(array(':account_id' => $account_id));

        // If the query worked display message to user
        if ($stmt){
            return true;
        }

    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    return false;
}


function restoreAccount($dbConn, $input){
    $account_id = $input['account_id'];

    // Try to carry out the database entries
    try{
        $sqlUpdate = "UPDATE timesheets_person
                         SET archive = 0
                       WHERE person_id = :account_id";

        $stmt = $dbConn->prepare($sqlUpdate);
        $stmt->execute(array(':account_id' => $account_id));

        // If the query worked display message to user
        if ($stmt){
            return true;
        }

    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    return false;
}


function getDepartmemtByAccountID($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT department_id
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $department_id = $rows->department_id;

    } else{
      $department_id = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $department_id = null;
  }


  return $department_id;
}

function getJobByAccountID($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT job_id
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $job_id = $rows->job_id;

    } else{
      $job_id = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $job_id = null;
  }


  return $job_id;
}

function getTeamByAccountID($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT team_id
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $team_id = $rows->team_id;

    } else{
      $team_id = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $team_id = null;
  }


  return $team_id;
}

function getRoleByAccountID($dbConn, $account_id){
    // Try to carry out the database search
    try{
        
        $sqlQuery = "SELECT timesheets_role.role_id
                       FROM timesheets_role
                       JOIN timesheets_user_role 
                         ON timesheets_user_role.role_id = timesheets_role.role_id
                       JOIN timesheets_user 
                         ON timesheets_user.user_id = timesheets_user_role.user_id
                       JOIN timesheets_person 
                         ON timesheets_person.user_id = timesheets_user.user_id
                      WHERE timesheets_person.person_id = :account_id
                   ORDER BY role_id ASC
                      LIMIT 1";
        
        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':account_id' => $account_id));
        $rows = $stmt->fetchObject();
        
        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $role_id = $rows->role_id;
            
        } else{
            $role_id = null;
        }
        
        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $role_id = null;
    }
    
    
    return $role_id;
}

function getTitle($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT title
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $title = $rows->title;

    } else{
      $title = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $title = null;
  }


  return $title;
}

function getUsername($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT username
                   FROM timesheets_user
                   JOIN timesheets_person 
                     ON timesheets_person.user_id = timesheets_user.user_id
                  WHERE timesheets_person.person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $username = $rows->username;

    } else{
      $username = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $username = null;
  }


  return $username;
}

function getForename($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT forename
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $forename = $rows->forename;

    } else{
      $forename = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $forename = null;
  }


  return $forename;
}

function getSurname($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT surname
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $surname = $rows->surname;

    } else{
      $surname = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $surname = null;
  }


  return $surname;
}

function getPhoneNumber($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT phone_number
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $phone_number = $rows->phone_number;

    } else{
      $phone_number = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $phone_number = null;
  }


  return $phone_number;
}

function getEmail($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT email
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $email = $rows->email;

    } else{
      $email = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $email = null;
  }


  return $email;
}

function getAddressLine1($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT address_line_1
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $address_line_1 = $rows->address_line_1;

    } else{
      $address_line_1 = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $address_line_1 = null;
  }


  return $address_line_1;
}




function getAddressLine2($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT address_line_2
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $address_line_2 = $rows->address_line_2;

    } else{
      $address_line_2 = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $address_line_2 = null;
  }


  return $address_line_2;
}

function getAddressLine3($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT address_line_3
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $address_line_3 = $rows->address_line_3;

    } else{
      $address_line_3 = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $address_line_3 = null;
  }


  return $address_line_3;
}

function getAddressLine4($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT address_line_4
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $address_line_4 = $rows->address_line_4;

    } else{
      $address_line_4 = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $address_line_4 = null;
  }


  return $address_line_4;
}

function getAddressLine5($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT address_line_5
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $address_line_5 = $rows->address_line_5;

    } else{
      $address_line_5 = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $address_line_5 = null;
  }


  return $address_line_5;
}

function getPostcode($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT post_code
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $postcode = $rows->post_code;

    } else{
      $postcode = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $postcode = null;
  }


  return $postcode;
}
function getDateOfBirth($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT date_of_birth
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $date_of_birth = $rows->date_of_birth;

    } else{
      $date_of_birth = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $date_of_birth = null;
  }


  return $date_of_birth;
}

function getContractedHours($dbConn, $account_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT contracted_hours
                   FROM timesheets_person
                  WHERE person_id = :account_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':account_id' => $account_id));
    $rows = $stmt->fetchObject();

    // Check the query returned some results
    if($stmt->rowCount() > 0){
      $contracted_hours = $rows->contracted_hours;

    } else{
      $contracted_hours = null;
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $contracted_hours = null;
  }


  return $contracted_hours;
}

function getUserIDByUsername ($dbConn, $username) {
    // Try to carry out the database search
    try {
        $sqlQuery = "SELECT user_id
                     FROM timesheets_user
                     WHERE username = :username";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':username' => $username));
        $rows = $stmt->fetchObject();

        // Check if the query returned a result
        if ($stmt->rowCount() > 0) {
            $userID = $rows->user_id;
        } else {
            $userID = null;
        }
        return $userID;

// Log the exception
  } catch(Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $userID = null;

    }

    return $userID;
}

function getPersonIDByUserID ($dbConn, $userID) {
    try {
        $sqlQuery = "SELECT person_id
                     FROM timesheets_person
                     WHERE user_id = :userID";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':userID' => $userID));
        $rows = $stmt->fetchObject();

        //Check if the query returned a result
        if ($stmt->rowCount() > 0) {
            $personID = $rows->person_id;
        } else {
            $personID = null;
        }

        // Log the exception
    } catch(Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $personID = null;

    }

    return $personID;
}

function updateUserPassword($dbConn, $loggedIn, $hashPassword){


    $sqlQuery = 'UPDATE timesheets_user SET passwordHash = :temporaryPassword
                                          WHERE username = :username';

    // Prepare the query
    $stmt = $dbConn->prepare($sqlQuery);

    // Execute the query
    $stmt->execute(array(':temporaryPassword' => $hashPassword,
        ':username' => $loggedIn));

    if ($stmt){
        return true;
    } else{
        return false;
    }
}




























