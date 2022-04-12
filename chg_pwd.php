<?php
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv'])){
        $username = $_SESSION['user'];
        require_once('header.php');

        if(!empty($_POST['currentpass'])&& !empty($_POST['newpass']) && !empty($_POST['renewpass']))
        {
            require("config/db_connect.php");
            if(!empty($conn)){

                $currentpass = $_POST['currentpass'];
                $newpass = $_POST['newpass'];
                $renewpass = $_POST['renewpass'];

                if($stmt=$conn->prepare("SELECT fpass FROM `fac_login` WHERE `fname`=?")){
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->bind_result($password);
                    $stmt->fetch();
                    if($password != $currentpass){
                        $msg = 'incorrect';
                    }else if($newpass != $renewpass){
                        $msg = "pwd_mismatch";
                    }else{
                        
                        $stmt->close();
                        
                        if($stmt=$conn->prepare("UPDATE `fac_login` SET `fpass`=? WHERE `fname`=?")){
                            
                            $stmt->bind_param("ss", $newpass, $username);
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
<div class="row">
    <div class="col-5 mt-3" style="max-width:400px;">
        <div class="list-group">
                <?php
                    $menu_id = 13;
                    require_once("menu.php");
                ?>
        </div>
    </div>
    <div class="col-7">
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
        
        <div class="card cards content text-center" style="max-width:500px;">
            
            <div class="card-header" style="font-weight: bold;">Change Password</div>
            <div class="card-body">
                <form action="chg_pwd.php" method="POST">
                    <div class="row mb-3 text-start">
                        <label class="col-sm-6 text-light col-form-label">Current Password&emsp;&emsp;&emsp;:</label>
                        <div class="col-sm-5">
                            <input type="password" class="form-control" name="currentpass" required>
                        </div>
                        
                    </div>
                    <div class="row mb-3 text-start">
                        <label class="col-sm-6 text-light col-form-label">Enter New Password&emsp;&emsp;:</label>
                        <div class="col-sm-5">
                            <input type="password" class="form-control" name="newpass" required>
                        </div>
                    </div>
                    <div class="row mb-3 text-start">
                        <label class="col-sm-6 text-light col-form-label">Re-enter New Password&emsp;:</label>
                        <div class="col-sm-5">
                            <input type="password" class="form-control" name="renewpass" required>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block px-5" type="submit">Go</button>
                </form>
                <?php
                    if($msg=="pwd_chngd"){
                        echo '<br /><div class="alert alert-success" role="alert">Password Changed Successfully..!</div>';
                    }
                    if($msg=="incorrect"){
                        echo '<br /><div class="alert alert-danger" role="alert">Current Password Incorrect..!</div>';
                    }	
                    if($msg=="pwd_mismatch"){
                        echo '<br /><div class="alert alert-danger" role="alert">Re-enter Password Mismatch..!</div>';
                    }	
                ?>
            </div>
        </div>
        <?php 
            require('footer.php');
        ?>
    </div>
</div>