<?php
@session_start();
    if(!empty($_POST['fac_id'])){
        require("db_connect.php");
        $fac_name = $_POST['fac_id'];
        $branch = $_SESSION['branch'];
        if($stmt =  $conn->prepare("SELECT DISTINCT a.`sid`, a.`year`, a.`sem`, b.`from_date`, b.`to_date`, a.`feed_id` 
                                    FROM `ques` a LEFT JOIN activation b ON a.feed_id=b.id 
                                    WHERE a.fid=? AND a.`cr_code`='A';")){
            
            $stmt->bind_param("s", $fac_name);
            if($stmt->execute()){
                $stmt->bind_result($subject, $year, $sem, $from_date, $to_date, $feed_id);
                $res = "";
                while($stmt->fetch()){
                    $fromdate = date('d-m-Y',strtotime($from_date));
                    $res .= "<option value='".$subject."-".$feed_id."'>".$branch." - ".$year." - ".$sem." - ".$subject." - ".$fromdate."</option>";
                }
                echo $res;
            }
        }
    }
?>