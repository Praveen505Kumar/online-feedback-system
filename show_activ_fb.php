<?php
    @session_start();
    date_default_timezone_set("Asia/Kolkata");
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']=="admin"){
        require('header.php');

        // connection
        require("Operations.php");
        $opt = new Operations();

        // deactivate feedback
        if(!empty($_POST['feed_id'])){
            $today = date("Y-m-d\TH:i", time()-100);
            $feed_id = $_POST['feed_id'];
            
            $msg = $opt->deactive($today, $feed_id);

        }
        
        // get active feedbacks
        $br_code = $_SESSION['br_code'];
        $feedbacks = $opt->getActiveFeedback($br_code);

?>
<div class="mx-2">
    <div class="row mx-0">
        <div class="col-5 mt-3 me-2" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 3;
                        require_once("menu.php");
                    ?>
            </div>
        </div>
        <div class="col-8 ms-1 my-2">
            <div class="container text-center">
                <?php
                    echo "<h4>Selected Department: &emsp;";
                    if(!empty($_SESSION['branch']) && $_SESSION['branch']=="all"){
                        echo "None";
                    }else{
                        echo $_SESSION['branch'];
                    }
                    echo "</h4>";
                    if($msg == "feed_deactive"){
                        echo "<div class='alert alert-success'>Feedback Deactivated..!</div>";
                    }
                ?>
            </div>
            <div class="">
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
                                    $status = ' <div class="row">
                                                    <div class="col-5">
                                                        <span class="text-success">Active</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <form action="show_activ_fb.php" method="POST">
                                                            <input type="hidden" name="feed_id" value="'.$feedback['feed_id'].'"/>
                                                            <button  type="submit" class="btn btn-sm btn-danger px-3" name="stop_feed" >Stop</button>
                                                        </form>
                                                    </div>
                                                </div>';
                                }else{
                                    $status = '<span class="text-default">Completed</span>';
                                }
                                ?>
                                <tr>
                                    <th scope="row"><?php echo $i++; ?></th>
                                    <td>B.Tech</td>
                                    <td><?php echo $feedback['reg']; ?></td>
                                    <td><?php echo $feedback['year']; ?></td>
                                    <td><?php echo $feedback['sem']; ?></td>
                                    <td><?php echo $feedback['from_date']; ?></td>
                                    <td><?php echo $feedback['to_date']; ?></td>
                                    <td><?php echo $status; ?></td>
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
    else{
        header('Location: index.php');
    }
?>