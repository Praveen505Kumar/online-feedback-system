<?php
    @session_start();
	if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']="admin"){
	    require('header.php');
        
        $br_codes = array("CSE"=>05, "CIVIL"=>01, "EEE"=>02, "ECE"=>04, "MECH"=>03, "FDT"=>07);
        
        if(!empty($_POST['dept'])){
            $_SESSION['br_code'] = $br_codes[$_POST['dept']];
            $_SESSION['branch'] = $_POST['dept'];
        }

        // connection
        require("Operations.php");
        $opt = new Operations();
        // getting departments
        $branches = $opt->getDepartment();
?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 1;
                        require_once("menu.php");
                    ?>
            </div>
        </div>
        <div class="col-7 mx-5 my-2">
            <div class="container text-center">
                <h4 class="p-4">Welcome Administrator</h4>
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
                <div class="card-header">
                    <label for="uname" class="names">Select Department</label>
                </div>
                <div class="card-body">
                    <form action="admin.php" method="POST">
                        <div class="mb-3 input-group justify-content-center text-center">
                            <select name="dept" required>
                                        <option value="">--Select Department--</option>
                                        <?php
                                            foreach($branches as $branch){
                                                echo '<option value="'.$branch.'">'.$branch.'</option>';
                                            } 
                                        ?>
                                    
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary sb-btn">Change Department</button>
                    </form>
                </div>
                
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




