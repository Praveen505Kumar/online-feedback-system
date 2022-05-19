<?php 
    @session_start();
    
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && ($_SESSION['priv'] == "hod" || $_SESSION['priv']=="admin" || $_SESSION['priv']=="staff" )){
        if($_SESSION['priv'] == 'staff'){
            $_POST['facname'] = $_SESSION['user'];
        }
        if(!empty($_POST['facname']) && !empty($_POST['subject']) ){
            require('header.php');
            require("config/db_connect.php");

            $facname = $_POST['facname'];
            $subject = $_POST['subject'];
            $questions = array();
            $comments = array();
            $questions[0][0] = "Teacher comes to the class on time";
            $questions[1][0] = "Teacher speaks clearly and audibly";
            $questions[2][0] = "Teacher plans lesson with clear objective";
            $questions[3][0] = "Teacher has got command on the subject";
            $questions[4][0] = "Teacher writes and draws legibly";
            $questions[5][0] = "Teacher asks qstions to promote interaction and effective thinking";
            $questions[6][0] = "Teacher encourages,compliments and praises originality and creativity displayed by the student";
            $questions[7][0] = "Teacher is courteous and impartial in dealing with the students";
            $questions[8][0] = "Teacher covers the syllabus completely";
            $questions[9][0] = "Teacher evaluation of the sessional exams answer scripts,lab records etc is fair and impartial";
            $questions[10][0] = "Teacher is prompt in valuing and returning the answer scripts providing feedback on performanc";
            $questions[11][0] = "Teacher offers assistance and counseling to the needy students";
            $questions[12][0] = "Teacher imparts the practical knowledge concerned to the subject";
            $questions[13][0] = "Teacher leaves the class on time";

            if(!empty($conn)){
                if($stmt=$conn->prepare("SELECT  `qs1`, `qs2`, `qs3`, `qs4`, `qs5`, `qs6`, `qs7`, `qs8`, `qs9`, `qs10`, `qs11`, `qs12`, `qs13`, `qs14`, `avg`, `count` FROM `ques` WHERE `fid`=? AND  `sid`=?; ")){
                    $stmt->bind_param("ss", $facname, $subject);
                    
                    if($stmt->execute()){
                        
                        $stmt->bind_result($qs1, $qs2, $qs3, $qs4, $qs5, $qs6, $qs7, $qs8, $qs9, $qs10, $qs11, $qs12, $qs13, $qs14, $avg, $count);
                        
                        while($stmt->fetch()){
                            $questions[0][1] = $qs1;
                            $questions[1][1] = $qs2;
                            $questions[2][1] = $qs3;
                            $questions[3][1] = $qs4;
                            $questions[4][1] = $qs5;
                            $questions[5][1] = $qs6;
                            $questions[6][1] = $qs7;
                            $questions[7][1] = $qs8;
                            $questions[8][1] = $qs9;
                            $questions[9][1] = $qs10;
                            $questions[10][1] = $qs11;
                            $questions[11][1] = $qs12;
                            $questions[12][1] = $qs13;
                            $questions[13][1] = $qs14;
                            $stdcount = $count;
                            $average = $avg;
                        }
                    }
                }
                if($stmt=$conn->prepare("SELECT cmnt FROM `comments` WHERE fname = ? AND subject = ?;")){
                    $stmt->bind_param("ss", $facname, $subject);
                    if($stmt->execute()){
                        
                        $stmt->bind_result($comment);
                        $i = 0;
                        while($stmt->fetch()){
                            $comments[$i] = $comment;
                            $i++;
                        }
                    }

                }
            }

        
    
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
            <div class="row">
                <div class="col-6">
                    <p>Subject&emsp;:&emsp;<?php echo strtoupper($subject);?></p>
                    <p>Overall rating :&emsp;
                        <meter value="<?php echo round($average*10, 2);?>" min="0" max="100"></meter>
                        <?php echo round($average*10, 2); ?>%
                    </p>
                </div>
                <div class="col-6">
                    <p>Faculty name&emsp;:&emsp;<?php echo strtoupper($facname);?></p>
                    <p>No.of students submitted&emsp;:&emsp;<?php echo $stdcount;?></p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-secondary table-hover border border-2 border-info" >
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
                                    <td><?php echo $questions[$i][0]; ?></td>
                                    <td><meter value="<?php echo round( (($questions[$i][1] / ($stdcount*10) ) * 100), 2);?>" min="0" max="100"></meter></td>
                                    <td><?php echo round( (($questions[$i][1] / ($stdcount*10) ) * 100), 2);?>%</td>
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
                        echo '<div class="alert alert-success" role="alert">Comments Not Found..!</div>';
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