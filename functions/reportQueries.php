<?php
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';

/**
 * SQL Query of all employee's time sheets totals for activity types.
 *
 * @return string
 */
function getAllEmployeeTime(){
    $query =
        "SELECT CONCAT(timesheets_person.forename, ' ', timesheets_person.surname) 
        AS name, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Normal' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id) 
        AS normal, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Overtime' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id) 
        AS overtime,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Holiday'
        AND timesheets_person.user_id = timesheets_timesheet.user_id) 
        AS holiday,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Absent' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id) 
        AS absent,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Sick' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id) 
        AS sick,
        (CASE
            WHEN (((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id) * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id))) < 0 
                        THEN 0
            ELSE FORMAT(((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime'
                    AND timesheets_person.user_id = timesheets_timesheet.user_id) * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id)),2)
        END) 
        AS under
        FROM timesheets_person 
        JOIN timesheets_timesheet ON timesheets_person.user_id = timesheets_timesheet.user_id
        WHERE timesheets_person.archive = FALSE
        GROUP BY timesheets_person.user_id";

    return $query;
}

/**
 * SQL Query of all employee's time sheets totals for activity types between two Dates.
 *
 * @param $startDate
 * @param $endDate
 * @return string
 */
function getAllEmployeeTimeBetweenTwoDates($startDate, $endDate){
    $startDate = $startDate == "" ? "1990-01-01" : $startDate;
    $endDate   = $endDate == "" ? "3000-01-01" : $endDate;
    $query =
        "SELECT CONCAT(timesheets_person.forename, ' ', timesheets_person.surname) 
        AS name, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Normal' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS normal, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Overtime' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS overtime,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Holiday' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS holiday,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Absent' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS absent,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Sick' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS sick,
        (CASE
            WHEN (((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate') * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate'))) < 0 
                        THEN 0
            ELSE FORMAT(((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate') * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate')),2)
        END) 
        AS under
        FROM timesheets_person 
        JOIN timesheets_timesheet ON timesheets_person.user_id = timesheets_timesheet.user_id
        WHERE timesheets_person.archive = FALSE
        GROUP BY timesheets_person.user_id";

    return $query;
}

/**
 * SQL Query of single employee time sheet totals for activity types.
 *
 * @param $userId
 * @return string
 */
function getEmployeeTime($userId){
    $query =
        "SELECT CONCAT(timesheets_person.forename, ' ', timesheets_person.surname) 
        AS name, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Normal' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id) 
        AS normal, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Overtime' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id) 
        AS overtime,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Holiday'
        AND timesheets_person.user_id = timesheets_timesheet.user_id) 
        AS holiday,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Absent' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id) 
        AS absent,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Sick' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id) 
        AS sick,
        (CASE
            WHEN (((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id) * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id))) < 0 
                        THEN 0
            ELSE FORMAT(((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime'
                    AND timesheets_person.user_id = timesheets_timesheet.user_id) * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id)),2)
        END) 
        AS under
        FROM timesheets_person 
        JOIN timesheets_timesheet ON timesheets_person.user_id = timesheets_timesheet.user_id
        WHERE timesheets_person.archive = FALSE
        AND timesheets_person.user_id = '$userId'
        GROUP BY timesheets_person.user_id";

    return $query;
}

/**
 * SQL Query of single employee time sheet totals for activity types between two dates.
 *
 * @param $userId
 * @param $startDate
 * @param $endDate
 * @return string
 */
function getEmployeeTimeBetweenTwoDates($userId, $startDate, $endDate){
    $query =
        "SELECT CONCAT(timesheets_person.forename, ' ', timesheets_person.surname) 
        AS name, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Normal' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS normal, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Overtime' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS overtime,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Holiday' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS holiday,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Absent' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS absent,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Sick' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS sick,
        (CASE
            WHEN (((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate') * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate'))) < 0 
                        THEN 0
            ELSE FORMAT(((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate') * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate')),2)
        END) 
        AS under
        FROM timesheets_person 
        JOIN timesheets_timesheet ON timesheets_person.user_id = timesheets_timesheet.user_id
        WHERE timesheets_person.archive = FALSE
        AND timesheets_person.user_id = '$userId'
        GROUP BY timesheets_person.user_id";

    return $query;
}

/**
 * @param $departmentId
 * @param $startDate
 * @param $endDate
 * @return string
 */
function getDepartmentEmployeeTimeBetweenTwoDates($departmentId, $startDate, $endDate){

    $startDate = $startDate == "" ? "1990-01-01" : $startDate;
    $endDate   = $endDate == "" ? "3000-01-01" : $endDate;

    $query =
        "SELECT CONCAT(timesheets_person.forename, ' ', timesheets_person.surname) 
        AS name, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Normal' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS normal, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Overtime' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS overtime,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Holiday' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS holiday,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Absent' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS absent,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
        WHERE timesheets_activity.activity_type = 'Sick' 
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS sick,
        (CASE
            WHEN (((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate') * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate'))) < 0 
                        THEN 0
            ELSE FORMAT(((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate') * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id 
                    WHERE NOT timesheets_activity.activity_type = 'Overtime' 
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate')),2)
        END) 
        AS under
        FROM timesheets_person 
        JOIN timesheets_timesheet ON timesheets_person.user_id = timesheets_timesheet.user_id
        WHERE timesheets_person.archive = FALSE
        AND timesheets_person.department_id = '$departmentId'
        GROUP BY timesheets_person.user_id";

    return $query;
}

/**
 * @param $projectId
 * @param $startDate
 * @param $endDate
 * @return string
 */
function getProjectEmployeeTimeBetweenTwoDates($projectId, $startDate, $endDate){
    $startDate = $startDate == "" ? "1990-01-01" : $startDate;
    $endDate   = $endDate == "" ? "3000-01-01" : $endDate;
    $query =
        "SELECT CONCAT(timesheets_person.forename, ' ', timesheets_person.surname) 
        AS name, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
        WHERE timesheets_activity.activity_type = 'Normal'
        AND timesheets_timesheet.project_id = '$projectId'
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS normal, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
        WHERE timesheets_activity.activity_type = 'Overtime'
        AND timesheets_timesheet.project_id = '$projectId'
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS overtime,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
        WHERE timesheets_activity.activity_type = 'Holiday'
        AND timesheets_timesheet.project_id = '$projectId'
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS holiday,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
        WHERE timesheets_activity.activity_type = 'Absent'
        AND timesheets_timesheet.project_id = '$projectId'
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS absent,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
        WHERE timesheets_activity.activity_type = 'Sick'
        AND timesheets_timesheet.project_id = '$projectId'
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS sick,
        (CASE
            WHEN (((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
                    WHERE NOT timesheets_activity.activity_type = 'Overtime'
                    AND timesheets_timesheet.project_id = '$projectId'
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate') * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
                    WHERE NOT timesheets_activity.activity_type = 'Overtime'
                    AND timesheets_timesheet.project_id = '$projectId'
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate'))) < 0 
                        THEN 0
            ELSE FORMAT(((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
                    WHERE NOT timesheets_activity.activity_type = 'Overtime'
                    AND timesheets_timesheet.project_id = '$projectId'
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate') * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
                    WHERE NOT timesheets_activity.activity_type = 'Overtime'
                    AND timesheets_timesheet.project_id = '$projectId'
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate')),2)
        END) 
        AS under
        FROM timesheets_person 
        JOIN timesheets_timesheet ON timesheets_person.user_id = timesheets_timesheet.user_id
        JOIN timesheets_department ON timesheets_department.department_id = timesheets_person.department_id
        WHERE timesheets_person.archive = FALSE
        GROUP BY timesheets_person.user_id";

    return $query;
}

/**
 * @param $departmentId
 * @param $projectId
 * @param $startDate
 * @param $endDate
 * @return string
 */
function getDepartmentProjectEmployeeTimeBetweenTwoDates($departmentId, $projectId, $startDate, $endDate){
    $startDate = $startDate == "" ? "1990-01-01" : $startDate;
    $endDate   = $endDate == "" ? "3000-01-01" : $endDate;
    $query =
        "SELECT CONCAT(timesheets_person.forename, ' ', timesheets_person.surname) 
        AS name, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
        WHERE timesheets_activity.activity_type = 'Normal'
        AND timesheets_timesheet.project_id = '$projectId'
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS normal, 
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
        WHERE timesheets_activity.activity_type = 'Overtime'
        AND timesheets_timesheet.project_id = '$projectId'
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS overtime,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
        WHERE timesheets_activity.activity_type = 'Holiday'
        AND timesheets_timesheet.project_id = '$projectId'
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS holiday,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
        WHERE timesheets_activity.activity_type = 'Absent'
        AND timesheets_timesheet.project_id = '$projectId'
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS absent,
        (SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
        FROM timesheets_timesheet 
        JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
        WHERE timesheets_activity.activity_type = 'Sick'
        AND timesheets_timesheet.project_id = '$projectId'
        AND timesheets_person.user_id = timesheets_timesheet.user_id
        AND timesheets_timesheet.date Between '$startDate' AND '$endDate') 
        AS sick,
        (CASE
            WHEN (((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
                    WHERE NOT timesheets_activity.activity_type = 'Overtime'
                    AND timesheets_timesheet.project_id = '$projectId'
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate') * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
                    WHERE NOT timesheets_activity.activity_type = 'Overtime'
                    AND timesheets_timesheet.project_id = '$projectId'
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate'))) < 0 
                        THEN 0
            ELSE FORMAT(((SELECT COALESCE(COUNT(DISTINCT(timesheets_timesheet.date))) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
                    WHERE NOT timesheets_activity.activity_type = 'Overtime'
                    AND timesheets_timesheet.project_id = '$projectId'
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate') * 
                    (timesheets_person.contracted_hours / 5)) - 
                    ((SELECT COALESCE(SUM(ROUND(TIME_TO_SEC(TIMEDIFF(timesheets_timesheet.time_out,timesheets_timesheet.time_in))/60/60,2)),0) 
                    FROM timesheets_timesheet 
                    JOIN timesheets_activity ON timesheets_activity.activity_id = timesheets_timesheet.activity_id
                    WHERE NOT timesheets_activity.activity_type = 'Overtime'
                    AND timesheets_timesheet.project_id = '$projectId'
                    AND timesheets_person.user_id = timesheets_timesheet.user_id
                    AND timesheets_timesheet.date Between '$startDate' AND '$endDate')),2)
        END) 
        AS under
        FROM timesheets_person 
        JOIN timesheets_timesheet ON timesheets_person.user_id = timesheets_timesheet.user_id
        JOIN timesheets_department ON timesheets_department.department_id = timesheets_person.department_id
        WHERE timesheets_person.archive = FALSE
        AND timesheets_person.department_id = '$departmentId'
        GROUP BY timesheets_person.user_id";

    return $query;
}
