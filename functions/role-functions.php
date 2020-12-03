<?php
function getRoleOptions($dbConn, $role_id = ''){
  // Try to carry out the database search
  try{
    $sqlQuery = "SELECT role_id,
                        role_type
                   FROM timesheets_role";

                  

    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $role_options = '';
    
    // Check the query returned some results
    if($stmt->rowCount() > 0){
      // Loop through resultsstmt
      foreach($rows as $row){
        if ($role_id == $row['role_id']){
          $selected = "selected";
        
        } else {
          $selected = "";
        }
        
        $role_options .= '<option value="'. $row['role_id'] .'" '.$selected.'>'. $row['role_type'] .'</option>';


      }
    }

  // Log the exception
  } catch(Exception $e){
    $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    $role_options = '';

  }

 
  return $role_options;
}

function getRoleIDs($dbConn){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT role_id
                       FROM timesheets_role";
        
        
        
        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        $role_ids = array();
        
        // Check the query returned some results
        if($stmt->rowCount() > 0){
            
            // Loop through resultsstmt
            foreach($rows as $row){
                array_push($role_ids, $row['role_id']);
                
            }
        }
        
        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }
    
    
    return $role_ids;
}



function insertAdminRole($dbConn, $account_id){
    
    // Try insert into database
    try {
        
        
        $sql1 = "INSERT INTO timesheets_user_role (user_id, role_id)
                    VALUES('$account_id', 1)";
        // Prepate SQL statement
        $stmt1 = $dbConn->prepare($sql1);
        // Excecute statement
        $stmt1->execute();
        
        // return true if query worked
        if ($stmt1) {
            $sql2 = "INSERT INTO timesheets_user_role (user_id, role_id)
                    VALUES('$account_id', 2)";
            // Prepate SQL statement
            $stmt2 = $dbConn->prepare($sql2);
            // Excecute statement
            $stmt2->execute();
            
            
            return true;
        }
        
        
        
    } catch (Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }
    // Return false if query failed
    return false;
}


function insertUserRole($dbConn, $account_id){
    // Try insert into database
    try {
        $sql2 = "INSERT INTO timesheets_user_role (user_id, role_id)
                VALUES('$account_id', 2)";
        // Prepate SQL statement
        $stmt2 = $dbConn->prepare($sql2);
        // Excecute statement
        $stmt2->execute();
        
        
        return true;
        
        
        
        
    } catch (Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }
    // Return false if query failed
    return false;
}