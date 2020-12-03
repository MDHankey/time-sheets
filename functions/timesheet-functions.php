<?php
/**
 * Created by IntelliJ IDEA.
 * User: elliottm
 * Date: 17/04/2019
 * Time: 07:40
 */
// get the timesheet type

function getTimesheet($dbConn, $timesheet_id){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT timesheet_id,
                            CONCAT(timesheets_person.forename, ' ', timesheets_person.surname, 's record for: ', time_in, ' - ',  time_out) AS time_record,
                            activity_id,
                            project_id,
                            time_in,
                            time_out,
                            note
                    FROM timesheets_timesheet
                    JOIN timesheets_person ON timesheets_person.user_id = timesheets_timesheet.user_id 
                    WHERE timesheet_id = :timesheet_id";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':timesheet_id' => $timesheet_id));
        $rows = $stmt->fetchObject();

        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $time_record = $rows->time_record;

        } else{
            $time_record = null;
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $time_record = null;
    }


    return $time_record;

}

function getTimesheetActivity($dbConn, $timesheet_id){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT activity_id
                   FROM  timesheets_timesheet
                   WHERE timesheets_timesheet.timesheet_id = :timesheet_id";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':timesheet_id' => $timesheet_id));
        $rows = $stmt->fetchObject();

        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $activity = $rows->activity_id;

        } else{
            $activity = null;
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $activity = null;
    }

    return $activity;
}

function getTimesheetProject($dbConn, $timesheet_id){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT project_id
                   FROM  timesheets_timesheet
                   WHERE timesheets_timesheet.timesheet_id = :timesheet_id";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':timesheet_id' => $timesheet_id));
        $rows = $stmt->fetchObject();

        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $project = $rows->project_id;

        } else{
            $project = null;
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $project = null;
    }

    return $project;
}

function getTimesheetDate($dbConn, $timesheet_id){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT timesheets_timesheet.date
                   FROM  timesheets_timesheet
                   WHERE timesheets_timesheet.timesheet_id = :timesheet_id";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':timesheet_id' => $timesheet_id));
        $rows = $stmt->fetchObject();

        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $date = $rows->date;

        } else{
            $date = null;
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $date = null;
    }

    return $date;
}

function getTimeIn($dbConn, $timesheet_id){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT time_in
                   FROM  timesheets_timesheet
                   WHERE timesheets_timesheet.timesheet_id = :timesheet_id";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':timesheet_id' => $timesheet_id));
        $rows = $stmt->fetchObject();

        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $time_in = $rows->time_in;

        } else{
            $time_in = null;
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $time_in = null;
    }

    return $time_in;
}

function getTimeOut($dbConn, $timesheet_id){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT time_out
                   FROM  timesheets_timesheet
                   WHERE timesheets_timesheet.timesheet_id = :timesheet_id";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':timesheet_id' => $timesheet_id));
        $rows = $stmt->fetchObject();

        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $time_out = $rows->time_out;

        } else{
            $time_out = null;
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $time_out = null;
    }

    return $time_out;
}

function getNote($dbConn, $timesheet_id){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT note
                   FROM  timesheets_timesheet
                   WHERE timesheets_timesheet.timesheet_id = :timesheet_id";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':timesheet_id' => $timesheet_id));
        $rows = $stmt->fetchObject();

        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $note = $rows->note;

        } else{
            $note = null;
        }

        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $note = null;
    }

    return $note;
}

// Validate post data
function validateUpdatetimesheetForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of timesheet ids*/
    $validtimesheetIDs = getTimesheetIDs($dbConn);

    /* ID validation */
    $input['timesheet_id'] = filter_has_var(INPUT_POST, 'timesheet_id') ? $_POST['timesheet_id']: null;
    $input['timesheet_id'] = trim($input['timesheet_id']);
    $input['timesheet_id'] = filter_var($input['timesheet_id'], FILTER_VALIDATE_INT) ? $input['timesheet_id'] : null;
    $input['timesheet_id'] = in_array($input['timesheet_id'], $validtimesheetIDs) ? $input['timesheet_id']  : null;

    if(empty($input['timesheet_id'])){
        $errors[] = "There is a problem with the timesheet you are trying to edit.";

    }

    /* timesheet validation */
    $input['update_activity_id'] = filter_has_var(INPUT_POST, 'update_activity_id') ? $_POST['update_activity_id']: null;
    $input['update_activity_id'] = trim($input['update_activity_id']);

    $input['update_project_id'] = filter_has_var(INPUT_POST, 'update_project_id') ? $_POST['update_project_id']: null;
    $input['update_project_id'] = trim($input['update_project_id']);

    $input['update_time_in'] = filter_has_var(INPUT_POST, 'update_time_in') ? $_POST['update_time_in']: null;
    $input['update_time_in'] = trim($input['update_time_in']);

    $input['update_time_out'] = filter_has_var(INPUT_POST, 'update_time_out') ? $_POST['update_time_out']: null;
    $input['update_time_out'] = trim($input['update_time_out']);

    $input['update_date'] = filter_has_var(INPUT_POST, 'update_date') ? $_POST['update_date']: null;
    $input['update_date'] = trim($input['update_date']);

    $input['update_note'] = filter_has_var(INPUT_POST, 'update_note') ? $_POST['update_note']: null;
    $input['update_note'] = trim($input['update_note']);

    $hourIn = 0;
    $minuteIn = 0;
    $hourOut = 0;
    $minuteOut = 0;

    if(empty($input['update_time_in'])){
        $errors[] = "You have not entered a valid start time.";

    }
    if (empty($input['update_time_out'])){
        $errors[] = "You have not entered a valid end time.";
    }

    if (empty($input['update_date'])){
        $errors[] = "You have not entered a valid date.";
    }

    //checks time in and time out are in the 24hr format of HH:MM
    if (!preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/",  $input['update_time_in'])){
        $errors[] = "Time In is not in the valid format - HH:MM";
    }
    else{
        $hourIn =  (int)substr( $input['update_time_in'] , 0 ,2 );
        $minuteIn = (int)substr( $input['update_time_in'] , -2 );
    }

    if (!preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $input['update_time_out'] )){
        $errors[] = "Time Out is not in the valid format - HH:MM";
    }
    else {
        $hourOut = (int)substr( $input['update_time_out'] , 0 ,2 );
        $minuteOut = (int)substr( $input['update_time_out'] , -2 );
    }

    if ($hourIn >= $hourOut && $minuteIn >= $minuteOut){
        $errors[] = "Time In needs to be before Time Out";
    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}


function validateDeleteTimesheetForm($dbConn){
    // Create an array of inputs and errors
    $input = array();
    $errors = array();

    /* Get the array of timesheet ids*/
    $validtimesheetIDs = getTimesheetIDs($dbConn);

    /* ID validation */
    $input['timesheet_id'] = filter_has_var(INPUT_POST, 'timesheet_id') ? $_POST['timesheet_id']: null;
    $input['timesheet_id'] = trim($input['timesheet_id']);
    $input['timesheet_id'] = filter_var($input['timesheet_id'], FILTER_VALIDATE_INT) ? $input['timesheet_id'] : null;
    $input['timesheet_id'] = in_array($input['timesheet_id'], $validtimesheetIDs) ? $input['timesheet_id']  : null;

    if(empty($input['timesheet_id'])){
        $errors[] = "There is a problem with the timesheet you are trying to delete.";

    }

    // Return an array of the input and errors arrays
    return array($input, $errors);
}

function getTimesheetIDs($dbConn){
    // Try to carry out the database search
    try{
        $sqlQuery = "SELECT timesheet_id
                   FROM timesheets_timesheet";

        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $timesheet_ids = array();

        // Check the query returned some results
        if($stmt->rowCount() > 0){

            // Loop through resultsstmt
            foreach($rows as $row){
                array_push($timesheet_ids, $row['timesheet_id']);
            }
        }
        // Log the exception
    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    return $timesheet_ids;
}


function setTimesheet($dbConn, $input){
    $timesheet_id = $input['timesheet_id'];
    $date = $input['update_date'];
    $activity_id = $input['update_activity_id'];
    $project_id = $input['update_project_id'];
    $time_in = $input['update_time_in'];
    $time_out = $input['update_time_out'];
    $note = $input['update_note'];

    // Try to carry out the database entries
    try{
        $sqlInsert = "UPDATE timesheets_timesheet 
                     SET    activity_id = :activity_id,
                            project_id = :project_id,
                            timesheets_timesheet.date = :timesheet_date,
                            time_in = :time_in,
                            time_out = :time_out,
                            note = :note
                   WHERE timesheet_id = :timesheet_id";

        $stmt = $dbConn->prepare($sqlInsert);
        $stmt->execute(array(':activity_id' => $activity_id,
            ':project_id' => $project_id,
            ':timesheet_date' => $date,
            ':time_in' => $time_in,
            ':time_out' => $time_out,
            ':note' => $note,
            ':timesheet_id' => $timesheet_id));

        // If the query worked display message to user
        if ($stmt){
            return true;
        }

    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    return false;
}


function deleteTimesheet($dbConn, $input){
    $timesheet_id = $input['timesheet_id'];

    // Try to carry out the database entries
    try{
        $sqlDelete = "DELETE FROM timesheets_timesheet 
                        WHERE timesheet_id = :timesheet_id";

        $stmt = $dbConn->prepare($sqlDelete);
        $stmt->execute(array(':timesheet_id' => $timesheet_id));

        // If the query worked display message to user
        if ($stmt){
            return true;
        }

    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }

    return false;
}

function createTimesheet($dbConn, $input, $user_id){
    $activity_id = $input['activity_id'];
    $project_id = $input['project_id'];
    $date = $input['date'];
    $time_in = $input['time_in'];
    $time_out = $input['time_out'];
    $note = $input['note'];


    // Try insert into database
    try {
        $sql = "INSERT INTO timesheets_timesheet (activity_id, project_id, timesheets_timesheet.date, time_in, time_out, note, user_id)
              VALUES('$activity_id', '$project_id', '$date', '$time_in', '$time_out', '$note', '$user_id')";
        // Prepare SQL statement
        $createtimesheetStmt = $dbConn->prepare($sql);
        // Execute statement
        $createtimesheetStmt->execute();

        // return true if query worked
        if ($createtimesheetStmt) {
            return true;
        }
    } catch (Exception $e) {
        $retval = "<p>Query failed: " . $e->getMessage() . "</p>\n";
    }
    // Return false if query failed
    return false;
}

// Function to get all of the details for timesheets 
function getTimesheetTable($dbConn, $loggedIn = null){
    // Try to carry out the database entries
    try{
        $sqlQuery = "SELECT timesheet_id,
                            timesheets_timesheet.date,
                            CONCAT(timesheets_person.forename, ' ', timesheets_person.surname) AS user_name,
                            activity_type,
                            project_name,
                            time_in,
                            time_out,
                            note
                    FROM timesheets_timesheet
                    JOIN timesheets_person ON timesheets_person.user_id = timesheets_timesheet.user_id 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    JOIN timesheets_project ON timesheets_project.project_id = timesheets_timesheet.project_id 
                ORDER BY YEAR(timesheets_timesheet.date) DESC, MONTH(timesheets_timesheet.date) DESC, DAY(timesheets_timesheet.date) DESC,
                        timesheets_person.surname ASC";

        // Prepare the query
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query
        $stmt->execute();

        // Set the mode to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Place all of the records in variable
        $timesheetResults = $stmt->fetchAll();

        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $timesheets =  "<div class='table-responsives'>
                        <table class='table table-dark' >
                          <thead>
                            <tr>
                              <th>Date</th>
                              <th>User</th>
                              <th>Activity</th>
                              <th>Project</th>
                              <th>Time In</th>
                              <th>Time Out</th>
                            </tr>
                          </thead>
                          <tbody>";

            foreach ($timesheetResults as $result){
                // timesheet Id
                $timesheet_id = htmlspecialchars($result['timesheet_id']);
                $date = htmlspecialchars($result['date']);
                $user_name = htmlspecialchars($result['user_name']);
                $activity_type = htmlspecialchars($result['activity_type']);
                $project_name = htmlspecialchars($result['project_name']);
                $time_in = htmlspecialchars($result['time_in']);
                $time_out = htmlspecialchars($result['time_out']);


                $timesheets .= "<tr>
                          <td>$date</td>
                          <td>$user_name</td>
                          <td>$activity_type</td>
                          <td>$project_name</td>
                          <td>$time_in</td>
                          <td>$time_out</td>

                          <td class='actions'>
                            <a href='edit-timesheet.php?timesheet_id=$timesheet_id' title='Edit'><i class='fas fa-pencil-alt action-icon' ></i></a>
                            <a href='delete-timesheet.php?timesheet_id=$timesheet_id' title='Delete'><i class='fas fa-times action-icon'></i></a>
                            <a target='_blank' href='generate-pdf.php?timesheet_id=$timesheet_id' title='Print'><i class='fas fa-print action-icon'></i></a>
                          </td>
                        </tr>";

            }
            // Close the table and div
            $timesheets .= "</tbody>
                    </table>
                  </div>";

        } else{
            $timesheets = null;
        }

    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $timesheets = null;
    }

    return $timesheets;
}

// Function to get user timesheets
function getUserTimesheetTable($dbConn, $loggedIn = null){
    // Try to carry out the database entries
    try{
        $sqlQuery = "SELECT timesheet_id,
                            timesheets_timesheet.date,
                            activity_type,
                            project_name,
                            time_in,
                            time_out,
                            note
                    FROM timesheets_timesheet
                    JOIN timesheets_person ON timesheets_person.user_id = timesheets_timesheet.user_id 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    JOIN timesheets_project ON timesheets_project.project_id = timesheets_timesheet.project_id
                    JOIN timesheets_user ON timesheets_user.user_id = timesheets_timesheet.user_id
                    WHERE timesheets_user.username = :username
                ORDER BY YEAR(timesheets_timesheet.date) DESC, MONTH(timesheets_timesheet.date) DESC, DAY(timesheets_timesheet.date) DESC,
                        timesheets_person.surname ASC";

        // Prepare the query
        $stmt = $dbConn->prepare($sqlQuery);

        // Execute the query
        $stmt->execute(array(':username' => $loggedIn));

        // Set the mode to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        // Place all of the records in variable
        $timesheetResults = $stmt->fetchAll();

        // Check the query returned some results
        if($stmt->rowCount() > 0){
            $timesheets =  "<div class='table-responsives'>
                        <table class='table table-dark' >
                          <thead>
                            <tr>
                              <th>Date</th>
                              <th>Activity</th>
                              <th>Project</th>
                              <th>Time In</th>
                              <th>Time Out</th>
                            </tr>
                          </thead>
                          <tbody>";

            foreach ($timesheetResults as $result){
                // timesheet Id
                $timesheet_id = htmlspecialchars($result['timesheet_id']);
                $date = htmlspecialchars($result['date']);
                $activity_type = htmlspecialchars($result['activity_type']);
                $project_name = htmlspecialchars($result['project_name']);
                $time_in = htmlspecialchars($result['time_in']);
                $time_out = htmlspecialchars($result['time_out']);


                $timesheets .= "<tr>
                          <td>$date</td>
                          <td>$activity_type</td>
                          <td>$project_name</td>
                          <td>$time_in</td>
                          <td>$time_out</td>

                          <td class='actions'>
                            <a href='edit-timesheet.php?timesheet_id=$timesheet_id' title='Edit'><i class='fas fa-pencil-alt action-icon' ></i></a>
                            <a href='delete-timesheet.php?timesheet_id=$timesheet_id' title='Delete'><i class='fas fa-times action-icon'></i></a>
                            <a target='_blank' href='generate-pdf.php?timesheet_id=$timesheet_id' title='Print'><i class='fas fa-print action-icon'></i></a>
                          </td>
                        </tr>";

            }
            // Close the table and div
            $timesheets .= "</tbody>
                    </table>
                  </div>";

        } else{
            $timesheets = null;
        }

    } catch(Exception $e){
        $retval =  "<p>Query failed: " . $e->getMessage() . "</p>\n";
        $timesheets = null;
    }

    return $timesheets;
}