<?php
    @session_start();
    
    if (!empty($_POST['regulation']) && !empty($_POST['year']) && !empty($_POST['sem']) && !empty($_POST['fromdate']) && !empty($_POST['todate'])) {
        require("config/db_connect.php");

        date_default_timezone_set("Asia/Kolkata");

        $today = date("Y-m-d\TH:i", time());
        $reg = $_POST['regulation'];
        $year = $_POST['year'];
        $sem = $_POST['sem'];
        $br_code = $_SESSION['br_code'];

        if($stmt = $conn->prepare("SELECT `id`, `from_date`,`to_date` FROM `activation` WHERE `regulation`=? AND `branch`=? AND `year`=? And `sem`=?;")){
            $stmt->bind_param("ssss", $reg, $br_code, $year, $sem);
            if($stmt->execute()){
                $stmt->bind_result($id, $fromdate, $todate);
                
                $stmt->fetch();
                if(!empty($fromdate) && !empty($todate) && $fromdate <= $today && $today <= $todate){
                    header('Location:activate_fb.php?msg=feedback_exists'.$id);
                }else{
                    if($_POST['fromdate'] >= $_POST['todate']){
                        header('Location:activate_fb.php?msg=start_end_time_error');
                    }else if($_POST['todate'] <= $today){
                        header('Location:activate_fb.php?msg=end_time_error');
                    }else{
                        // activate feedback
                        $stmt->close();
                        $cr_code = "A";
                        $query1="UPDATE `st_login` SET `feedback_status`='1' WHERE `regulation`=? AND `year`=? AND `sem`=? AND `br_code`=?";
                        if ($stmt = $conn->prepare($query1)) {
                            $stmt->bind_param("ssss", $reg, $year, $sem, $br_code);
                            if($stmt->execute()){			   
                                if($conn->affected_rows){
                                    $query2 = "INSERT INTO `activation` (`regulation`,`cr_code`,`branch`,`year`,`sem`,`from_date`,`to_date`) VALUES(?,?,?,?,?,?,?)";
                                    if ($stmt2 = $conn->prepare($query2)) {
                                        $stmt2->bind_param("sssssss", $reg, $cr_code, $br_code, $year, $sem, $_POST['fromdate'], $_POST['todate']);
                                        if($stmt2->execute()){
                                            if($conn->affected_rows){
                                                header('Location:activate_fb.php?msg=feedback_activated');
                                            }else{
                                                header('Location:activate_fb.php?msg=feedback_not_activated');
                                            }
                                        }
                                    }
                                }else{
                                    header('Location:activate_fb.php?msg=feedback_not_activated');
                                }
                            }
                            
                        }
                        
                    }
                }
            }
        }
        
    }else{
        header('Location:activate_fb.php');
    }
?>