<?php
@session_start();
    if(!empty($_POST['fac_id'])){
        require("db_connect.php");
        $fac_name = $_POST['fac_id'];
        $branch = $_SESSION['branch'];
        if($stmt =  $conn->prepare("SELECT DISTINCT a.`sid`, a.`year`, a.`sem`, b.`from_date`, b.`to_date` 
                                    FROM `ques` a LEFT JOIN activation b ON a.feed_id=b.id 
                                    WHERE a.fid=? AND a.`cr_code`='A';")){
            
            $stmt->bind_param("s", $fac_name);
            if($stmt->execute()){
                $stmt->bind_result($subject, $year, $sem, $from_date, $to_date);
                $res = "";
                while($stmt->fetch()){
                    $fromdate = date('d-m-Y',strtotime($from_date));
                    $res .= "<option value='".$subject."'>".$branch." - ".$year." - ".$sem." - ".$subject." - ".$fromdate."</option>";
                }
                echo $res;
            }
        }
    }
?>