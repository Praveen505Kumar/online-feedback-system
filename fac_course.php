<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && ($_SESSION['priv']="hod" || $_SESSION['priv']="admin")){
        require('header.php');

        // connection
        require("Operations.php");
        $opt = new Operations();

        // getting regulation
        $regulation = $opt->getRegulation();

        // faculty and subject mapping add button
        if(!empty($_POST['regulation']) && !empty($_POST['year']) && !empty($_POST['sem']) && !empty($_POST['subject']) && !empty($_POST['faculty']) ){
                            
            $reg = $_POST['regulation'];
            $year = $_POST['year'];
            $sem = $_POST['sem'];
            $sub = $_POST['subject'];
            $faculty = $_POST['faculty'];
            $cr_code = 'A';
            $branch = $_SESSION['branch'];
            $br_code = $_SESSION['br_code'];

            $msg = $opt->facSubjectMap($reg, $year, $sem, $sub, $br_code, $branch, $cr_code, $faculty);
        }
        // deleting fac_subject mapping
        if(!empty($_POST['delsubfac']) && !empty($_POST['subfacid'])){
            $subfacid = $_POST['subfacid'];
            $msg = $opt->deleteFacSubjectMap($subfacid);
        }
        
?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 9;
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
            <div class="card cards content text-center mt-5 mb-0" style="max-width:500px;">
                
                <div class="card-header" style="font-weight: bold;">Select The Following</div>
                <div class="card-body">
                    <form action="fac_course.php" roll="form" method="POST">
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
                                <label class="col-sm-6 col-form-label" for="subject" style="font-weight: bold;">Select Subject&emsp;:&emsp;</label>
                                <div class="col-sm-6">
                                    <select class="form-select text-center" name="subject" id="subject" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4 row">
                                <label class="col-sm-6 col-form-label" for="faculty" style="font-weight: bold;">Select Faculty&emsp;:&emsp;</label>
                                <div class="col-sm-6">
                                    <select class="form-select" name="faculty" id="faculty" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary sb-btn px-5 ">ADD</button>
                    </form>
                </div>
            </div>
            <div class="" style="max-width=800px;">
                <div class="card text-center mt-4" >
                    <div class="card-header" style="font-weight: bold;">Added Subjects</div>
                    <div class="card-body" id="addedsub">
                        <?php
                            if($msg=="delsuccess"){
                                echo '<div class="alert alert-warning">
                                                Success..! Subject - Faculty Deleted 
                                              </div>';
                            }
                            if($msg == "addsuccess"){
                                echo '<div class="alert alert-success">Success..! mapping done </div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>
<script>
    $(document).ready(function(){
        $('#sem').change(function(){
            var _reg = $("#reg").val();
            var _year = $("#year").val();
            var _sem = $("#sem").val();

            // added fac_course details
            $.ajax({
                url:"config/ajgetsubfac.php",
                method:"POST",
                data:{reg:_reg, year:_year, sem:_sem},
                dataType:"text",
                success:function(data){
                    $("#addedsub").html(data);
                }
            });

            // faculty details
            $.ajax({
                url:"config/ajaxfaculty.php",
                method:"POST",
                data:{},
                dataType:"text",
                success:function(data){
                    $("#faculty").html(data);
                }
            });

            //subject details
            $.ajax({
                url:"config/ajaxsubject.php",
                method:"POST",
                data:{reg:_reg, year:_year, sem:_sem},
                dataType:"text",
                success:function(data){
                    $("#subject").html(data);
                }
            });
        });
        

    });

</script>

<?php 
        require('footer.php');
    }
    else{
        header('Location: index.php');
    }
?>