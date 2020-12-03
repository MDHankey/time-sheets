<?php
  // get the department type 
  function getDepartment($dbConn, $department_id){
    // Try to carry out the database search
    try{
      $sqlQuery = "SELECT department_name
                     FROM timesheets_department
                    WHERE department_id = :department_id";

      $stmt = $dbConn->prepare($sqlQuery);
      $stmt->execute(array(':department_id' => $department_id));
      $rows = $stmt->fetchObject();

      // Check the query returned some results
      if($stmt->rowCount() > 0){
        $department_name = $rows->department_name;

      } else{
        $department_name = null;
      }

    // Log the exception
    } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
      $department_name = null;
    }

    
    return $department_name;
    
  }


  // Validate post data
  function validateUpdateDepartmentForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of department ids*/
    $validDepartmentIDs = getDepartmentIDs($dbConn);
    
    /* ID validation */
    $input['department_id'] = filter_has_var(INPUT_POST, 'department_id') ? $_POST['department_id']: null;
    $input['department_id'] = trim($input['department_id']);
    $input['department_id'] = filter_var($input['department_id'], FILTER_VALIDATE_INT) ? $input['department_id'] : null;
    $input['department_id'] = in_array($input['department_id'], $validDepartmentIDs) ? $input['department_id']  : null;

    if(empty($input['department_id'])){
        $errors[] = "There is a problem with the Department you are trying to edit.";
    
    }

    /* Department validation */
    $input['update_department_name'] = filter_has_var(INPUT_POST, 'update_department_name') ? $_POST['update_department_name']: null;
    $input['update_department_name'] = trim($input['update_department_name']);

    if(empty($input['update_department_name'])){
        $errors[] = "You have not entered a valid value for the department.";
    
    } else if (strlen ($input['update_department_name']) > 50){   
      $errors[] = "You have entered a department that is too long. The character limit is 50.";
      $input['update_department_name'] = "";
    
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}


function validateDeleteDepartmentForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of department ids*/
    $validDepartmentIDs = getDepartmentIDs($dbConn);
    
    /* ID validation */
    $input['department_id'] = filter_has_var(INPUT_POST, 'department_id') ? $_POST['department_id']: null;
    $input['department_id'] = trim($input['department_id']);
    $input['department_id'] = filter_var($input['department_id'], FILTER_VALIDATE_INT) ? $input['department_id'] : null;
    $input['department_id'] = in_array($input['department_id'], $validDepartmentIDs) ? $input['department_id']  : null;

    if(empty($input['department_id'])){
        $errors[] = "There is a problem with the Department you are trying to delete.";
    
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}

function getDepartmentIDs($dbConn){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT department_id
                   FROM timesheets_department";

                  

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $department_ids = array();

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        array_push($department_ids, $row['department_id']);

      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }

 
  return $department_ids;
}


function setDepartment($dbConn, $input){
  $department_id = $input['department_id'];
  $department_name = $input['update_department_name'];
  
  // Try to carry out the database entries
  try{
    $sqlInsert = "UPDATE timesheets_department 
                     SET department_name = :department_name
                   WHERE department_id = :department_id";

    $stmt = $dbConn->prepare($sqlInsert);
    $stmt->execute(array(':department_name' => $department_name, 
                         ':department_id' => $department_id));
               
    // If the query worked display message to user
    if ($stmt){
      return true;
    }
        
  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }
    
  return false;
}


function deleteDepartment($dbConn, $input){
  $department_id = $input['department_id'];
  
  // Try to carry out the database entries
  try{
    $sqlDelete = "DELETE FROM timesheets_department 
                        WHERE department_id = :department_id";

    $stmt = $dbConn->prepare($sqlDelete);
    $stmt->execute(array(':department_id' => $department_id));
               
    // If the query worked display message to user
    if ($stmt){
      return true;
    }
        
  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }
    
  return false;
}

function getDepartmemtOptions($dbConn, $department_id = ''){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT department_id,
                        department_name
                   FROM timesheets_department";

                  

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $department_options = '';

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        if ($department_id == $row['department_id']){
          $selected = "selected";
        
        } else {
          $selected = "";
        }
        
        $department_options .= '<option value="'. $row['department_id'] .'" '.$selected.'>'. $row['department_name'] .'</option>';


      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $department_options = '';

  }

 
  return $department_options;
}

function createDepartment($dbConn, $input){
    $department = $input['department'];

    // Try insert into database
    try {
        $sql = "INSERT INTO timesheets_department (department_name)
              VALUES('$department')";
        // Prepare SQL statement
        $createDepartmentStmt = $dbConn->prepare($sql);
        // Execute statement
        $createDepartmentStmt->execute();

        // return true if query worked
        if ($createDepartmentStmt) {
            return true;
        }
    } catch (Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }
    // Return false if query failed
    return false;
}