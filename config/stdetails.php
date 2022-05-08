<?php
    @session_start();
    require("db_connect.php");
    
    if(!empty($_POST['std'])){
        $details = array();
        $student = $_POST['std'];
        if($stmt = $conn->prepare("SELECT `email`, `spass` FROM `st_login` WHERE `sid`=?;")){
            $stmt->bind_param("s", $student);
            if($stmt->execute()){
                $stmt->bind_result($name, $pass);
                while($stmt->fetch()){
                    $details['name'] = $name;
                    $details['roll'] = $student;
                    $details['pass'] = $pass;
                }
            }
        }
?>
        <form action="student_data.php" method="post">
            <div class="row">
                <div class="mb-4 row">
                    <label class="col-sm-5 col-form-label" for="name" style="font-weight: bold;">Roll NO&emsp;:&emsp;</label>
                    <div class="col-sm-6">
                        <label for=""><?php echo $details['roll']; ?></label>
                        <input type="hidden" name="roll" value="<?php echo $details['roll']; ?>">
                    </div>
                </div>  
                <div class="mb-4 row">
                    <label class="col-sm-5 col-form-label" for="user" style="font-weight: bold;">Name&emsp;:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="name" id="name" required value="<?php echo $details['name']; ?>">
                    </div>
                </div>
                <div class="mb-4 row">
                    <label class="col-sm-5 col-form-label" for="pass" style="font-weight: bold;">Password&emsp;:&emsp;</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" name="pass" id="pass" required value="<?php echo $details['pass']; ?>">
                    </div>
                </div>
                <div class="row justify-content-around">
                    <button type="submit" class="col-5 btn btn-primary" name="updatebutton">UPDATE</button>
                    <button type="button" class="col-5 btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </form>
<?php 
    } 
?>

