<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && ($_SESSION['priv']="hod" || $_SESSION['priv']="admin")){
        require('header.php');
        if($_SESSION['user']=="admin" || strtolower($_SESSION['user'])=="administrator"){
            require("config/db_connect.php");
            // getting regulations
            if($stmt = $conn->prepare("SELECT DISTINCT `regulation` FROM `subjects_2`;")){
                if($stmt->execute()){
                    $stmt->bind_result($reg);
                    $i=0;
                    $regulation = array();
                    while($stmt->fetch()){
                        $regulation[$i]=$reg;
                        $i++;
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
            <div class="container text-center">
                <?php
                    if(!empty($_GET['msg']) && $_GET['msg']=='feedback_activated'){
                        echo "<div class='alert alert-success'>Feedback Activated Successfully</div>";
                    }
                    else if(!empty($_GET['msg']) && $_GET['msg']=='feedback_not_activated'){
                        echo "<div class='alert alert-danger'>Feedback Not Activated..!</div>";
                    }else if(!empty($_GET['msg']) && $_GET['msg']=='feedback_exists'){
                        echo "<div class='alert alert-warning'>Feedback Exists..!</div>";
                    }else if(!empty($_GET['msg']) && $_GET['msg']=='start_end_time_error'){
                        echo "<div class='alert alert-warning'>From DateTime Value > To DateTime Value..!</div>";
                    }else if(!empty($_GET['msg']) && $_GET['msg']=='end_time_error'){
                        echo "<div class='alert alert-warning'>To DateTime Value is Invalid/Expired..!</div>";
                    }
                ?>
            </div>
            <div class="card cards content text-center mt-5" style="max-width:500px;">
                
                <div class="card-header" style="font-weight: bold;">Select The Following</div>
                <div class="card-body">
                    <form action="active.php" roll="form" method="post">
                        <div class="mb-3">
                            <div class="mb-3 row">
                                <label class="col-sm-6 col-form-label" style="font-weight: bold;" for="reg">Select Regulation&emsp;:&emsp;</label>
                                <div class="col-sm-6 ">
                                    <select class="form-select text-center" name="regulation" id="reg" required>
                                        <option value="">Select</option>
                                        <?php
                                            for($i=0;$i < sizeof($regulation);$i++){
                                                echo "<option value='".$regulation[$i]."'>".$regulation[$i]."</option>";
                                            }
                                        ?>        
                                </select>
                                </div>
                            </div>
                            <div class="mb-4 row">
                                <label class="col-sm-6 col-form-label" style="font-weight: bold;" for="year" >Select Year&emsp;:&emsp;</label>
                                <div class="col-sm-6">
                                    <select class="form-select text-center" name="year" id="year" required>
                                        <option value="">Select</option>
                                        <option value="I">I</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                        <option value="IV">IV</option>    
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4 row">
                                <label class="col-sm-6 col-form-label" for="sem" style="font-weight: bold;">Select Semester&emsp;:&emsp;</label>
                                <div class="col-sm-6">
                                    <select class="form-select text-center" name="sem" id="sem" required>
                                        <option value="">Select</option>
                                        <option value="I">I</option>
                                        <option value="II">II</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4 row">
                                <label class="col-sm-6 col-form-label" for="fdtime" style="font-weight: bold;">Select Feedback Time&emsp;:&emsp;</label>
                                <div class="col-sm-6">
                                    <select class="form-select text-center" name="fdtime" id="fdtime" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary sb-btn px-5">Get Report</button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#reg").change(function(){
            $("#year").val('');
            $("#sem").val('');
        });
        $("#year").change(function(){
            $("#sem").val('');
        });
        $("#sem").change(function(){
            var _reg = $("#reg").val();
            var _year = $("#year").val();
            var _sem = $("#sem").val();
            $.ajax({
                url:"config/ajaxreport.php",
                method:"POST",
                data:{reg:_reg, year:_year, sem:_sem},
                dataType:"text",
                success:function(data){
                    $("#fdtime").html(data);
                }
            });
        });
    });
</script>

<?php 
        require('footer.php');
    }
?>