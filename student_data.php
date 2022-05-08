<?php 
    @session_start();
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']=="admin"){
        require('header.php');

        // connection
        require("Operations.php");
        $opt = new Operations();

        // getting regulation
        $regulation = $opt->getRegulation();

        // deleting student details
        if(!empty($_POST['delstd'])){
            $student = $_POST['delstd'];
            $msg = $opt->deleteStudent($student);
        }

        // adding student details
        if(isset($_POST['setbutton'])){
            $name = $_POST['name'];
            $roll = $_POST['roll'];
            $reg = $_COOKIE['reg'];
            $year = $_COOKIE['year'];
            $sem = $_COOKIE['sem'];
            $msg = $opt->setStudent($name, $roll, $reg, $year, $sem);
        }

        // updating student details
        if(isset($_POST['updatebutton'])){
            $name = $_POST['name'];
            $roll = $_POST['roll'];
            $pass = $_POST['pass'];
            $msg = $opt->updateStudent($name, $roll, $pass);
        }
        
        //getting student list
        if(isset($_POST['show'])){
            $reg = $_POST['regulation'];
            $year = $_POST['year'];
            $sem = $_POST['sem'];
            setcookie("reg", $reg);
            setcookie("year", $year);
            setcookie("sem", $sem);
            $br_code = $_SESSION['br_code'];
            $students = $opt->getStudentDetails($reg, $year, $sem, $br_code);
        }


?>
<div class="container ms-0">
    <div class="row">
        <div class="col-5 mt-3 me-5" style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 15;
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
                    if($msg == "setsuccess"){
                        echo '<br /><div class="alert alert-success" role="alert">Student Details Added..!</div>';
                    }
                    else if($msg == "setfailure"){
                        echo '<br /><div class="alert alert-danger" role="alert">Student Details Not Added..!</div>';
                    }else if($msg == "updatesuccess"){
                        echo '<br /><div class="alert alert-success" role="alert">Student Details Updated..!</div>';
                    }else if($msg == "updatefailure"){
                        echo '<br /><div class="alert alert-danger" role="alert">Student Details Not Updated..!</div>';
                    }else if($msg == "delsuccess"){
                        echo '<br /><div class="alert alert-success" role="alert">Student Details Deleted..!</div>';
                    }else if($msg == "delfailure"){
                        echo '<br /><div class="alert alert-danger" role="alert">Student Details Not Deleted..!</div>';
                    }
                ?>
            </div>
            <div class="">
                <form action="" method="post">
                    <div class="mb-4 row">
                        <label class="col-auto col-form-label" style="font-weight: bold;" for="regulation">Regulation:</label>
                        <div class="col-auto ">
                            <select class="form-select text-center" name="regulation" id="regulation" required>
                                <option value="">Select</option>
                                <?php
                                    for($i=0;$i < sizeof($regulation);$i++){
                                        if(!empty($_COOKIE['reg']) && $_COOKIE['reg'] == $regulation[$i]){ 
                                            echo "<option value='".$regulation[$i]."' selected>".$regulation[$i]."</option>"; 
                                        }else{
                                            echo "<option value='".$regulation[$i]."'>".$regulation[$i]."</option>";
                                        }
                                    }
                                ?>        
                            </select>
                        </div>
                        <label class="col-auto col-form-label" style="font-weight: bold;" for="year" required>Year:</label>
                        <div class="col-auto">
                            <select class="form-select text-center" name="year" id="year" required>
                                <option value="">Select</option>
                                <option value="I" <?php if(!empty($_COOKIE['year']) && $_COOKIE['year'] == "I"){ echo 'selected'; } ?> >I</option>
                                <option value="II" <?php if(!empty($_COOKIE['year']) && $_COOKIE['year'] == "II"){ echo 'selected'; } ?> >II</option>
                                <option value="III" <?php if(!empty($_COOKIE['year']) && $_COOKIE['year'] == "III"){ echo 'selected'; } ?> >III</option>
                                <option value="IV" <?php if(!empty($_COOKIE['year']) && $_COOKIE['year'] == "IV"){ echo 'selected'; } ?> >IV</option>    
                            </select>
                        </div>
                        <label class="col-auto col-form-label" for="sem" style="font-weight: bold;" required>Semester:</label>
                        <div class="col-auto">
                            <select class="form-select text-center" name="sem" id="sem" required>
                                <option value="">Select</option>
                                <option value="I" <?php if(!empty($_COOKIE['sem']) && $_COOKIE['sem'] == "I"){ echo 'selected'; } ?> >I</option>
                                <option value="II" <?php if(!empty($_COOKIE['sem']) && $_COOKIE['sem'] == "II"){ echo 'selected'; } ?> >II</option>
                            </select>
                        </div>
                        <input type="submit" class="col-auto btn btn-primary" name="show" value="SHOW">
                    </div>
                </form>
                
                <div class="row">
                    <table class="table table-success table-hover  border-success text-center">
                        <thead>
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col">Roll No.</th>
                                <th scope="col">Name</th>
                                <th scope="col">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                for($i=0;$i<sizeof($students);$i++) { ?>
                                    <tr>
                                        <th scope="row"><?php echo $i+1; ?></th>
                                        <td><?php echo $students[$i]['roll']; ?></td>
                                        <td><?php echo $students[$i]['name']; ?></td>
                                        <td>
                                            <div class="row justify-content-start">
                                                <div class="col-auto">
                                                    <input type="hidden" id="std" value="<?php echo $students[$i]['roll']; ?>"/>
                                                    <button  type="submit" class="btn btn-sm btn-primary px-3 editmodal" data-bs-toggle="modal" data-bs-target="#EditModal">Edit</button>
                                                </div>
                                                <div class="col-auto">
                                                    <input type="hidden" id="std" value="<?php echo $students[$i]['roll']; ?>"/>
                                                    <button class="btn btn-sm btn-danger px-3 deletemodal" data-bs-toggle="modal" data-bs-target="#DeleteModal">Delete</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <button  type="button" class="btn btn-lg btn-primary px-3" data-bs-toggle="modal" data-bs-target="#AddModal">Add</button>
                </div>

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
                                <form action="student_data.php" method="POST">
                                    <input type="hidden" id="delstd" name="delstd" />
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
                                <form action="student_data.php" method="POST">
                                    <div class="row">
                                        <div class="mb-4 row">
                                            <label class="col-sm-5 col-form-label" for="roll" style="font-weight: bold;">ROll No&emsp;:&emsp;</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="roll" id="roll" required>
                                            </div>
                                        </div>  
                                        <div class="mb-4 row">
                                            <label class="col-sm-5 col-form-label" for="name" style="font-weight: bold;">Name&emsp;:</label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="name" id="name" required>
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
            var student = $(this).prev('#std').val();
            $('#delstd').val(student);
            $('#delout').html(student);
        });
        
        // for edit modal
        $('.editmodal').on('click', function (e) {
            var student = $(this).prev('#std').val();
            $.ajax({
                url:"config/stdetails.php",
                method:"POST",
                data:{std:student},
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