<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv'])&& ($_SESSION['priv']="hod" || $_SESSION['priv']="admin")){
        require('header.php');
        if($_SESSION['user']=="admin" || strtolower($_SESSION['user'])=="administrator"){
            if(!empty($_POST['rollno'])){
                require("config/db_connect.php");
                
                $roll = strtoupper($_POST['rollno']);
                if(!empty($conn)){
                    if($stmt=$conn->prepare("SELECT  email,spass FROM `st_login` WHERE `sid`=?")){
                        $stmt->bind_param("s", $roll);
                        $stmt->execute();
                        $stmt->bind_result($email, $pass);
                        
                        $stmt->fetch();
                        if(empty($email)){
                            $msg = 'no_roll';
                        }else if($roll == $pass){
                            $msg = "pwd_chngd";
                        }else{
                            
                            $stmt->close();
                            
                            if($stmt=$conn->prepare("UPDATE `st_login` SET `spass`=? WHERE `sid`=?")){
                                
                                $stmt->bind_param("ss", $roll, $roll);
                                if($stmt->execute()){
                                    $msg = "pwd_chngd";
                                }
                            }
                        }
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
                        $menu_id = 4;
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
            
            <div class="card cards content text-center" style="max-width:400px;">
                
                <div class="card-header" style="font-weight: bold;">Reset Student Password</div>
                <div class="card-body">
                    <form action="pwdreset.php" method="post">
                        <div class="form-group ">
                            <label for="rollno" class="col-form-label" style="font-weight: bold;">Enter UserName/Roll_No</label>
                            <br/><br/>
                            <input type="text" name="rollno" class="form-control" id="rollno" placeholder="Enter roll number" required>
                        </div>
                        <br/><br/>
                        <button class="btn btn-md btn-primary btn-block" type="submit">Reset Password</button>
                    </form>
                    <?php
                        if($msg=="pwd_chngd"){
                            echo '<br /><div class="alert alert-success" role="alert">Password Changed Successfully..!</div>';
                        }
                        if($msg=="pwd_not_chngd"){
                            echo '<br /><div class="alert alert-danger" role="alert">Password NOT Changed..!</div>';
                        }	
                        if($msg=="no_roll"){
                            echo '<br /><div class="alert alert-danger" role="alert">Student Details Not Found..!</div>';
                        }	
                    ?>
                </div>
                
            </div>
    
        </div>
        
    </div>
</div>

<?php 
        require('footer.php');
    }
?>