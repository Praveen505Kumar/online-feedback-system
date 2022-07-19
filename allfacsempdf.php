<?php 
    @session_start();
    date_default_timezone_set("Asia/Kolkata");
    if(!empty($_POST['sembtn'])){
        //require('fpdf184/fpdf.php');
        require('fpdf184/pdf_mc_table.php');
        
        // connection
        require("Operations.php");
        $opt = new Operations();

        $sem = $_POST['sembtn'];

        // getting report details
        $report = $opt->getSemWiseReport($sem);
        
        
        $pdf = new PDF_MC_Table();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 14);

        $pdf->SetFont('Arial', '', 8);
        $today = date("d-m-Y, h:iA",time());
        $pdf->Cell(80, 10, $today, 0);

        $pdf->SetFont('Arial','',14);
        $pdf->SetXY(0, 20);
        $pdf->SetLeftMargin(5);
        $pdf->Cell(180,10,"JNTUA College of Engineering, Kalikiri",0);
        $pdf->Image('images/jntuacek.png',160,15,25,25,'PNG');
        $pdf->Ln();
        $pdf->Cell(180,10,"Online Feedback Report",0);				
        $pdf->Ln();
        $pdf->Ln();

        //set width for each column (6 columns)
        $pdf->SetWidths(Array(13,40,60,20,15,15,17,20));

        //set alignment
        //$pdf->SetAligns(Array('','R','C','','',''));

        //set line height. This is the height of each lines, not rows.
        $pdf->SetLineHeight(7);

        //add table heading using standard cells
        //set font to bold
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(13,7,"S.No",1,0);
        $pdf->Cell(40,7,"Fac Name",1,0);
        $pdf->Cell(60,7,"Sub Name",1,0);
        $pdf->Cell(20,7,"Branch",1,0);
        $pdf->Cell(15,7,"Reg",1,0);
        $pdf->Cell(15,7,"Year",1,0);
        $pdf->Cell(17,7,"StdCount",1,0);
        $pdf->Cell(20,7,"Per",1,0);

        $pdf->Ln();

        //reset font
        $pdf->SetFont('Arial','',12);
        //loop the data
        
        for($i=0; $i<sizeof($report); $i++){
            $pdf->Row(Array(
                $i+1,
                $report[$i]['facname'],
                $report[$i]['subname'],
                $report[$i]['branch'],
                $report[$i]['reg'],
                $report[$i]['year'],
                $report[$i]['count'],
                round($report[$i]['avg'] * 10, 2).'%'
            ));
        }
        $pdf->Ln();

        $pdf->Output();
    }
?>