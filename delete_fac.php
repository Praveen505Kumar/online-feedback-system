<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']=="admin"){
        require('header.php');
        
        // connection
        require("Operations.php");
        $opt = new Operations();

        // delete faculty
        if(!empty($_POST['fname'])){
            $faculty = $_POST['fname'];
            
            $msg = $opt->deleteFaculty($faculty);

        }

        //getting faculties list
        $br_code = $_SESSION['br_code'];
        $faculties = $opt->getFaultyList($br_code);
        
?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 5;
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

            <div class="card cards content text-center" style="max-width:500px;">
                
                <div class="card-header" style="font-weight: bold;">Remove Faculty</div>
                <div class="card-body">
                    <form action="delete_fac.php" method="post">
                        <div class="mb-3 row">
                            <label class="col-sm-5 col-form-label" for="fac" style="font-weight: bold;">Select Faculty Name: &emsp;</label>
                            <div class="col-sm-6">
                                <select class="form-select" name="fname" id="fac" required>
                                    <option value="">--Select faculty--</option>
                                    <?php
                                        for($i=0;$i < sizeof($faculties);$i++){
                                            echo "<option value='".$faculties[$i]."'>".$faculties[$i]."</option>";
                                        }
                                    ?>       
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary sb-btn px-5">Delete</button>
                    </form>
                    <?php
                        if($msg == "success"){
                            echo '<br /><div class="alert alert-success" role="alert">Faculty Details Deleted..!</div>';
                        }
                        if($msg == "failure"){
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
    else{
        header('Location: index.php');
    }
?>