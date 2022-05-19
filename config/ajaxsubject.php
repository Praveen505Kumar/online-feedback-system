<?php
    @session_start();
    require("db_connect.php");
    
    if(!empty($_POST['reg']) && !empty($_POST['year']) && !empty($_POST['sem'])){
        
        $res="<option>--Select--</option>";
        $reg = $_POST['reg'];
        $year = $_POST['year'];
        $sem = $_POST['sem'];
        $branch = $_SESSION['branch'];
        $br_code = $_SESSION['br_code'];
        $subjects = array();
        $i=0;
        if($stmt = $conn->prepare("SELECT `subject` FROM `fac_course` WHERE `regulation`=? AND `branch`=? AND `year`=? AND `sem`=?;")){
            $stmt->bind_param("ssss", $reg, $branch, $year, $sem);
            if($stmt->execute()){
                $stmt->bind_result($subject);
                while($stmt->fetch()){
                    $subjects[$i++] = $subject;
                }
            }
            $stmt->close();
        }

        if($stmt = $conn->prepare("SELECT `sub` FROM `subjects_2` WHERE `regulation`=? AND `branch`=? AND `year`=? AND `sem`=?;")){
            $stmt->bind_param("ssss", $reg, $branch, $year, $sem);
            if($stmt->execute()){
                $stmt->bind_result($sub);
                while($stmt->fetch()){
                    if(!in_array($sub, $subjects)){
                        $res.= "<option value='".$sub."'>".$sub."</option>";
                    }
                }
            }
        }

        if($stmt =  $conn->prepare("SELECT DISTINCT `subject` FROM `partial_subjects` WHERE `regulation`=? AND `year`=? AND `sem`=? AND `br_code`=?;")){
            $stmt->bind_param("sssd", $reg, $year, $sem, $br_code);
            if($stmt->execute()){
                $stmt->bind_result($sub);
                while($stmt->fetch()){
                    if(!in_array($sub, $subjects)){
                        $res.= "<option value='".$sub."'>".$sub."</option>";
                    }
                }
            }
            $stmt->close();
        }
        echo $res;
    }
?>