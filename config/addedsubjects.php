<?php
    @session_start();
    require("db_connect.php");
    
    if(!empty($_POST['reg'])){
        $reg = $_POST['reg'];
        $br_code = $_SESSION['br_code'];
        $year = $_POST["year"];
        $sem = $_POST["sem"];
        $subjects = array();
        $i = 0;

        if($stmt =  $conn->prepare("SELECT `sub` FROM `subjects_2` WHERE `regulation`=? AND `year`=? AND `sem`=? AND `br_code`=?;")){
            $stmt->bind_param("ssss", $reg, $year, $sem, $br_code);
            if($stmt->execute()){
                $stmt->bind_result($subject);
                while($stmt->fetch()){
                    $subjects[$i++] = $subject;   
                }
            }
            $stmt->close();
        }

        if($stmt =  $conn->prepare("SELECT DISTINCT`subject` FROM `partial_subjects` WHERE `regulation`=? AND `year`=? AND `sem`=? AND `br_code`=?;")){
            $stmt->bind_param("sssd", $reg, $year, $sem, $br_code);
            if($stmt->execute()){
                $stmt->bind_result($subject);
                while($stmt->fetch()){
                    $subjects[$i++] = $subject;   
                }
            }
            $stmt->close();
        }

?>
        <table class="table table-striped">
            <tbody>
                <?php for($i=0;$i<sizeof($subjects);$i++) { ?>
                    <tr>
                        <th scope="row"><?php echo $i+1; ?></th>
                        <td><?php echo $subjects[$i]; ?></td>
                        <td>
                            <form action='add_subjects.php' method='post'>
                                <input type='hidden' name='delsub' value='<?php echo $subjects[$i]; ?>' />
                                <input type='submit' class='btn btn-danger' value=' X ' /> 
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
<?php 
    }
    if(!empty($_POST['roll'])){
        $roll = $_POST['roll']; 
        $reg = $_SESSION['reg'];
        $br_code = $_SESSION['br_code'];
        $year = $_SESSION["year"];
        $sem = $_SESSION["sem"];
        $subjects = array();
        $i = 0;

        if($stmt =  $conn->prepare("SELECT `sub` FROM `subjects_2` WHERE `regulation`=? AND `year`=? AND `sem`=? AND `br_code`=?;")){
            $stmt->bind_param("ssss", $reg, $year, $sem, $br_code);
            if($stmt->execute()){
                $stmt->bind_result($subject);
                while($stmt->fetch()){
                    $subjects[$i++] = $subject;   
                }
            }
            $stmt->close();
        }
        if($stmt =  $conn->prepare("SELECT DISTINCT`subject` FROM `partial_subjects` WHERE `regulation`=? AND `year`=? AND `sem`=? AND `br_code`=? AND `std_id`=?;")){
            $stmt->bind_param("sssds", $reg, $year, $sem, $br_code, $roll);
            if($stmt->execute()){
                $stmt->bind_result($subject);
                while($stmt->fetch()){
                    $subjects[$i++] = $subject;   
                }
            }
            $stmt->close();
        }
        foreach($subjects as $subject){
            echo "<option value='$subject'>$subject</option>";
        }
    }
?>