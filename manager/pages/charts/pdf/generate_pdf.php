<?php
    //include connection file
    ini_set('display_errors', 1);
    include "../cfg/db.php";
    include_once('fpdf.php');

    class PDF extends FPDF
    {
        // Page header
        function Header()
        {
            $this->SetFont('Arial','B',13);    
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
    // Create connection
    $conn = mysqli_connect($servername, $username, $password);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }    
    
    mysqli_select_db($conn, $database);       
    //retrieve the last posted data(including query)
    $sel_query = "SELECT * FROM received_data ORDER BY data_id DESC LIMIT 1";
    $result = mysqli_query($conn, $sel_query);    
    
    if($result) {      
        $res_data = mysqli_fetch_assoc($result);
        //print_r($res_data);
		$send_query 	   = $res_data["send_query"];             
    }    
    $ret_query = $send_query;
    $ret_result = mysqli_query($conn, $ret_query);    
    if($ret_result) {
			$result = $ret_result;
			$heading = false;
			$header = array();
			foreach($result as $row) {
				if(!$heading) {
				  // display field/column names as a first row
				  $header = array_keys($row);
				  $heading = true;
				}
			}
			
		}        
$pdf = new PDF();
//header
$pdf->AddPage();
//foter page
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',12);
foreach($header as $ky => $each_head) {
	$pdf->Cell(40,12,$each_head,1);
}
foreach($result as $row) {
	$pdf->Ln();
	foreach($row as $column)
		$pdf->Cell(40,12,$column,1);
}   
$pdf->Output();
?>