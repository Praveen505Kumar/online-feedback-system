<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv'])&& ($_SESSION['priv']="hod" || $_SESSION['priv']="admin")){
        require('header.php');
    
        if($_SESSION['user']=="admin" || strtolower($_SESSION['user'])=="administrator"){
            require("config/db_connect.php");
            if(!empty($_POST['fname'])){
                
                $fac=$_POST['fname'];
                if(!empty($conn)){
                    //For delete faculty
                    if($stmt=$conn->prepare("DELETE FROM `fac_login` WHERE `fname`=?;")){
                        
                        $stmt->bind_param("s", $fac);
                        if($stmt->execute()){
                            $msg="success";
                        }
                        else{
                            $msg="failure";
                        }
                        $stmt->close();
                    }
                    

                }
            }
            
            //getting the list
            $br=$_SESSION['br'];
            $faculties=array();
            if(!empty($conn)){
                
                if($stmt=$conn->prepare("SELECT DISTINCT `fname` FROM `fac_login` WHERE `br_code`=? AND `privilege` LIKE '%staff%'; ")){
                    
                    $stmt->bind_param("s", $br);
                    
                    if($stmt->execute()){
                        
                        $stmt->bind_result($fac);
                        $i=0;
                        
                        while($stmt->fetch()){
                            $faculties[$i]=$fac;
                            $i++;
                        }
                    }
                    $stmt->close();
                }
            }
        }
?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 10;
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
            
            <div class="card cards content text-center mt-5" style="max-width:500px;">
                
                <div class="card-header" style="font-weight: bold;">Select The Following</div>
                <div class="card-body">
                    <form action="delete_fac.php" method="post">
                        <div class="mb-3 input-group justify-content-center">
                            <div class="mb-3 row">
                                <label class="col-sm-5 col-form-label" for="inputPassword" >Select Regulation : &emsp;</label>
                                <div class="col-sm-6">
                                    <select class="form-select" name="fname" id="fac" required>
                                        <option class="text-center" value="">--Select--</option>
                                        <?php
                                            for($i=0;$i < sizeof($faculties);$i++){
                                                echo "<option value='".$faculties[$i]."'>".$faculties[$i]."</option>";
                                            }
                                        ?>        
                                </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-6 col-form-label" for="inputPassword" style="font-weight: bold;">Select Specialization : &emsp;</label>
                                <div class="col-sm-6">
                                    <select class="form-select" name="fname" id="fac" required>
                                        <option class="text-center" value="">--Select--</option>
                                        <?php
                                            for($i=0;$i < sizeof($faculties);$i++){
                                                echo "<option value='".$faculties[$i]."'>".$faculties[$i]."</option>";
                                            }
                                        ?>        
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-6 col-form-label" for="inputPassword" >Select Year : &emsp;</label>
                                <div class="col-sm-6">
                                    <select class="form-select" name="fname" id="fac" required>
                                        <option class="text-center" value="">--Select--</option>
                                        <?php
                                            for($i=0;$i < sizeof($faculties);$i++){
                                                echo "<option value='".$faculties[$i]."'>".$faculties[$i]."</option>";
                                            }
                                        ?>        
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-6 col-form-label" for="inputPassword" style="font-weight: bold;">Select Semester : &emsp;</label>
                                <div class="col-sm-6">
                                    <select class="form-select" name="fname" id="fac" required>
                                        <option class="text-center" value="">--Select--</option>
                                        <?php
                                            for($i=0;$i < sizeof($faculties);$i++){
                                                echo "<option value='".$faculties[$i]."'>".$faculties[$i]."</option>";
                                            }
                                        ?>        
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-6 col-form-label" for="inputPassword" style="font-weight: bold;">Select Feedback Time : &emsp;</label>
                                <div class="col-sm-6">
                                    <select class="form-select" name="fname" id="fac" required>
                                        <option class="text-center" value="">--Select--</option>
                                        <?php
                                            for($i=0;$i < sizeof($faculties);$i++){
                                                echo "<option value='".$faculties[$i]."'>".$faculties[$i]."</option>";
                                            }
                                        ?>        
                                    </select>
                                </div>
                            </div>
                            
                            
                        </div>
                        
                        <button type="submit" class="btn btn-primary sb-btn px-5">Activate</button>
                    </form>
                    <?php
                        if($msg=="success"){
                            echo '<br /><div class="alert alert-success" role="alert">Faculty Details Deleted..!</div>';
                        }
                        if($msg=="failure"){
                            echo '<br /><div class="alert alert-danger" role="alert">Faculty Details Not Deleted..!</div>';
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