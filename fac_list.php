<?php
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv'])&& ($_SESSION['priv']="admin")){
        require('header.php');
        require('config/db_connect.php');
        if(!empty($conn)){
            $br_code = $_SESSION['br_code'];
            if ($stmt = $conn->prepare("SELECT fid, AVG(avg) FROM `ques` WHERE fid IN (SELECT fname FROM `fac_login` WHERE br_code=?) GROUP BY fid ORDER BY AVG(avg) DESC;")) {
                $stmt->bind_param("s",$br_code);
                if($stmt->execute()){
                    $stmt->bind_result($fac, $score);
                    $i = 0;
                    $res = array();
                    while ($stmt->fetch()) {
                        $res[$i]['fname'] = $fac;
                        $res[$i]['score'] = $score;
                        $i++;
                    }
                    
                }
            }
        }

    
?>
<div class="row">
    <div class="col-sm-5 mt-3" style="max-width:400px;">
        <div class="list-group">
                <?php
                    $menu_id = 6;
                    require_once("menu.php");
                ?>
        </div>
    </div>
    <div class="col-sm-8">
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
        <div class="container">
            <table class="table table-dark table-hover table-bordered border-primary">
                <thead>
                    <tr>
                        <th scope="col">S.No.</th>
                        <th scope="col">Name of the Faculty</th>
                        <th scope="col">Average</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        for($i=0;$i < sizeof($res);$i++){
                            echo "<tr><td scope='row'>";
                            echo ($i+1)."</td><td>".$res[$i]['fname']."</td><td>".round($res[$i]['score'], 2)."</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div> 
    </div>
</div>
<?php 
        require('footer.php');
    }
?>