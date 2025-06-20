<?php
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv'])){
        $username = $_SESSION['user'];
        require_once('header.php');

        if(!empty($_POST['currentpass']) && !empty($_POST['newpass']) && !empty($_POST['renewpass']))
        {
            // connection
            require("Operations.php");
            $opt = new Operations();

            // changing password
            $currentpass = $_POST['currentpass'];
            $newpass = $_POST['newpass'];
            $renewpass = $_POST['renewpass'];
            $msg = $opt->changePassword($username, $currentpass, $newpass, $renewpass);
        }
    
?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        if($_SESSION['priv'] == "admin"){
                            $menu_id = 13;
                            require_once("menu.php");
                        }else if($_SESSION['priv'] == "staff"){
                            $menu_id = 2;
                            require_once("facmenu.php");
                        }else if($_SESSION['priv'] == "hod"){
                            $menu_id = 4;
                            require_once("hodmenu.php");
                        }
                    ?>
            </div>
        </div>
        <div class="col-7 mx-5 my-2">
            <div class="container text-center">
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
            
            <div class="card cards content text-center" style="max-width:500px;">
                
                <div class="card-header" style="font-weight: bold;">Change Password</div>
                <div class="card-body">
                    <form action="chg_pwd.php" method="POST">
                        <div class="row mb-3 text-start">
                            <label class="col-sm-6 text-dark col-form-label">Current Password&emsp;&emsp;&emsp;:</label>
                            <div class="col-sm-5">
                                <input type="password" class="form-control" name="currentpass" required>
                            </div>
                            
                        </div>
                        <div class="row mb-3 text-start">
                            <label class="col-sm-6 text-dark col-form-label">Enter New Password&emsp;&emsp;:</label>
                            <div class="col-sm-5">
                                <input type="password" class="form-control" name="newpass" required>
                            </div>
                        </div>
                        <div class="row mb-3 text-start">
                            <label class="col-sm-6 text-dark col-form-label">Re-enter New Password&emsp;:</label>
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
            
        </div>
    </div>
</div>

<?php 
        require('footer.php');
    }else{
        header('Location: index.php');
    }
?>