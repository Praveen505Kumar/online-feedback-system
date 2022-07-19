<?php 
    @session_start();
    date_default_timezone_set("Asia/Kolkata");
    
    require('header.php');
    if(!empty($_POST['username']) && !empty($_POST['password'])){
        $username = strtoupper($_POST['username']);
        $password = strtoupper($_POST['password']);
        $usertype = $_POST['usertype'];
        
        require("Operations.php");
        $opt = new Operations();

        if($usertype == "student"){
            $res = $opt->checkStudentLogin($username, $password);
            
            if($res['status'] == 'success'){
                // get feedbacks by year and sem
                $br_code = $res['br_code'];
                $year = $res['year'];
                $sem = $res['sem'];
                $reg = $res['reg'];
                $_SESSION['priv'] = $res['priv'];
                $_SESSION['user'] = $res['user'];
                $_SESSION['year'] = $res['year'];
                $_SESSION['sem'] = $res['sem'];
                $_SESSION['reg'] = $res['reg'];
                $_SESSION['br_code'] = $res['br_code'];
                $_SESSION['roll'] = $username;
                $today = date("Y-m-d\TH:i", time());
                $feedbacks = $opt->getActiveFeedbackByStudent($br_code, $year, $sem, $reg);
                $temp = 0;
                foreach($feedbacks as $feedback){
                    if(!empty($feedback['from_date']) && !empty($feedback['to_date']) && $feedback['from_date'] <= $today && $today <= $feedback['to_date']){
                        header('Location: student.php');
                        $temp = 1;
                        break;
                    }
                }
                if($temp == 0){
                    session_unset();
                    echo '<script type="text/javascript">
                                alert("Feedback is NOT active for your class..!"); 
                                document.location.href = "index.php";
                        </script>';
                }
            }else{
                echo '<script type="text/javascript">
                        alert("Invalid Username or Password..!"); 
                        document.location.href = "index.php";
                    </script>';
            }
        }
        else if($usertype == "staff"){
            $res = $opt->checkStaffLogin($username, $password);

            if($res['status'] == 'success'){
                // get feedbacks by year and sem
                $br_code = $res['br_code'];
                $_SESSION['priv'] = $res['priv'];
                $_SESSION['user'] = $res['user'];
                $_SESSION['br_code'] = $res['br_code'];
                $_SESSION['branch'] = $res['branch'];
                if($res['priv'] == 'admin'){
                    header('Location: admin.php');
                }
                else if($res['priv'] == 'hod'){
                    header('Location: hod.php');
                }else if($res['priv'] == 'staff'){
                    header('Location: faculty.php');
                }else{
                    echo '<script type="text/javascript">
                        alert("Invalid Privilege!"); 
                        document.location.href = "index.php";
                    </script>';
                }
            }else{
                echo '<script type="text/javascript">
                        alert("Invalid Username or Password..!"); 
                        document.location.href = "index.php";
                    </script>';
            }
        }
    }
?>

<div class="container names">
    <div class="row content">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-4 d-flex justify-content-center">
            <div class="card text-center cards">
                <div class="card-header">
                    Student Login 
                </div>
                <div class="card-body">
                    <form action="index.php" method="post">
                        <div class="mb-3 input-group">
                            <label for="uname" class="names">Username</label>
                            <div class="input-group">
                                <span class="input-group-text" id="adminnumber">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                    </svg>
                                </span>
                                <input type="hidden" name="usertype" value="student">
                                <input type="text" class="form-control" id="uname" name="username" placeholder="XXXXXXXXXX" aria-describedby="adminnumber" required>
                            </div>
                        </div>
                        <div class="mb-3 input-group">
                            <label for="pass" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text" id="adminnumber">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                    </svg>
                                </span>
                                <input type="password" class="form-control" id="pass" name="password" placeholder="*********" aria-describedby="adminnumber" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-outline-dark sb-btn">Submit</button>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="#">Forgot Password?</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex justify-content-center align-items-center">
            <div class="card text-center cards">
                <div class="card-header">
                Staff/Admin Login
                </div>
                <div class="card-body">
                    <form action="index.php" method="POST">
                        <div class="mb-3 input-group">
                            <label for="uname" >Username</label>
                            <div class="input-group">
                                <span class="input-group-text" id="adminnumber">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                    </svg>
                                </span>
                                <input type="hidden" name="usertype" value="staff">
                                <input type="text" class="form-control" id="uname" placeholder="XXXXXXXX" name="username" aria-describedby="adminnumber" required>
                            </div>
                        </div>
                        <div class="mb-3 input-group">
                            <label for="pass" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text" id="adminnumber">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                    </svg>
                                </span>
                                <input type="password" class="form-control" id="pass" name="password" placeholder="********" aria-describedby="adminnumber" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-outline-dark sb-btn">Submit</button>
                    </form>
                </div>
                <div class="card-footer text-muted card-link">
                    <a href="#">Forgot Password?</a>
                </div>
            </div>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>   
    
</div>


<?php 
    require('footer.php');
?>