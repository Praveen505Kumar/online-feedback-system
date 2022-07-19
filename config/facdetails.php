<?php
    @session_start();
    require("db_connect.php");
    
    if(!empty($_POST['fac'])){
        $details = array();
        $faculy = $_POST['fac'];
        if($stmt = $conn->prepare("SELECT `fuser`, `fpass`, `privilege`, `email` FROM `fac_login` WHERE `fname`=?;")){
            $stmt->bind_param("s",$faculy);
            if($stmt->execute()){
                $stmt->bind_result($user, $pass, $priv, $email);
                while($stmt->fetch()){
                    $details['name'] = $faculy;
                    $details['user'] = $user;
                    $details['pass'] = $pass;
                    $details['priv'] = $priv;
                    $details['email'] = $email;
                }
            }
        }
?>
        <form action="faculty_data.php" method="post">
            <div class="row">
                <div class="mb-4 row">
                    <label class="col-sm-5 col-form-label" for="name" style="font-weight: bold;">Name&emsp;:&emsp;</label>
                    <div class="col-sm-6">
                        <label for=""><?php echo $details['name']; ?></label>
                        <input type="hidden" name="name" value="<?php echo $details['name']; ?>">
                    </div>
                </div>  
                <div class="mb-4 row">
                    <label class="col-sm-5 col-form-label" for="user" style="font-weight: bold;">Username&emsp;:</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="user" id="user" required value="<?php echo $details['user']; ?>">
                    </div>
                </div>
                <div class="mb-4 row">
                    <label class="col-sm-5 col-form-label" for="pass" style="font-weight: bold;">Password&emsp;:&emsp;</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="pass" id="pass" required value="<?php echo $details['pass']; ?>">
                    </div>
                </div>
                <div class="mb-4 row">
                    <label class="col-sm-5 col-form-label" for="email" style="font-weight: bold;">Email&emsp;:&emsp;</label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" name="email" id="email" required value="<?php echo $details['email']; ?>">
                    </div>
                </div>
                <div class="mb-4 row">
                    <label class="col-sm-5 col-form-label" for="email" style="font-weight: bold;">Privileges&emsp;:&emsp;</label>
                    <div class="col-sm-3 form-check">
                        <input class="form-check-input" type="radio" name="priv" id="hod" <?php if($details['priv'] == 'hod') echo 'checked'; ?>>
                        <label class="form-check-label" for="hod">
                            HOD
                        </label>
                    </div>
                    <div class="col-sm-3 form-check">
                        <input class="form-check-input" type="radio" name="priv" id="staff" <?php if($details['priv'] == 'staff') echo 'checked'; ?>>
                        <label class="form-check-label" for="staff">
                            STAFF
                        </label>
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

