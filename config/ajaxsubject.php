<?php
    @session_start();
    require("db_connect.php");
    
    if(!empty($_POST['reg']) && !empty($_POST['year']) && !empty($_POST['sem'])){
        
        $res="<option>--Select--</option>";
        $reg = $_POST['reg'];
        $year = $_POST['year'];
        $sem = $_POST['sem'];
        $branch = $_SESSION['branch'];
        $subjects = array();
        if($stmt = $conn->prepare("SELECT `subject` FROM `fac_course` WHERE `regulation`=? AND `branch`=? AND `year`=? AND `sem`=?;")){
            $stmt->bind_param("ssss",$reg, $branch, $year, $sem);
            if($stmt->execute()){
                $stmt->bind_result($subject);
                $i=0;
                while($stmt->fetch()){
                    $subjects[$i++] = $subject;
                }
            }
            $stmt->close();
        }
        if($stmt = $conn->prepare("SELECT `sub` FROM `subjects_2` WHERE `regulation`=? AND `branch`=? AND `year`=? AND `sem`=?;")){
            $stmt->bind_param("ssss",$reg, $branch, $year, $sem);
            if($stmt->execute()){
                $stmt->bind_result($sub);
                while($stmt->fetch()){
                    if(!in_array($sub, $subjects)){
                        $res.= "<option value='".$sub."'>".$sub."</option>";
                    }
                }
            }
        }
        echo $res;
    }
?>