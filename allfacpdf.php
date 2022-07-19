<?php
    @session_start();
    date_default_timezone_set("Asia/Kolkata");  
    if(!empty($_POST['regulation']) && !empty($_POST['year']) && !empty($_POST['sem']) && !empty($_POST['fdtime'])){
        require('fpdf184/fpdf.php');
        
        // connection
        require("Operations.php");
        $opt = new Operations();

        $reg = $_POST['regulation'];
        $year = $_POST['year'];
        $sem = $_POST['sem'];
        $feeds = explode('_', $_POST['fdtime']);
        $feed_id = $feeds[0];
        $feedtime = $feeds[1];
        $br_code = $_SESSION['br_code'];
        $branch = $_SESSION['branch'];
        $cr_code = "A";

        // getting report details
        $reportdetails = $opt->getReportDetails($br_code, $year, $sem, $reg, $cr_code, $feed_id);
        
        $pdf = new FPDF();
        $pdf->SetMargins(10, 3.1, 3.1);
        
        foreach($reportdetails as $record){
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 8);
            $today = date("d-m-Y, h:iA",time());
            $pdf->Cell(80, 10, $today, 0);
            $pdf->SetFont('Arial','',14);
            $pdf->SetXY(0, 20);
            $pdf->SetLeftMargin(15);
            $pdf->Cell(180,10,"JNTUA College of Engineering, Kalikiri",0);
            $pdf->Image('images/jntuacek.png',160,15,25,25,'PNG');
            $pdf->Ln();
            $pdf->Cell(180,10,"Online Feedback Report",0);				
            $pdf->Ln();
            $pdf->Ln();
            $pdf->Cell(50,8,"Name of the faculty : ");              $pdf->Cell(200, 8, $record['facname'], 0);                                                  $pdf->Ln();
            $pdf->Cell(50,8,"Subject Name : ");                     $pdf->Cell(200, 8, $record['subname'], 0);                                                  $pdf->Ln();
            $pdf->Cell(50,8,"Class : ");                            $pdf->Cell(200, 8, "B.Tech (R".$reg.') - '.$branch.' - '.$year.' Yr '.$sem.' Sem ', 0);     $pdf->Ln();
            $pdf->Cell(50,8,"Date(s) of Feedback : ");              $pdf->Cell(200, 8, $feedtime, 0);                                                           $pdf->Ln();
            $pdf->Cell(100,8, 'No. of Students Submitted : '.$record['count']);             $pdf->Ln();
            

            // get questions and percentages
            $questions = $opt->getQuestionsPer($record['facname'], $record['subname'], $feed_id);


            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(20,8,"S.No",1); $pdf->Cell(130,8,"Question",1); $pdf->Cell(30,8,"Percentage",1,0,'C'); 
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 14);
            for($i=0; $i<14; $i++){
                if($i<5 || $i==8 || $i==13){
                    $pdf->Cell(20, 7,$i+1, 1, 0, 'C'); 
                    $pdf->Cell(130, 7, $questions[0][$i], 1); 
                    $pdf->Cell(30, 7, round( (($questions[1][$i] / ($questions['stdcount']*10) ) * 100), 2).'%', 1, 0, 'C'); 
                    $pdf->Ln();
                }else{
                    $y = $pdf->GetY();
                    $pdf->Cell(20, 14, $i+1, 1, 0, 'C'); 
                    $pdf->MultiCell(130, 7, $questions[0][$i], 1); 
                    $x = $pdf->getX();
                    $pdf->SetXY($x + 150,$y);
                    $pdf->Cell(30, 14, round( (($questions[1][$i] / ($questions['stdcount']*10) ) * 100), 2).'%', 1, 0,'C'); 
                    $pdf->Ln();
                }
            }


            $pdf->Cell(180, 8, 'Overall Percentage : '.round($record['avg']*10, 2).' %', 0, 1, 'R');
            $pdf->SetFont('Arial','',10);
            $pdf->cell(10,6,"Rating: Poor-2, Average-4, Good-6, Very Good-8, Excellent-10",0,0,'L');
            $pdf->Ln();
            $pdf->Cell(10,6,"Percentage Calculated (%) = (Sum of Students rating)/ No. of Students Submitted",0,0,'L');
            $pdf->Ln();
            $pdf->Cell(10,6,"Overall Percentage = (Sum of Percentages Calculated)/14 ",0,0,'L');
            $pdf->Ln();
        }
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 8);
        $today = date("d-m-Y, h:iA",time());
        $pdf->Cell(80, 10, $today, 0);
        $pdf->SetFont('Arial','',14);
        $pdf->SetXY(0, 20);
        $pdf->SetLeftMargin(15);
        $pdf->Cell(180,10,"JNTUA College of Engineering, Kalikiri",0);
        $pdf->Image('images/jntuacek.png',160,15,25,25,'PNG');
        $pdf->Ln();
        $pdf->Cell(180,10,"Online Feedback Report",0);				
        $pdf->Ln();
        $pdf->Cell(50,8,"Class : ");                            $pdf->Cell(200, 8, "B.Tech (R".$reg.') - '.$branch.' - '.$year.' Yr '.$sem.' Sem ', 0);     $pdf->Ln();
        $pdf->Cell(50,8,"Date(s) of Feedback : ");              $pdf->Cell(200, 8, $feedtime, 0);                                                           $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        foreach($reportdetails as $record){
            $y = $pdf->getY();
            $pdf->MultiCell(160, 8, "Faculty Name : ".$record['facname']."\nSubject Name : ".$record['subname'], 1);
            $x = $pdf->getX(); 
            $pdf->SetXY($x + 160,$y); 
            $pdf->Cell(20, 16, round($record['avg']*10, 2).' %', 1, 'C');
            $pdf->Ln();
        } 

        $pdf->Output();
    
    }
    else{
        header('Location: index.php');
    }
?>
