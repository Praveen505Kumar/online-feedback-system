<?php
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv'])&& $_SESSION['priv']=="admin"){
        require('header.php');

        // connection
        require("Operations.php");
        $opt = new Operations();

        //getting faculties scores
        $br_code = $_SESSION['br_code'];
        $faculties = $opt->getFacultyScores($br_code);
    
?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 6;
                        require_once("menu.php");
                    ?>
            </div>
        </div>
        <div class="col-7 mx-5 my-2">
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
                            for($i=0; $i < sizeof($faculties);$i++){
                                echo "<tr><td scope='row'>";
                                echo ($i+1)."</td><td>".$faculties[$i]['fname']."</td><td>".round($faculties[$i]['score'], 2)."</td></tr>";
                            }
                        ?>
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