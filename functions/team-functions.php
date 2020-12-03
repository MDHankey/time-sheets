<?php
  // get the Team title
  function getTeam($dbConn, $team_id){
    // Try to carry out the database search
    try{
      $sqlQuery = "SELECT team_name
                     FROM timesheets_team
                    WHERE team_id = :team_id";

      $stmt = $dbConn->prepare($sqlQuery);
      $stmt->execute(array(':team_id' => $team_id));
      $rows = $stmt->fetchObject();

      // Check the query returned some results
      if($stmt->rowCount() > 0){
        $team_name = $rows->team_name;

      } else{
        $team_name = null;
      }

    // Log the exception
    } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
      $team_name = null;
    }

    
    return $team_name;
    
  }


  // Validate post data
  function validateUpdateTeamForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of team ids */
    $validTeamIDs = getTeamIDs($dbConn);

    /* Get the array of department ids */
    $validDepartmentIDs = getDepartmentIDs($dbConn);

    /* Team id validation */
    $input['team_id'] = filter_has_var(INPUT_POST, 'team_id') ? $_POST['team_id']: null;
    $input['team_id'] = trim($input['team_id']);
    $input['team_id'] = filter_var($input['team_id'], FILTER_VALIDATE_INT) ? $input['team_id'] : null;
    $input['team_id'] = in_array($input['team_id'], $validTeamIDs) ? $input['team_id']  : null;

    if(empty($input['team_id'])){
        $errors[] = "There is a problem with the Team you are trying to edit.";
    
    }


    /* Department id validation */
    $input['department_id'] = filter_has_var(INPUT_POST, 'department_id') ? $_POST['department_id']: null;
    $input['department_id'] = trim($input['department_id']);
    $input['department_id'] = filter_var($input['department_id'], FILTER_VALIDATE_INT) ? $input['department_id'] : null;
    $input['department_id'] = in_array($input['department_id'], $validDepartmentIDs) ? $input['department_id']  : null;

    if(empty($input['department_id'])){
        $errors[] = "There is a problem with the Department you are trying to set.";
    
    }

    /* Team name validation */
    $input['update_team_name'] = filter_has_var(INPUT_POST, 'update_team_name') ? $_POST['update_team_name']: null;
    $input['update_team_name'] = trim($input['update_team_name']);

    if(empty($input['update_team_name'])){
        $errors[] = "You have not entered a valid value for the team.";
    
    } else if (strlen ($input['update_team_name']) > 50){   
      $errors[] = "You have entered a team that is too long. The character limit is 50.";
      $input['update_team_name'] = "";
    
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}


function validateDeleteTeamForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of team ids*/
    $validTeamIDs = getTeamIDs($dbConn);
    
    /* ID validation */
    $input['team_id'] = filter_has_var(INPUT_POST, 'team_id') ? $_POST['team_id']: null;
    $input['team_id'] = trim($input['team_id']);
    $input['team_id'] = filter_var($input['team_id'], FILTER_VALIDATE_INT) ? $input['team_id'] : null;
    $input['team_id'] = in_array($input['team_id'], $validTeamIDs) ? $input['team_id']  : null;

    if(empty($input['team_id'])){
        $errors[] = "There is a problem with the team you are trying to delete.";
    
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}

function getTeamIDs($dbConn){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT team_id
                   FROM timesheets_team";

                  

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $team_ids = array();

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        array_push($team_ids, $row['team_id']);

      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }

 
  return $team_ids;
}


function setTeam($dbConn, $input){
  $team_id = $input['team_id'];
  $department_id = $input['department_id'];
  $team_name = $input['update_team_name'];
  
  // Try to carry out the database entries
  try{
    $sqlInsert = "UPDATE timesheets_team
                     SET department_id = :department_id,
                         team_name = :team_name
                   WHERE team_id = :team_id";

    $stmt = $dbConn->prepare($sqlInsert);
    $stmt->execute(array(':department_id' => $department_id, 
                         ':team_name' => $team_name,
                         ':team_id' => $team_id));
               
    // If the query worked display message to user
    if ($stmt){
      return true;
    }
        
  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }
    
  return false;
}


function deleteTeam($dbConn, $input){
  $team_id = $input['team_id'];
  
  // Try to carry out the database entries
  try{
    $sqlDelete = "DELETE FROM timesheets_team 
                        WHERE team_id = :team_id";

    $stmt = $dbConn->prepare($sqlDelete);
    $stmt->execute(array(':team_id' => $team_id));
               
    // If the query worked display message to user
    if ($stmt){
      return true;
    }
        
  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }
    
  return false;
}


function getDepartmemtByTeamID($dbConn, $team_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT department_id
                   FROM timesheets_team
                  WHERE team_id = :team_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':team_id' => $team_id));
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


function getTeamDepartments($dbConn)
{

    try {


        // Retrieve department ID's and names from database
        $departmentsSql = "SELECT department_id, department_name
                        FROM timesheets_department
                        ORDER BY department_name";

        // Execute the statement
        $departmentsStmt = $dbConn->prepare($departmentsSql);
        $departmentsStmt->execute();
        $departmentResults = $departmentsStmt->fetchAll();

        return $departmentResults;

    } catch (Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    return null;
}

function getTeamOptions($dbConn, $team_id = ''){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT team_id,
                        team_name
                   FROM timesheets_team";

                  

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $team_options = '';

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        if ($team_id == $row['team_id']){
          $selected = "selected";
        
        } else {
          $selected = "";
        }
        
        $team_options .= '<option value="'. $row['team_id'] .'" '.$selected.'>'. $row['team_name'] .'</option>';


      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $team_options = '';

  }

 
  return $team_options;
}

function createTeam($dbConn, $input)
{
    $departmentID = $input['departmentID'];
    $team = $input['team'];

    // Try insert into database
    try {
        $sql = "INSERT INTO timesheets_team (department_id, team_name)
                VALUES('$departmentID','$team')";
        // Prepare SQL statement
        $createTeamStmt = $dbConn->prepare($sql);
        // Execute statement
        $createTeamStmt->execute();

        // Return true if query worked
        if ($createTeamStmt) {
            return true;
        }

    } catch (Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
        echo($retval);
    }

    // Return false if query failed
    return false;
}