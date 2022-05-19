<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv'])&& ($_SESSION['priv'] == "hod" || $_SESSION['priv'] == "admin")){
        require('header.php');

        // connection
        require("Operations.php");
        $opt = new Operations();

        //getting faculties list
        $br_code = $_SESSION['br_code'];
        $faculties = $opt->getFaultyList($br_code);
        
?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        if($_SESSION['priv'] == "admin"){
                            $menu_id = 11;
                            require_once("menu.php");
                        }else{
                            $menu_id = 3;
                            require_once("hodmenu.php");
                        }
                    ?>
            </div>
        </div>
        <div class="col-7 mx-5 my-2">
            <div class="container text-center">
                <?php
                    if($_SESSION['priv'] == "admin"){
                        echo "<h4>Selected Department: &emsp;";
                        if(!empty($_SESSION['branch']) && $_SESSION['branch']=="all"){
                            echo "None";
                        }else{
                            echo $_SESSION['branch'];
                        }
                        echo "</h4>";
                    }
                ?>
            </div>
            
            <div class="card cards content text-center" style="max-width:500px;">
                
                <div class="card-header" style="font-weight: bold;">Select The Following</div>
                <div class="card-body">
                    <form action="report.php" method="POST">
                        <div class="mb-3 row">
                            <label class="col-sm-5 col-form-label" for="fac" style="font-weight: bold;">Select Faculty Name&emsp;: &emsp;</label>
                            <div class="col-sm-6">
                                <select class="form-select" name="facname" id="fac" required>
                                    <option value="">Select Faculty</option>
                                    <?php
                                        for($i=0;$i < sizeof($faculties);$i++){
                                            echo "<option value='".$faculties[$i]."'>".$faculties[$i]."</option>";
                                        }
                                    ?>       
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-5 col-form-label" for="sub" style="font-weight: bold;">Select Subject&emsp;:&emsp;</label>
                            <div class="col-sm-6">
                                <select class="form-select" name="subject" id="sub" required>
                                    <option value="">Select Subject</option>
                                           
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary sb-btn px-5 mt-3">Get Feedback</button>
                    </form>
                </div>
                
            </div>
    
        </div>
        
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#fac").change(function(){
            var faculty = $("#fac").val();
            $.ajax({
                url:"config/ajaxpost.php",
                method:"POST",
                data:{fac_id:faculty},
                dataType:"text",
                success:function(data){
                    $("#sub").html(data);
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