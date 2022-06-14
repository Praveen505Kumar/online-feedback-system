<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']=="admin"){
        require('header.php');

        // connection
        require("Operations.php");
        $opt = new Operations();

        // deleting faculty details
        if(!empty($_POST['delfac'])){
            $faculty = $_POST['delfac'];
            
            $msg = $opt->deleteFaculty($faculty);
        }

        // adding faculty details
        if(isset($_POST['setbutton'])){
            $name = $_POST['name'];
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $email = $_POST['email'];
            $priv = $_POST['priv'];
            $msg = $opt->setFaculty($name, $user, $pass, $email, $priv);
        }
        if(isset($_POST['updatebutton'])){
            $name = $_POST['name'];
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $email = $_POST['email'];
            $priv = $_POST['priv'];
            $msg = $opt->updateFaculty($name, $user, $pass, $email, $priv);
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
                        $menu_id = 14;
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
                        echo $_SESSION['branch'] ;
                    }
                    echo "</h4>";
                    if($msg == "setsuccess"){
                        echo '<br /><div class="alert alert-success" role="alert">Faculty Details Added..!</div>';
                    }
                    else if($msg == "setfailure"){
                        echo '<br /><div class="alert alert-danger" role="alert">Faculty Details Not Added..!</div>';
                    }else if($msg == "updatesuccess"){
                        echo '<br /><div class="alert alert-danger" role="alert">Faculty Details Updated..!</div>';
                    }else if($msg == "updatefailure"){
                        echo '<br /><div class="alert alert-danger" role="alert">Faculty Details Not Updated..!</div>';
                    }else if($msg == "delsuccess"){
                        echo '<br /><div class="alert alert-danger" role="alert">Faculty Details Deleted..!</div>';
                    }else if($msg == "delfailure"){
                        echo '<br /><div class="alert alert-danger" role="alert">Faculty Details Not Deleted..!</div>';
                    }
                ?>
            </div>
            <div class="mt-5">
                <table class="table table-light table-hover  border-success text-center">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for($i=0;$i<sizeof($faculties);$i++) { ?>
                                <tr>
                                    <th scope="row"><?php echo $i+1; ?></th>
                                    <td><?php echo $faculties[$i]; ?></td>
                                    <td>
                                        <div class="row justify-content-center">
                                            <div class="col-3">
                                                <input type="hidden"class="fac" value="<?php echo $faculties[$i]; ?>"/>
                                                <button  type="submit" class="btn btn-sm btn-primary px-3 editmodal" data-bs-toggle="modal" data-bs-target="#EditModal">Edit</button>
                                            </div>
                                            <div class="col-3">
                                                <input type="hidden"class="fac" value="<?php echo $faculties[$i]; ?>"/>
                                                <button class="btn btn-sm btn-danger px-3 deletemodal" data-bs-toggle="modal" data-bs-target="#DeleteModal">Delete</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <button  type="button" class="btn btn-lg btn-primary px-3" data-bs-toggle="modal" data-bs-target="#AddModal">Add</button>

                <!-- Delete Modal -->
                <div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="DeleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="DeleteModalLabel">Confirmation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                               <h4>Are you sure?</h4>
                               <h5 id="delout" class="text-center"></h5>
                            </div>
                            <div class="modal-footer">
                                <form action="faculty_data.php" method="POST">
                                    <input type="hidden" id="delfac" name="delfac" />
                                    <button type="submit" class="btn btn-danger">DELETE</button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>  
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="EditModalLabel">Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="editout">
                               
                            </div>
                        </div>
                    </div>  
                </div>

                <!-- Add  Modal -->
                <div class="modal fade" id="AddModal" tabindex="-1" aria-labelledby="AddModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="AddModalLabel">Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="editout">
                                <form action="faculty_data.php" method="POST">
                                    <div class="row">
                                        <div class="mb-4 row">
                                            <label class="col-sm-5 col-form-label" for="name" style="font-weight: bold;">Name&emsp;:&emsp;</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="name" id="name" required>
                                            </div>
                                        </div>  
                                        <div class="mb-4 row">
                                            <label class="col-sm-5 col-form-label" for="user" style="font-weight: bold;">Username&emsp;:</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="user" id="user" required>
                                            </div>
                                        </div>
                                        <div class="mb-4 row">
                                            <label class="col-sm-5 col-form-label" for="pass" style="font-weight: bold;">Password&emsp;:&emsp;</label>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control" name="pass" id="pass" required>
                                            </div>
                                        </div>
                                        <div class="mb-4 row">
                                            <label class="col-sm-5 col-form-label" for="email" style="font-weight: bold;">Email&emsp;:&emsp;</label>
                                            <div class="col-sm-6">
                                                <input type="email" class="form-control" name="email" id="email" required>
                                            </div>
                                        </div>
                                        <div class="mb-4 row">
                                            <label class="col-sm-5 col-form-label" for="email" style="font-weight: bold;">Privileges&emsp;:&emsp;</label>
                                            <div class="col-sm-3 form-check">
                                                <input class="form-check-input" type="radio" name="priv" id="hod" value="hod">
                                                <label class="form-check-label" for="hod">
                                                    HOD
                                                </label>
                                            </div>
                                            <div class="col-sm-3 form-check">
                                                <input class="form-check-input" type="radio" name="priv" id="staff" value="staff" checked>
                                                <label class="form-check-label" for="staff">
                                                    STAFF
                                                </label>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row justify-content-around">
                                        <button type="submit" class="col-5 btn btn-primary" name="setbutton">ADD</button>
                                        <button type="button" class="col-5 btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>  
                </div>
            </div> 
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.deletemodal').on('click', function (e) {
            var faculty = $(this).prev('.fac').val();
            $('#delfac').val(faculty);
            $('#delout').html(faculty);
        });
        // for edit modal
        $('.editmodal').on('click', function (e) {
            var faculty = $(this).prev('.fac').val();
            $.ajax({
                url:"config/facdetails.php",
                method:"POST",
                data:{fac:faculty},
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