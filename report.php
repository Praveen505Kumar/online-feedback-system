<?php 
    @session_start();
    
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && ($_SESSION['priv'] == "hod" || $_SESSION['priv'] == "admin" || $_SESSION['priv'] == "staff" )){
        
        if($_SESSION['priv'] == 'staff'){
            $_POST['facname'] = $_SESSION['user'];
        }

        if(!empty($_POST['facname']) && !empty($_POST['subject']) ){
            require('header.php');

            // connection
            require("Operations.php");
            $opt = new Operations();

            $facname = $_POST['facname'];
            $data = explode('_', $_POST['subject']);
            $subject = $data[0];
            $feed_id = $data[1];

            // get questions and percentages
            $questions = $opt->getQuestionsPer($facname, $subject, $feed_id);

            // get commets
            $comments = $opt->getComments($facname, $subject, $feed_id);
        
    
?>
<div class="ms-2">
    <div class="row mx-0">
        <div class="col-sm-4 mt-3 me-4 d-print-none" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        if($_SESSION['priv'] == "admin"){
                            $menu_id = 11;
                            require_once("menu.php");
                        }else if($_SESSION['priv'] == "staff"){
                            $menu_id = 1;
                            require_once("facmenu.php");
                        }else{
                            $menu_id = 3;
                            require_once("hodmenu.php");
                        }
                        
                    ?>
            </div>
        </div>
        <div class="col-sm-8 my-2">
            <div class="container text-center d-print-none">
                <?php
                    if($_SESSION['priv'] == "admin"){
                        echo "<h4>Selected Department: &emsp;";
                        if(!empty($_SESSION['branch']) && $_SESSION['branch']=="all"){
                            echo "None";
                        }else{
                            echo $_SESSION['branch'];
                        }
                        echo "</h4>";
                    }
                    
                ?>
            </div>
            <div class="row my-4 bg-light rounded p-3">
                <div class="col-5">
                    <p>Subject&emsp;:&emsp;<?php echo strtoupper($subject);?></p>
                    <p>Overall rating :&emsp;
                        <meter value="<?php echo round($questions['average']*10, 2);?>" min="0" max="100"></meter>
                        <?php echo round($questions['average']*10, 2); ?>%
                    </p>
                </div>
                <div class="col-5">
                    <p>Faculty name&emsp;:&emsp;<?php echo strtoupper($facname);?></p>
                    <p>No.of students submitted&emsp;:&emsp;<?php echo $questions['stdcount'];?></p>
                </div>
                <div class="col-2">
                    <?php 
                        echo '<a href="printpdf.php?facname='.urlencode($facname).'&subject='.urlencode($subject).'&feed_id='.urldecode($feed_id).'&'.'" class="btn btn-primary sb-btn px-5 mt-3">Print</a>';
                    ?>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-light table-hover border border-2 border-info caption-top" >
                    <caption class="text-light">Individual Report</caption>
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Question</th>
                            <th scope="col">Rating</th>
                            <th scope="col">Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for($i=0; $i<14 ; $i++){
                                $sno = $i+1;
                                ?>
                                <tr>
                                    <th scope="row"><?php echo $sno; ?></th>
                                    <td><?php echo $questions[0][$i]; ?></td>
                                    <td><meter value="<?php echo round( (($questions[1][$i] / ($questions['stdcount']*10) ) * 100), 2);?>" min="0" max="100"></meter></td>
                                    <td><?php echo round( (($questions[1][$i] / ($questions['stdcount']*10) ) * 100), 2);?>%</td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <h4 class="text-primary">Comments&emsp;:&emsp;</h4>
            <ul class="list-group">
                <?php
                    $length = sizeof($comments);
                    if($length == 0){
                        echo '<div class="alert alert-success text-center" role="alert">No Comments Found..!</div>';
                    }else{
                        foreach($comments as $comment){
                            echo '<li class="list-group-item">'.$comment.'</li>';
                        }
                    }
                ?>
            </ul>

    
        </div>
        
    </div>
</div>
<?php 
            require('footer.php');
        }
    }else{

        header('Location: index.php');
    }
?>