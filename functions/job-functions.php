<?php
  // get the job title
  function getJob($dbConn, $job_id){
    // Try to carry out the database search
    try{
      $sqlQuery = "SELECT job_title
                     FROM timesheets_job
                    WHERE job_id = :job_id";

      $stmt = $dbConn->prepare($sqlQuery);
      $stmt->execute(array(':job_id' => $job_id));
      $rows = $stmt->fetchObject();

      // Check the query returned some results
      if($stmt->rowCount() > 0){
        $job_title = $rows->job_title;

      } else{
        $job_title = null;
      }

    // Log the exception
    } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
      $job_title = null;
    }

    
    return $job_title;
    
  }


  // Validate post data
  function validateUpdateJobForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of job ids */
    $validJobIDs = getJobIDs($dbConn);

    /* Get the array of department ids */
    $validDepartmentIDs = getDepartmentIDs($dbConn);

    /* Job id validation */
    $input['job_id'] = filter_has_var(INPUT_POST, 'job_id') ? $_POST['job_id']: null;
    $input['job_id'] = trim($input['job_id']);
    $input['job_id'] = filter_var($input['job_id'], FILTER_VALIDATE_INT) ? $input['job_id'] : null;
    $input['job_id'] = in_array($input['job_id'], $validJobIDs) ? $input['job_id']  : null;

    if(empty($input['job_id'])){
        $errors[] = "There is a problem with the Job you are trying to edit.";
    
    }


    /* Department id validation */
    $input['department_id'] = filter_has_var(INPUT_POST, 'department_id') ? $_POST['department_id']: null;
    $input['department_id'] = trim($input['department_id']);
    $input['department_id'] = filter_var($input['department_id'], FILTER_VALIDATE_INT) ? $input['department_id'] : null;
    $input['department_id'] = in_array($input['department_id'], $validDepartmentIDs) ? $input['department_id']  : null;

    if(empty($input['department_id'])){
        $errors[] = "There is a problem with the Department you are trying to set.";
    
    }

    /* Job title validation */
    $input['update_job_title'] = filter_has_var(INPUT_POST, 'update_job_title') ? $_POST['update_job_title']: null;
    $input['update_job_title'] = trim($input['update_job_title']);

    if(empty($input['update_job_title'])){
        $errors[] = "You have not entered a valid value for the job.";
    
    } else if (strlen ($input['update_job_title']) > 50){   
      $errors[] = "You have entered a job that is too long. The character limit is 50.";
      $input['update_job_title'] = "";
    
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}


function validateDeleteJobForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of job ids*/
    $validJobIDs = getJobIDs($dbConn);
    
    /* ID validation */
    $input['job_id'] = filter_has_var(INPUT_POST, 'job_id') ? $_POST['job_id']: null;
    $input['job_id'] = trim($input['job_id']);
    $input['job_id'] = filter_var($input['job_id'], FILTER_VALIDATE_INT) ? $input['job_id'] : null;
    $input['job_id'] = in_array($input['job_id'], $validJobIDs) ? $input['job_id']  : null;

    if(empty($input['job_id'])){
        $errors[] = "There is a problem with the job you are trying to delete.";
    
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}

function getJobIDs($dbConn){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT job_id
                   FROM timesheets_job";

                  

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $job_ids = array();

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        array_push($job_ids, $row['job_id']);

      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }

 
  return $job_ids;
}


function setJob($dbConn, $input){
  $job_id = $input['job_id'];
  $department_id = $input['department_id'];
  $job_title = $input['update_job_title'];
  
  // Try to carry out the database entries
  try{
    $sqlInsert = "UPDATE timesheets_job 
                     SET department_id = :department_id,
                         job_title = :job_title
                   WHERE job_id = :job_id";

    $stmt = $dbConn->prepare($sqlInsert);
    $stmt->execute(array(':department_id' => $department_id, 
                         ':job_title' => $job_title,
                         ':job_id' => $job_id));
               
    // If the query worked display message to user
    if ($stmt){
      return true;
    }
        
  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }
    
  return false;
}


function deleteJob($dbConn, $input){
  $job_id = $input['job_id'];
  
  // Try to carry out the database entries
  try{
    $sqlDelete = "DELETE FROM timesheets_job 
                        WHERE job_id = :job_id";

    $stmt = $dbConn->prepare($sqlDelete);
    $stmt->execute(array(':job_id' => $job_id));
               
    // If the query worked display message to user
    if ($stmt){
      return true;
    }
        
  } catch(Exception $e){
      $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
  }
    
  return false;
}


function getDepartmemtByJobID($dbConn, $job_id){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT department_id
                   FROM timesheets_job
                  WHERE job_id = :job_id";

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':job_id' => $job_id));
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

function getJobDepartments($dbConn)
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

function getJobOptions($dbConn, $job_id = ''){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT job_id,
                        job_title
                   FROM timesheets_job";

                  

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $job_options = '';

    // Check the query returned some results
    if($stmt->rowCount() > 0){

      // Loop through resultsstmt
      foreach($rows as $row){
        if ($job_id == $row['job_id']){
          $selected = "selected";
        
        } else {
          $selected = "";
        }
        
        $job_options .= '<option value="'. $row['job_id'] .'" '.$selected.'>'. $row['job_title'] .'</option>';


      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $job_options = '';

  }

 
  return $job_options;
}

function createJob($dbConn, $input)
{
    $departmentID = $input['departmentID'];
    $job = $input['job'];

    // Try insert into database
    try {
        $sql = "INSERT INTO timesheets_job (department_id, job_title)
                VALUES('$departmentID','$job')";
        // Prepare SQL statement
        $createJobStmt = $dbConn->prepare($sql);
        // Execute statement
        $createJobStmt->execute();

        // Return true if query worked
        if ($createJobStmt) {
            return true;
        }

    } catch (Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
        echo($retval);
    }

    // Return false if query failed
    return false;
}