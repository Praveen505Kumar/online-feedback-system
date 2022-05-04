<?php
@session_start();
    require("db_connect.php");
    
    if(!empty($_POST['reg']) && !empty($_POST['year']) && !empty($_POST['sem'])){
        $reg = $_POST['reg'];
        $year = $_POST['year'];
        $sem = $_POST['sem'];
        $branch = $_SESSION['branch'];
        $res = array();
        if($stmt = $conn->prepare("SELECT `id`, `subject`, `fname` FROM `fac_course` WHERE `regulation`=? AND `branch`=? AND `year`=? AND `sem`=?;")){
            $stmt->bind_param("ssss",$reg, $branch, $year, $sem);
            if($stmt->execute()){
                $stmt->bind_result($id, $subject, $facname);
                $i=0;
                while($stmt->fetch()){
                    $res[$i]['id'] = $id;
                    $res[$i]['subject'] = $subject;
                    $res[$i]['facname'] = $facname;
                    $i++;
                }
            }
        }
        echo "<table class='table table-striped'>";
        for($i=0; $i<sizeof($res) ;$i++){
            echo "<tr>  <td>".$res[$i]['subject']."</td>
                        <td>".$res[$i]['facname']."</td>
                        <td>
                            <form action='fac_course.php' method='post'>
                                <input type='hidden' name='subfacid' value='".$res[$i]['id']."' />
                                <input type='submit' name='delsubfac' class='btn btn-danger' value=' X ' />
                            </form>
                        </td>
                    </tr>";
        }
        echo "</table>";
    }
?>