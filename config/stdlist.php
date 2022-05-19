<?php
    @session_start();
    require("db_connect.php");
    
    if(!empty($_POST['reg']) && !empty($_POST['year']) && !empty($_POST['sem']) && !empty($_POST['subject'])){
        $stdlist = array();
        $reg = $_POST['reg'];
        $year = $_POST['year'];
        $sem = $_POST['sem'];
        $subject = $_POST['subject'];
        $br_code = $_SESSION['br_code'];
        if($stmt = $conn->prepare("SELECT `sid` FROM `st_login` WHERE `regulation`=? AND `year`=? AND `sem`=? AND `br_code`=?;")){
            $stmt->bind_param("dssd", $reg, $year, $sem, $br_code);
            if($stmt->execute()){
                $stmt->bind_result($roll);
                $i = 0;
                while($stmt->fetch()){
                    $stdlist[$i] = $roll;
                    $i++;
                }
            }
        }
?>
        <form action="add_subjects.php" method="POST">
            <table class="table table-success table-hover  border-success text-center">
                <thead>
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Roll No.</th>
                        <th scope="col">Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        for($i=0;$i<sizeof($stdlist);$i++) { ?>
                            <tr>
                                <th scope="row"><?php echo $i+1; ?></th>
                                <td><?php echo $stdlist[$i]; ?></td>
                                <td>
                                    <input type="checkbox" class="form-check-input" name="std[]" id="std" value="<?php echo $stdlist[$i]; ?>">
                                </td>
                            </tr>
                    <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="sub" value="<?php echo $subject; ?> ">
            <input type="hidden" name="reg" value="<?php echo $reg; ?> ">
            <input type="hidden" name="year" value="<?php echo $year; ?> ">
            <input type="hidden" name="sem" value="<?php echo $sem; ?> ">
            <button type="submit" class="btn btn-primary" name="partialadd">ADD</button>
        </form>
<?php 
    } else{
        echo '<h4>Please Input Subject Name</h4>';
    }
?>

