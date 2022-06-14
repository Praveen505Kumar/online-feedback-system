<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv'] == "admin"){
        require('header.php');

        // connection
        require("Operations.php");
        $opt = new Operations();


        if (isset($_POST["import"]) && !empty($_POST["who"]) && $_POST["who"] == "student") {
           
            $fileName = $_FILES["file"]["tmp_name"];
            
            if ($_FILES["file"]["size"] > 0) {
                
                $file = fopen($fileName, "r");
                while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                    $sid = $column[0];
                    $email = $column[1];
                    $spass = $column[2];
                    $privilege =$column[3];
                    $cr_code = $column[4];
                    $regulation = $column[5];
                    $year =$column[6];
                    $sem = $column[7];
                    $br_code = $column[8];
                    $status = $column[9];
                    $otp_status = $column[10];
                    $feedback_status =$column[11];
                    $msg = $opt->uploadStudentData($sid, $email, $spass, $privilege, $cr_code, $regulation, $year, $sem, $br_code, $status, $otp_status, $feedback_status);
                    
                }
            }
        }
        
        //import faculty
        if (isset($_POST["import"]) && !empty($_POST["who"]) && $_POST["who"] == "faculty") {
           
            $fileName = $_FILES["filefac"]["tmp_name"];
            
            if ($_FILES["filefac"]["size"] > 0) {
                
                $file = fopen($fileName, "r");
                while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                    $fname = $column[0];
                    $br_code = $column[1];
                    $branch = $column[2];
                    $fuser =$column[3];
                    $fpass = $column[4];
                    $priv = $column[5];
                    $email = $column[6];
                    $otp_status = $column[7];
                    
                    $msg = $opt->uploadFacultyData($fname, $br_code, $branch, $fuser, $fpass, $priv, $email, $otp_status);
                    
                }
            }
        }
        
?>
<div class="mx-2">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 7;
                        require_once("menu.php");
                    ?>
            </div>
        </div>
        <div class="col-7 mx-0 my-2">
            <div class="container text-center mb-5">
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
            <div class="row justify-content-center align-items-center my-5 py-5">
                <div class="card cards content text-center m-2 p-0" style="max-width:400px;">
                    <div class="card-header" style="font-weight: bold;">Import Students Details / Add Regulation</div>
                    <div class="card-body">
                        <form action="" method="POST" name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                            <div class="mb-3 px-4">
                                <input class="form-control form-control-lg" type="file" name="file" id="file" accept=".csv">
                            </div>
                            <input type="hidden" name="who" value="student">
                            <button class="btn btn-md btn-success btn-block px-5" type="submit" name="import">Import</button>
                            <?php
                                if($msg == "success"){
                                    echo '<br /><div class="alert alert-success" role="alert">Student Data Uploaded Successfully..!</div>';
                                }
                                if($msg == "failure"){
                                    echo '<br /><div class="alert alert-danger" role="alert">Problem in Importing..!</div>';
                                }
                            ?>
                        </form>
                    </div>
                    
                </div>
                <br>
                <div class="card cards content text-center m-0 p-0" style="max-width:400px;">
                    <div class="card-header" style="font-weight: bold;">Import Faculty Details / Add Branch</div>
                    <div class="card-body">
                        <form action="" method="POST" name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                            <div class="mb-3 px-4">
                                <input class="form-control form-control-lg" type="file" name="filefac" id="filefac" accept=".csv">
                            </div>
                            <input type="hidden" name="who" value="faculty" >
                            <button class="btn btn-md btn-success btn-block px-5" type="submit"  name="import"> Import  </button>
                            <?php
                                if($msg == "success1"){
                                    echo '<br /><div class="alert alert-success" role="alert">Faculty Data Uploaded Successfully..!</div>';
                                }
                                if($msg == "failure1"){
                                    echo '<br /><div class="alert alert-danger" role="alert">Problem in Importing..!</div>';
                                }
                                if($msg == "some"){
                                    echo '<br /><div class="alert alert-danger" role="alert">Other Problem..!</div>';
                                }
                            ?>
                        </form>
                    </div>
                    
                </div>
            </div>
            <div class="text-secondary bg-white rounded p-3">
                <h3>Note : </h3>
                <p>If you want to add new regulation then upload student details with that regulation and similary for new branch.</p>
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