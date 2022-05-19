<?php
    @session_start();
    require("db_connect.php");
    
    if(!empty($_POST['subname'])){
        $subname = $_POST['subname'];
        $br_code = $_SESSION['br_code'];
        $sem = $_SESSION['sem'];
        $year = $_SESSION['year'];
        $reg = $_SESSION['reg'];
        if($stmt = $conn->prepare("SELECT `fname` FROM `fac_course` WHERE `regulation`=? AND `br_code`=? AND `year`=? AND `sem`=? AND `subject`=?;")){
            $stmt->bind_param("ddsss", $reg, $br_code, $year, $sem, $subname);
            if($stmt->execute()){
                $stmt->bind_result($facname);
                while($stmt->fetch()){
                    echo $facname;
                }
            }
        }
    }
?>