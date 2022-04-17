<?php
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && ($_SESSION['priv']="admin")){
        require('header.php');
        require('config/db_connect.php');
        date_default_timezone_set("Asia/Kolkata");
        $today = date("Y-m-d\TH:i",time());
        if(!empty($conn)){
            $br_code = $_SESSION['br_code'];
            if ($stmt = $conn->prepare("SELECT regulation, year, sem, from_date, to_date FROM `activation` WHERE branch=? ORDER BY from_date;")) {
                $stmt->bind_param("s",$br_code);
                if($stmt->execute()){
                    $stmt->bind_result($reg, $year, $sem, $from_date, $to_date);
                    $i = 0;
                    $feedbacks = array();
                    while ($stmt->fetch()) {
                        $feedbacks[$i]['reg'] = $reg;
                        $feedbacks[$i]['year'] = $year;
                        $feedbacks[$i]['sem'] = $sem;
                        $feedbacks[$i]['from_date'] = date("Y-m-d\TH:i", strtotime($from_date));
                        $feedbacks[$i]['to_date'] = date("Y-m-d\TH:i", strtotime($to_date));
                        $i++;
                    }
                }
            }
        }

    
?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 3;
                        require_once("menu.php");
                    ?>
            </div>
        </div>
        <div class="col-7 ms-3 my-2">
            <div class="container text-center">
                <?php
                    echo "<h4>Selected Department: &emsp;";
                    if(!empty($_SESSION['branch']) && $_SESSION['branch']=="all"){
                        echo "None";
                    }else{
                        echo $_SESSION['branch'];
                    }
                    echo "</h4>";
                ?>
            </div>
            <div class="table-responsive">
                <table class="table table-danger table-hover  border-success text-center">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Course</th>
                            <th scope="col">Regulation</th>
                            <th scope="col">Year</th>
                            <th scope="col">Semester</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            foreach($feedbacks as $feedback){
                                if(!empty($feedback['from_date']) && !empty($feedback['to_date']) && $feedback['from_date'] <= $today && $today <= $feedback['to_date']){
                                    $status = '<span class="text-success">Active</span>';
                                }else{
                                    $status = '<span class="text-default">Completed</span>';
                                }
                                ?>
                                <tr>
                                    <th scope="row"><?php echo $i++ ?></th>
                                    <td>B.Tech</td>
                                    <td><?php echo $feedback['reg'] ?></td>
                                    <td><?php echo $feedback['year'] ?></td>
                                    <td><?php echo $feedback['sem'] ?></td>
                                    <td><?php echo $feedback['from_date'] ?></td>
                                    <td><?php echo $feedback['to_date'] ?></td>
                                    <td><?php echo $status?></td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

</div>

<?php 
        require('footer.php');
    }
?>