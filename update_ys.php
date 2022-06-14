<?php
    @session_start();
	if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']=="admin"){
	    require('header.php');
        
        // connection
        require("Operations.php");
        $opt = new Operations();

        // getting regulation
        $regulation = $opt->getRegulation();

        // update std year and sem
        if(isset($_POST['update'])){
            $fromreg = $_POST['fromreg'];
            $fromyear = $_POST['fromyear'];
            $fromsem = $_POST['fromsem'];
            $toreg = $_POST['toreg'];
            $toyear = $_POST['toyear'];
            $tosem = $_POST['tosem'];
            $msg = $opt->UpdateStdYearSem($fromreg, $fromyear, $fromsem, $toreg, $toyear, $tosem);
        }
?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 16;
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
                    if($msg == "updatesuccess"){
                        echo '<br /><div class="alert alert-success" role="alert">Students Year/Sem Updated..!</div>';
                    }else if($msg == "updatefailure"){
                        echo '<br /><div class="alert alert-danger" role="alert">Student Year/Sem Not Updated..!</div>';
                    }
                ?>
            </div>
            
            <form action="" method="post" class="bg-white rounded p-3 mt-5">
                <h2 class="text-center">FROM</h2>
                <div class="mb-4 row justify-content-center">
                    <label class="col-auto col-form-label" style="font-weight: bold;" for="fromreg">Regulation:</label>
                    <div class="col-auto ">
                        <select class="form-select text-center" name="fromreg" id="fromreg" required>
                            <option value="">Select</option>
                            <?php
                                for($i=0;$i < sizeof($regulation);$i++){
                                    echo "<option value='".$regulation[$i]."'>".$regulation[$i]."</option>";
                                }
                            ?>        
                        </select>
                    </div>
                    <label class="col-auto col-form-label" style="font-weight: bold;" for="fromyear" required>Year:</label>
                    <div class="col-auto">
                        <select class="form-select text-center" name="fromyear" id="fromyear" required>
                            <option value="">Select</option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III" >III</option>
                            <option value="IV">IV</option>    
                        </select>
                    </div>
                    <label class="col-auto col-form-label" for="fromsem" style="font-weight: bold;" required>Semester:</label>
                    <div class="col-auto">
                        <select class="form-select text-center" name="fromsem" id="fromsem" required>
                            <option value="">Select</option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                        </select>
                    </div>
                </div>
                <h2 class="text-center">TO</h2>
                <div class="mb-4 row justify-content-center">
                    <label class="col-auto col-form-label" style="font-weight: bold;" for="toreg">Regulation:</label>
                    <div class="col-auto ">
                        <select class="form-select text-center" name="toreg" id="toreg" required>
                            <option value="">Select</option>
                            <?php
                                for($i=0;$i < sizeof($regulation);$i++){
                                    echo "<option value='".$regulation[$i]."'>".$regulation[$i]."</option>";
                                }
                            ?>        
                        </select>
                    </div>
                    <label class="col-auto col-form-label" style="font-weight: bold;" for="toyear" required>Year:</label>
                    <div class="col-auto">
                        <select class="form-select text-center" name="toyear" id="toyear" required>
                            <option value="">Select</option>
                            <option value="I" >I</option>
                            <option value="II">II</option>
                            <option value="III" >III</option>
                            <option value="IV">IV</option>    
                        </select>
                    </div>
                    <label class="col-auto col-form-label" for="tosem" style="font-weight: bold;" required>Semester:</label>
                    <div class="col-auto">
                        <select class="form-select text-center" name="tosem" id="tosem" required>
                            <option value="">Select</option>
                            <option value="I" >I</option>
                            <option value="II" >II</option>
                        </select>
                    </div>
                    <input type="submit" class="col-5 btn btn-primary mt-5" name="update" value="UPDATE">
                </div>
            </form>
        
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




