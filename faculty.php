<?php
    @session_start();
	if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']=="staff"){
	    require('header.php');
        
?>
<div class="ms-2">
    <div class="row ">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 1;
                        require_once("facmenu.php");
                    ?>
            </div>
        </div>
        <div class="col-6 mx-5 my-2">
            <div class="container text-center">
                <h4 class="p-4">Welcome <?php echo $_SESSION['user']; ?> </h4>
                
            </div>
            
            <div class="card cards content text-center" style="max-width:400px;">
                <div class="card-header">
                    <label for="uname" class="names">Select The Following</label>
                </div>
                <div class="card-body">
                    <form action="report.php" method="post">
                        <div class="mb-3 row">
                            <input type='hidden' name='user' id="user" value='<?php echo $_SESSION['user']; ?>' />
                            <label class="col-sm-5 col-form-label" for="subject" style="font-weight: bold;">Select Subject: &emsp;</label>
                            <div class="col-sm-6">
                                <select class="form-select" name="subject" id="subject" required>
                                    <option value="">--Select--</option>
                                    <?php
                                        
                                    ?>       
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary sb-btn">GET FEEDBACK</button>
                    </form>
                </div>
                
            </div>
        
        </div>
        
    </div>
</div>
<script>
    $(document).ready(function(){
        var faculty = $('#user').val();
        $.ajax({
            url:"config/ajaxpost.php",
            method:"POST",
            data:{fac_id:faculty},
            dataType:"text",
            success:function(data){
                $("#subject").html(data);
            }
        });
    });
</script>

<?php 
        require('footer.php');
    }else{
        header('Location: index.php');
    }
?>



