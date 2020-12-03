<?php
require_once 'env/environment.php';
require_once 'functions/functions.php';
require_once 'class/PDODB.php';
include_once 'fpdf/fpdf.php';

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('images/icon512.png',10,5,20);
        $this->SetFont('Arial','B',13);
        // Move to the right
        $this->Cell(50);
        // Title
        $this->Cell(50,10,'Timesheet',1,0,'C');
        // Line break
        $this->Ln(20);
    }
    
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Attempt to make connection to the database
$dbConn = PDODB::getConnection();

$timesheet_id = isset($_GET['timesheet_id']) ? $_GET['timesheet_id'] : '';

if (!empty($timesheet_id)){
    
    $sqlQuery = "SELECT timesheets_timesheet.date,
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
                  WHERE timesheet_id = :timesheet_id";
    
    $stmt = $dbConn->prepare($sqlQuery);
    $stmt->execute(array(':timesheet_id' => $timesheet_id));
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    
    
    
    
    $header = array(
        'Date',
        'Name',
        'Activity',
        'Project',
        'Time in',
        'Time out',
        'Note'
    );
    
    $pdf = new PDF();
    //header
    $pdf->AddPage();
    //foter page
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial','B',12);
    
    $i = 0;
    foreach($rows as $row) {
        $pdf->Cell(0,10, "$header[$i]: $row",0,1);
        $i++;
        
    }
    
    $pdf->Output();
}
?>