<?php
@session_start();
    require("db_connect.php");
    
    if(!empty($_POST['reg'])){
        $reg = $_POST['reg'];
        $branch = $_SESSION['branch'];
        $year = $_POST["year"];
        $sem = $_POST["sem"];
        if($stmt =  $conn->prepare("SELECT `id`, `sub` FROM `subjects_2` WHERE `regulation`=? AND `year`=? AND `sem`=? AND `branch`=?;")){
            
            $stmt->bind_param("ssss", $reg, $year, $sem, $branch);
            if($stmt->execute()){
                $stmt->bind_result($id, $subject);
                $i = 1;
                echo "<table class='table table-striped'>";
                echo "<tbody>";
                while($stmt->fetch()){
                    echo "<tr>
                                <th scope='row'>".$i++."</th>
                                <td>".$subject."</td>
                                <td><form action='add_subjects.php' method='post'>
                                        <input type='hidden' name='subid' value='".$id."' />
                                        <input type='submit' name='deletesub' class='btn btn-danger' value=' X ' /> 
                                    </form></td>
                            </tr>";
                }
                echo "</tbody>";
                echo "</table>";
            }
            $stmt->close();
        }
    }
?>