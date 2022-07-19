<?php
    @session_start();
	if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']=="student"){
	    require('header.php'); 
        //print_r($_SESSION['yes_sub']);
        //$branches = array("05"=>"CSE", "01"=>"CIVIL", "02"=>"EEE", "04"=>"ECE", "03"=>"MECH", "07"=>"FDT");
        // connection
        require("Operations.php");
        $opt = new Operations();
        $branches = $opt->branchBrCodesMap();
        $questions = array();
        $questions[0] = "Teacher comes to the class on time";
        $questions[1] = "Teacher speaks clearly and audibly";
        $questions[2] = "Teacher plans lesson with clear objective";
        $questions[3] = "Teacher has got command on the subject";
        $questions[4] = "Teacher writes and draws legibly";
        $questions[5] = "Teacher asks qstions to promote interaction and effective thinking";
        $questions[6] = "Teacher encourages,compliments and praises originality and creativity displayed by the student";
        $questions[7] = "Teacher is courteous and impartial in dealing with the students";
        $questions[8] = "Teacher covers the syllabus completely";
        $questions[9] = "Teacher evaluation of the sessional exams answer scripts,lab records etc is fair and impartial";
        $questions[10] = "Teacher is prompt in valuing and returning the answer scripts providing feedback on performanc";
        $questions[11] = "Teacher offers assistance and counseling to the needy students";
        $questions[12] = "Teacher imparts the practical knowledge concerned to the subject";
        $questions[13] = "Teacher leaves the class on time";
?>
<div class="ms-2">
    <div class="row ">
        <div class="col-5 mt-3 me-5 " style="max-width:400px;">
            <div class="list-group">
                    <?php
                        $menu_id = 1;
                        require_once("stdmenu.php");
                    ?>
            </div>
        </div>
        <div class="col-6" style="width:65%;">
            <div class="container text-center border-bottom border-primary">
                <h4 class="p-4">Welcome <?php echo $_SESSION['user']; ?> </h4>
            </div>
            <div class="bg-white p-4 border border-5 border-success rounded">
                <div class="row">
                    <div class="col-xs-12 col-sm-2">
                        <img src="images/jntuacek.png" alt="JNTUACEA" width="100px" height="100px" style="margin:10px;" />
                    </div>
                    <div class="col-xs-12 col-sm-10 text-center">
                        <br/><span style="font-size:1.2em;">JAWAHARLAL NEHRU TECHNOLOGICAL UNIVERSITY ANANTAPUR</span><br/>
                        <span style="font-size:1.2em;">COLLEGE OF ENGINEERING, KALIKIRI</span><br/>
                        <span style="font-size:1em; padding-top:10px;">ANDHRA PRADESH, INDIA - 515002</span><br/>
                        <br/>
                        <span style="font-size:1.4em; margin-bottom:12px; letter-spacing: 3px;">FEEDBACK FORM</span><br/>
                    </div>
                    <input type="hidden" name="roll" id="roll" value="<?php echo $_SESSION['roll']; ?>">
				</div>
                <div class="row">
                    <form action="feedform.php" method="POST">
                        <table style="width:100%">
                            <tbody>
                                <tr>
                                    <td>Subject Name : 
                                        <select class="form-select-md" name="subname" id="subname" required>
                                            <option value="">Select</option>
                                        </select>
                                    </td>
                                    <td>Class : <?php echo $_SESSION['year'].' B.TECH '.$_SESSION['sem'];?></td>
                                </tr>
                                <tr>
                                    <td>Faculty Name : <span id="facnameshow"></span>
                                        <input type="hidden" name="facname" id="facname">
                                    </td>
                                    <td>Branch : <?php echo $branches[$_SESSION['br_code']];?> </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-secondary table-hover  border-success">
                            <thead>
                                <tr>
                                    <th scope="col">S.No</th>
                                    <th scope="col" class="text-center">Question</th>
                                    <th scope="col">Excellent</th>
                                    <th scope="col">Very Good</th>
                                    <th scope="col">Good</th>
                                    <th scope="col">Average</th>
                                    <th scope="col">Poor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for($i=0;$i<sizeof($questions);$i++) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $i+1; ?></th>
                                            <td><?php echo $questions[$i]; ?></td>
                                            <td><input type='radio' required="required" class="form-check-input" name='<?php echo "q".($i+1);?>' value='10'></td>
                                            <td><input type='radio' required="required" class="form-check-input" name='<?php echo "q".($i+1);?>' value='8'></td>
                                            <td><input type='radio' required="required" class="form-check-input" name='<?php echo "q".($i+1);?>' value='6'></td>
                                            <td><input type='radio' required="required" class="form-check-input" name='<?php echo "q".($i+1);?>' value='4'></td>
                                            <td><input type='radio' required="required" class="form-check-input" name='<?php echo "q".($i+1);?>' value='2'></td>
                                        </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="form-floating">
                            <textarea name="cmnt" class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                            <label for="floatingTextarea2">Comments</label>
                        </div>
                        <div class="row justify-content-center pt-2">
                            <input type="submit" class="col-auto btn btn-lg btn-primary" value="Submit">
                        </div>
                        
                    </form>
                </div>
            </div>
        
        </div>
        
    </div>
</div>

<script>
    $(document).ready(function(){
        var rollno = $('#roll').val();
        $.ajax({
            url:"config/addedsubjects.php",
            method:"POST",
            data:{roll:rollno},
            dataType:"text",
            success:function(data){
                $("#subname").html(data);
            }
        });
        $("#subname").change(function(){
            var _subnmae = $("#subname").val();
            $.ajax({
                url:"config/getfaculty.php",
                method:"POST",
                data:{subname:_subnmae},
                dataType:"text",
                success:function(data){
                    $("#facnameshow").html(data);
                    $("#facname").val(data);
                }
            });
        });
    });
</script>
<?php 
        require('footer.php');
    }else{
        header('Location: index.php');
    }
?>



