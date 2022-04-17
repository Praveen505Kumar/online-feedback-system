<?php
    session_start();
	if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']="admin"){
	    require('header.php');
    }
    
    if($_SESSION['user']=="admin" || strtolower($_SESSION['user'])=="administrator"){
			
        if(!empty($_POST)){
            if(!empty($_POST['dept'])){
                $br = explode("|",$_POST['dept']);
                $_SESSION['br_code'] = $br[0];
                $_SESSION['br'] = $br[0];
                $_SESSION['branch'] = $br[1];
            }
        }
        
    }
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
                    <form action="admin.php" method="post">
                        <div class="mb-3 input-group justify-content-center">
                            <select name="dept" required>
                                        <option value="">--Select Department--</option>
                                        <option value="01|CIVIL">CIVIL</option>
                                        <option value="02|EEE">EEE</option>
                                        <option value="03|MECH">MECH</option>
                                        <option value="04|ECE">ECE</option>
                                        <option value="05|CSE">CSE</option>
                                        <option value="27|FDT">FOOD TECH</option>
                                        
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
?>




<!-- <div class=" d-flex align-items-end justify-content-center">
    <div class="d-flex flex-column mt-5 p-5" style="max-width:400px;">
        <a href="admin.php" class="btn btn-primary btn-lg m-2">CIVIL</a>
        <a href="#" class="btn btn-primary btn-lg m-2">EEE</a>
        <a href="#" class="btn btn-primary btn-lg m-2">MECH</a>
        <a href="#" class="btn btn-primary btn-lg m-2">ECE</a>
        <a href="#" class="btn btn-primary btn-lg m-2">CSE</a>
        <a href="#" class="btn btn-primary btn-lg m-2">FOOD TECH</a>
    </div>
    
</div> -->





<!-- <div class="row">
    <div class="col-5" style="max-width:400px;">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action active">
                Home
            </a>
            <a href="#" class="list-group-item list-group-item-action">
                Activate Feedback
            </a>
            <a href="#" class="list-group-item list-group-item-action">Show Activated Feedbacks</a>
            <a href="#" class="list-group-item list-group-item-action">Reset Student Password</a>
            <a href="#" class="list-group-item list-group-item-action">Delete Faculty</a>
            <a href="#" class="list-group-item list-group-item-action">Faculty List</a>
            <a href="#" class="list-group-item list-group-item-action">Upload Data</a>
            <a href="#" class="list-group-item list-group-item-action">Add/View Subjects</a>
            <a href="#" class="list-group-item list-group-item-action">Fac_Course Mapping</a>
            <a href="#" class="list-group-item list-group-item-action">All Faculty Report</a>
            <a href="#" class="list-group-item list-group-item-action">Individual Report</a>
            <a href="#" class="list-group-item list-group-item-action">Individual Written Feedback</a>
            <a href="#" class="list-group-item list-group-item-action">Change Password</a>
            <a href="#" class="list-group-item list-group-item-action">Log Out</a>
        
        </div>
    </div>
    <div class="col-7 text-center container align-self-center">
        <form action="admin.php" method="post">
            <div>   
                <select name="dept" required>
                    <option value="">--Select Department--</option>
                    <option value="01|CIVIL">CIVIL</option>
                    <option value="02|EEE">EEE</option>
                    <option value="03|MECH">MECH</option>
                    <option value="04|ECE">ECE</option>
                    <option value="05|CSE">CSE</option>
                    <option value="27|FDT">FOOD TECH</option>	
                </select>
            </div>
            
            <input type="submit" name="deptchange" class="btn btn-primary"value="Change Department" />
        </form>
    </div>
</div> -->



