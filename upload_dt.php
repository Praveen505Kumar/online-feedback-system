<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv'] == "admin"){
        require('header.php');

        
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
                    
                    <div class="card-header" style="font-weight: bold;">Import Students Details</div>
                    <div class="card-body">
                        <div class="mb-3 px-4">
                            <input class="form-control form-control-lg" type="file" accept=".csv">
                        </div>
                        <button class="btn btn-md btn-success btn-block px-5" type="submit">Import</button>
                    </div>
                    
                </div>
                <div class="card cards content text-center m-0 p-0" style="max-width:400px;">
                    
                    <div class="card-header" style="font-weight: bold;">Import Faculty Details</div>
                    <div class="card-body">
                        <div class="mb-3 px-4">
                            <input class="form-control form-control-lg" type="file" accept=".csv">
                        </div>
                        <button class="btn btn-md btn-success btn-block px-5"type="submit">Import</button>
                    </div>
                    
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