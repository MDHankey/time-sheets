<?php
  // get the project type 
  function getProject($dbConn, $project_id){
    // Try to carry out the database search
    try{
      $sqlQuery = "SELECT project_name
                     FROM timesheets_project
                    WHERE project_id = :project_id";

      $stmt = $dbConn->prepare($sqlQuery);
      $stmt->execute(array(':project_id' => $project_id));
      $rows = $stmt->fetchObject();

      // Check the query returned some results
      if($stmt->rowCount() > 0){
        $project_name = $rows->project_name;

      } else{
        $project_name = null;
      }

    // Log the exception
    } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
      $project_name = null;
    }

    
    return $project_name;
    
  }


  // Validate post data
  function validateUpdateProjectForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of project ids*/
    $validProjectIDs = getProjectIDs($dbConn);
    
    /* ID validation */
    $input['project_id'] = filter_has_var(INPUT_POST, 'project_id') ? $_POST['project_id']: null;
    $input['project_id'] = trim($input['project_id']);
    $input['project_id'] = filter_var($input['project_id'], FILTER_VALIDATE_INT) ? $input['project_id'] : null;
    $input['project_id'] = in_array($input['project_id'], $validProjectIDs) ? $input['project_id']  : null;

    if(empty($input['project_id'])){
        $errors[] = "There is a problem with the Project you are trying to edit.";
    
    }

    /* Project validation */
    $input['update_project_name'] = filter_has_var(INPUT_POST, 'update_project_name') ? $_POST['update_project_name']: null;
    $input['update_project_name'] = trim($input['update_project_name']);

    if(empty($input['update_project_name'])){
        $errors[] = "You have not entered a valid value for the project.";
    
    } else if (strlen ($input['update_project_name']) > 50){   
      $errors[] = "You have entered a project that is too long. The character limit is 50.";
      $input['update_project_name'] = "";
    
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}


function validateDeleteProjectForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of project ids*/
    $validProjectIDs = getProjectIDs($dbConn);
    
    /* ID validation */
    $input['project_id'] = filter_has_var(INPUT_POST, 'project_id') ? $_POST['project_id']: null;
    $input['project_id'] = trim($input['project_id']);
    $input['project_id'] = filter_var($input['project_id'], FILTER_VALIDATE_INT) ? $input['project_id'] : null;
    $input['project_id'] = in_array($input['project_id'], $validProjectIDs) ? $input['project_id']  : null;

    if(empty($input['project_id'])){
        $errors[] = "There is a problem with the Project you are trying to delete.";
    
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}

function getProjectIDs($dbConn){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT project_id
                   FROM timesheets_project";

                  

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $project_ids = array();

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        array_push($project_ids, $row['project_id']);

      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }

 
  return $project_ids;
}


function setProject($dbConn, $input){
  $project_id = $input['project_id'];
  $project_name = $input['update_project_name'];
  
  // Try to carry out the database entries
  try{
    $sqlInsert = "UPDATE timesheets_project 
                     SET project_name = :project_name
                   WHERE project_id = :project_id";

    $stmt = $dbConn->prepare($sqlInsert);
    $stmt->execute(array(':project_name' => $project_name, 
                         ':project_id' => $project_id));
               
    // If the query worked display message to user
    if ($stmt){
      return true;
    }
        
  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }
    
  return false;
}


function deleteProject($dbConn, $input){
  $project_id = $input['project_id'];
  
  // Try to carry out the database entries
  try{
    $sqlDelete = "DELETE FROM timesheets_project 
                        WHERE project_id = :project_id";

    $stmt = $dbConn->prepare($sqlDelete);
    $stmt->execute(array(':project_id' => $project_id));
               
    // If the query worked display message to user
    if ($stmt){
      return true;
    }
        
  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }
    
  return false;
}

function getProjectOptions($dbConn, $project_id = ''){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT project_id,
                        project_name
                   FROM timesheets_project";

                  

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $project_options = '';

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        if ($project_id == $row['project_id']){
          $selected = "selected";
        
        } else {
          $selected = "";
        }
        
        $project_options .= '<option value="'. $row['project_id'] .'" '.$selected.'>'. $row['project_name'] .'</option>';


      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $project_options = '';

  }

 
  return $project_options;
}

function createProject($dbConn, $input)
{

    $project = $input['project'];

    // Try insert into database
    try {
        $sql = "INSERT INTO timesheets_project (project_name)
                 VALUES('$project')";
        // Prepare SQL statement
        $createProjectStmt = $dbConn->prepare($sql);
        // Execute statement
        $createProjectStmt->execute();

        // Return true if query worked
        if ($createProjectStmt) {
            return true;
        }
    } catch (Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }
    // Return false if query failed
    return false;

}