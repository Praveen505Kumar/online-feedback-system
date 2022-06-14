<?php 
    date_default_timezone_set("Asia/Kolkata");  
    require('fpdf184/fpdf.php');

    // connection
    require("Operations.php");
    $opt = new Operations();

    $facname = $_GET['facname'];
    $subject = $_GET['subject'];

    // get questions and percentages
    $questions = $opt->getQuestionsPer($facname, $subject);

    // get commets
    $comments = $opt->getComments($facname, $subject);

    $pdf = new FPDF();
    $pdf->SetMargins(10, 3.1, 3.1);
    // report
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 8);
    $today = date("d-m-Y, h:iA",time());
    $pdf->Cell(80, 10, $today, 0);
    $pdf->Cell(80, 10, "JNTUACEK Online FeedBack System", 0, 1);
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(80, 10, "Subject : ".$subject, 0); 
    $pdf->Cell(80, 10, "Faculty Name : ".$facname, 0,1);
    $pdf->Cell(80, 10, "Overall Rating : ".round($questions['average']*10, 2).'%', 0);
    $pdf->Cell(80, 10, "Number Of Students Submitted : ".$questions['stdcount'], 0, 1);
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(20,8,"S.No",1); $pdf->Cell(130,8,"Question",1); $pdf->Cell(30,8,"Percentage",1,0,'C'); 
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 14);
    for($i=0; $i<14; $i++){
        if($i<5 || $i==8 || $i==13){
            $pdf->Cell(20, 10,$i+1, 1, 0, 'C'); 
            $pdf->Cell(130, 10, $questions[0][$i], 1); 
            $pdf->Cell(30, 10, round( (($questions[1][$i] / ($questions['stdcount']*10) ) * 100), 2).'%', 1, 0, 'C'); 
            $pdf->Ln();
        }else{
            $y = $pdf->GetY();
            $pdf->Cell(20, 20, $i+1, 1, 0, 'C'); 
            $pdf->MultiCell(130, 10, $questions[0][$i], 1); 
            $x = $pdf->getX();
            $pdf->SetXY($x + 150,$y);
            $pdf->Cell(30, 20, round( (($questions[1][$i] / ($questions['stdcount']*10) ) * 100), 2).'%', 1, 0,'C'); 
            $pdf->Ln();
        }
    }
    // commets
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 8);
    $today = date("m-d-Y, h:iA",time());
    $pdf->Cell(80, 10, $today, 0);
    $pdf->Cell(80, 10, "JNTUACEK Online FeedBack System", 0, 1);
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(20, 10, "COMMENTS", 0, 1, 'L');
    $pdf->SetFont('Arial', '', 14);
    $length = sizeof($comments);
    if($length == 0){
        $pdf->Cell(20, 20, "Comments Not Found..!", 1, 0, 'C'); 
    }else{
        foreach($comments as $comment){
            $pdf->Cell(20, 8, '- '.$comment, 0, 1, 'L');
        }
    }




    $pdf->Output();
?>
