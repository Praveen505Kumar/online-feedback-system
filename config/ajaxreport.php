<?php
@session_start();
    if(!empty($_POST['reg']) && !empty($_POST['year']) && !empty($_POST['sem'])){
        
        require("db_connect.php");
        
        $reg = $_POST['reg'];
        $year = $_POST['year'];
        $sem = $_POST['sem'];
        $br_code = $_SESSION['br_code'];
        if($stmt =  $conn->prepare("SELECT from_date, to_date FROM `activation` WHERE regulation=? AND year=? AND sem=? AND branch=?;")){
            
            $stmt->bind_param("ssss", $reg, $year, $sem, $br_code);
            if($stmt->execute()){
                $stmt->bind_result($from_date, $to_date);
                $res = "";
                while($stmt->fetch()){
                    $fromdate = date('d-m-Y', strtotime($from_date));
                    $todate = date('d-m-Y', strtotime($to_date));
                    $temp = $fromdate.'_'.$todate;
                    $res .= "<option value='".$temp."'>".$fromdate." - ".$todate."</option>";
                }
                echo $res;
            }
        }
    }
?>