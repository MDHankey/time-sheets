<?php
  // get the actiity 
  function getActivity($dbConn, $activity_id){
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
    
  }


  // Validate post data
  function validateUpdateActivityForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of activity ids*/
    $validActivityIDs = getActivityIDs($dbConn);
    
    /* ID validation */
    $input['activity_id'] = filter_has_var(INPUT_POST, 'activity_id') ? $_POST['activity_id']: null;
    $input['activity_id'] = trim($input['activity_id']);
    $input['activity_id'] = filter_var($input['activity_id'], FILTER_VALIDATE_INT) ? $input['activity_id'] : null;
    $input['activity_id'] = in_array($input['activity_id'], $validActivityIDs) ? $input['activity_id']  : null;

    if(empty($input['activity_id'])){
        $errors[] = "There is a problem with the Activity you are trying to edit.";
    
    }

    /* Activity validation */
    $input['update_activity_type'] = filter_has_var(INPUT_POST, 'update_activity_type') ? $_POST['update_activity_type']: null;
    $input['update_activity_type'] = trim($input['update_activity_type']);

    if(empty($input['update_activity_type'])){
        $errors[] = "You have not entered a valid value for the activity.";
    
    } else if (strlen ($input['update_activity_type']) > 50){   
      $errors[] = "You have entered an activity that is too long. The character limit is 50.";
      $input['update_activity_type'] = "";
    
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}


function validateDeleteActivityForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of activity ids*/
    $validActivityIDs = getActivityIDs($dbConn);
    
    /* ID validation */
    $input['activity_id'] = filter_has_var(INPUT_POST, 'activity_id') ? $_POST['activity_id']: null;
    $input['activity_id'] = trim($input['activity_id']);
    $input['activity_id'] = filter_var($input['activity_id'], FILTER_VALIDATE_INT) ? $input['activity_id'] : null;
    $input['activity_id'] = in_array($input['activity_id'], $validActivityIDs) ? $input['activity_id']  : null;

    if(empty($input['activity_id'])){
        $errors[] = "There is a problem with the Activity you are trying to delete.";
    
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}

function getActivityIDs($dbConn){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT activity_id
                   FROM timesheets_activity";

                  

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $activity_ids = array();

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        array_push($activity_ids, $row['activity_id']);

      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }

 
  return $activity_ids;
}


function setActivity($dbConn, $input){
  $activity_id = $input['activity_id'];
  $activity_type = $input['update_activity_type'];
  
  // Try to carry out the database entries
  try{
    $sqlInsert = "UPDATE timesheets_activity 
                     SET activity_type = :activity_type
                   WHERE activity_id = :activity_id";

    $stmt = $dbConn->prepare($sqlInsert);
    $stmt->execute(array(':activity_type' => $activity_type, 
                         ':activity_id' => $activity_id));
               
    // If the query worked display message to user
    if ($stmt){
      return true;
    }
        
  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }
    
  return false;
}


function deleteActivity($dbConn, $input){
  $activity_id = $input['activity_id'];
  
  // Try to carry out the database entries
  try{
    $sqlDelete = "DELETE FROM timesheets_activity 
                        WHERE activity_id = :activity_id";

    $stmt = $dbConn->prepare($sqlDelete);
    $stmt->execute(array(':activity_id' => $activity_id));
               
    // If the query worked display message to user
    if ($stmt){
      return true;
    }
        
  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }
    
  return false;
}


function createActivity($dbConn, $input){
    $activity = $input['activity'];

    // Try insert into database
    try {


        $sql = "INSERT INTO timesheets_activity (activity_type)
              VALUES('$activity')";
        // Prepate SQL statement
        $createActivityStmt = $dbConn->prepare($sql);
        // Excecute statement
        $createActivityStmt->execute();

        // return true if query worked
        if ($createActivityStmt) {
            return true;
        }
    } catch (Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }
    // Return false if query failed
    return false;
}

function getActivityOptions($dbConn, $activity_id = ''){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT activity_id,
                        activity_type
                   FROM timesheets_activity";



        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $activity_options = '';

        // Check the query returned some results
        if($stmt->rowCount() > 0){

            // Loop through resultsstmt
            foreach($rows as $row){
                if ($activity_id == $row['activity_id']){
                    $selected = "selected";

                } else {
                    $selected = "";
                }

                $activity_options .= '<option value="'. $row['activity_id'] .'" '.$selected.'>'. $row['activity_type'] .'</option>';

            }
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $activity_options = '';

    }

    return $activity_options;
}