<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']=="admin"){
        require('header.php');
        
        // connection
        require_once("Operations.php");
        $opt = new Operations();
        
        // getting regulation
        $regulation = $opt->getRegulation();
        // adding subjects
        if(!empty($_POST['subject'])){
            $reg = $_POST['reg'];
            $year = $_POST['year'];
            $sem = $_POST['sem'];
            $sub = $_POST['subject'];
            $br_code = $_SESSION['br_code'];
            $branch = $_SESSION['branch'];
            $cr_code = "A";
            $opt->addSubject($reg, $year, $sem, $sub, $br_code, $branch, $cr_code);
        }
        
        // remove subject
        if(!empty($_POST['delsub'])){    
            $delsub = $_POST['delsub'];
            $opt->removeSubject($delsub);
        }

        // for partial add option
        if(isset($_POST['partialadd'])){
            $sub = $_POST['sub'];
            $reg = $_POST['reg'];
            $year = $_POST['year'];
            $sem = $_POST['sem'];
            $br_code = $_SESSION['br_code'];
            $stds = $_POST['std'];
            $msg = $opt->addPartialSubject($stds, $sub, $reg, $year, $sem, $br_code);
        }
            
?>
<div class="ms-2">
    <div class="row">
        <div class="col-4 mt-3" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 8;
                        require_once("menu.php");
                    ?>
            </div>
        </div>
        <div class="col-4">
            <div class="container text-center">
                <?php
                    echo "<h4>Selected Department: &emsp;";
                    if(!empty($_SESSION['branch']) && $_SESSION['branch']=="all"){
                        echo "None";
                    }else{
                        echo $_SESSION['branch'];
                    }
                    echo "</h4>";
                    if($msg == "parsuccess"){
                        echo '<br /><div class="alert alert-success" role="alert">Partial Student  Added..!</div>';
                    }else if($msg == "parfailure"){
                        echo '<br /><div class="alert alert-danger" role="alert">Partial Student  Not Added..!</div>';
                    }
                ?>
            </div>
            <div class="card cards content text-center mt-5" style="max-width:500px;">
                
                <div class="card-header" style="font-weight: bold;">Select The Following</div>
                <div class="card-body">
                    <form action="add_subjects.php" roll="form" method="post">
                        <div class="mb-3 row">
                            <label class="col-sm-6 col-form-label" style="font-weight: bold;" for="reg">Select Regulation&emsp;:&emsp;</label>
                            <div class="col-sm-6 ">
                                <select class="form-select text-center" name="reg" id="reg" required>
                                    <option value="">Select</option>
                                    <?php
                                        for($i=0;$i < sizeof($regulation);$i++){
                                            if(!empty($_COOKIE['sub_reg']) && $_COOKIE['sub_reg'] == $regulation[$i]){ 
                                                echo "<option value='".$regulation[$i]."' selected>".$regulation[$i]."</option>"; 
                                            }else{
                                                echo "<option value='".$regulation[$i]."'>".$regulation[$i]."</option>";
                                            }
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
                                    <option value="I" <?php if(!empty($_COOKIE['sub_year']) && $_COOKIE['sub_year'] == "I"){ echo 'selected'; } ?> >I</option>
                                    <option value="II" <?php if(!empty($_COOKIE['sub_year']) && $_COOKIE['sub_year'] == "II"){ echo 'selected'; } ?> >II</option>
                                    <option value="III" <?php if(!empty($_COOKIE['sub_year']) && $_COOKIE['sub_year'] == "III"){ echo 'selected'; } ?> >III</option>
                                    <option value="IV"<?php if(!empty($_COOKIE['sub_year']) && $_COOKIE['sub_year'] == "IV"){ echo 'selected'; } ?> >IV</option>    
                                </select>
                            </div>
                        </div>
                        <div class="mb-4 row">
                            <label class="col-sm-6 col-form-label" for="sem" style="font-weight: bold;">Select Semester&emsp;:&emsp;</label>
                            <div class="col-sm-6">
                                <select class="form-select text-center" name="sem" id="sem" required>
                                    <option value="">Select</option>
                                    <option value="I" <?php if(!empty($_COOKIE['sub_sem']) && $_COOKIE['sub_sem'] == "I"){ echo 'selected'; } ?> >I</option>
                                    <option value="II" <?php if(!empty($_COOKIE['sub_sem']) && $_COOKIE['sub_sem'] == "II"){ echo 'selected'; } ?> >II</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4 row">
                            <label class="col-sm-6 col-form-label" for="subject" style="font-weight: bold;">Subject Name&emsp;:&emsp;</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="subject" id="subject" required>
                            </div>
                        </div>
                        <div class="mb-4 row">
                            <button type="button" class="col-sm-12 btn btn-secondary sb-btn px-5 mb-2 addsubmodal" data-bs-toggle="modal" data-bs-target="#AddSubModal">Select Some</button>
                            <button type="submit" class="col-sm-12 btn btn-primary sb-btn px-5" name="addall">Select All</button>
                        </div>
                        
                    </form>
                    <!-- Selective Student  Modal -->
                    <div class="modal fade" id="AddSubModal" tabindex="-1" aria-labelledby="AddSubModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="AddSubModalLabel">Select Students</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="editout">
                                    
                                </div>
                            </div>
                        </div>  
                    </div>                
                </div>
            </div>
        </div>
        <div class="col-4 mt-4 pt-1">
            <div class="card cards content text-center mt-5" style="max-width:500px;">
            
                <div class="card-header" style="font-weight: bold;">Added Subjects</div>
                <div class="card-body" id="addedsub">

                </div>
                
            </div>
        </div>
        
    </div>
</div>
<script>
    $(document).ready(function(){
        document.cookie = "sub_reg="+$("#reg").val();
        document.cookie = "sub_year="+$("#year").val();
        document.cookie = "sub_sem="+$("#sem").val();
        if(1){
            var _reg = $("#reg").val();
            var _year = $("#year").val();
            var _sem = $("#sem").val();
            
            $.ajax({
                url:"config/addedsubjects.php",
                method:"POST",
                data:{reg:_reg, year:_year, sem:_sem},
                dataType:"text",
                success:function(data){
                    $("#addedsub").html(data);
                }
            });
        }
        else{
            $("#addedsub").html("");
        }
        $("#reg").change(function(){
            document.cookie = "sub_reg="+$("#reg").val();
            $("#year").val('');
            $("#sem").val('');
        });
        $("#year").change(function(){
            document.cookie = "sub_year="+$("#year").val();
            $("#sem").val('');
        });
        $("#sem").change(function(){
            document.cookie = "sub_sem="+$("#sem").val();
            var _reg = $("#reg").val();
            var _year = $("#year").val();
            var _sem = $("#sem").val();
            $.ajax({
                url:"config/addedsubjects.php",
                method:"POST",
                data:{reg:_reg, year:_year, sem:_sem},
                dataType:"text",
                success:function(data){
                    $("#addedsub").html(data);
                }
            });
        });
        
        // for partial add subject modal
        $('.addsubmodal').on('click', function (e) {
            var _reg = $('#reg').val();
            var _year = $('#year').val();
            var _sem = $('#sem').val();
            var _sub = $("#subject").val();
            $.ajax({
                url:"config/stdlist.php",
                method:"POST",
                data:{reg:_reg, year:_year, sem:_sem, subject:_sub},
                dataType:"text",
                success:function(data){
                    $('#editout').html(data);
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