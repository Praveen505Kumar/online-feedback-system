<?php
    @session_start();
    date_default_timezone_set("Asia/Kolkata");
    require("database.php");
    
    class Operations extends Database{
        private $my_conn = "";
        private $my_error = 0;

        function __construct(){
            $this->my_conn = new mysqli($this->getHost(), $this->getUserName(), $this->getPassword(), $this->getDBName());
            
            if (mysqli_connect_errno()) {
                $this->my_error = mysqli_connect_errno();
            }
        }

        function checkLogin($username, $password){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("SELECT `privilege`, `br_code`, `branch`, `fname` FROM `fac_login` WHERE `fuser`=? AND `fpass`=?;")){
                    $stmt->bind_param("ss", $username, $password);
                    if($stmt->execute()){
                        $stmt->bind_result($priv, $br_code, $branch, $facname);
                        while($stmt->fetch()){
                            $_SESSION['priv']= $priv;
                            $_SESSION['br_code']= $br_code;
                            $_SESSION['branch']= $branch;
                            $_SESSION['user']= $facname;

                            return $priv;
                        }
                    }
                }
            }
        }
        
        function checkStudentLogin($username, $password){
            $res = array();
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("SELECT `privilege`, `regulation`, `br_code`, `email`, `year`, `sem` FROM `st_login` WHERE `sid`=? AND `spass`=?;")){
                    $stmt->bind_param("ss", $username, $password);
                    if($stmt->execute()){
                        $stmt->bind_result($priv, $reg, $br_code, $name, $year, $sem);
                        while($stmt->fetch()){
                            $res['priv'] = $priv;
                            $res['br_code'] = $br_code;
                            $res['user'] = $name;
                            $res['year'] = $year;
                            $res['sem'] = $sem;
                            $res['reg'] = $reg;
                            $res['status'] =  "success";
                            return $res;
                        }
                    }
                }
            }
            $res['status'] =  "failure";
            return $res;
        }
        function getDepartment(){
            $branches = array();
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("SELECT DISTINCT `branch` FROM `fac_login` ORDER BY `br_code`;")){
                    if($stmt->execute()){
                        $stmt->bind_result($branch);
                        $i=0;
                        while($stmt->fetch()){
                            if($branch != "HMS")
                                $branches[$i++] = $branch;
                        }
                    }
                }
            }
            return $branches;
        }

        function getRegulation(){
            $regulation = array();
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("SELECT DISTINCT `regulation` FROM `subjects_2`;")){
                    if($stmt->execute()){
                        $stmt->bind_result($reg);
                        $i=0;
                        while($stmt->fetch()){
                            $regulation[$i++] = $reg;
                        }
                    }
                }
            }
            return $regulation;
        }

        function deactivate($today, $feed_id){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("UPDATE `activation` SET `to_date`=? WHERE `id`=?;")){
                    $stmt->bind_param("sd", $today, $feed_id);
                    if($stmt->execute()){
                        if($conn->affected_rows){
                            return "feed_deactive";
                        }
                    }
                }
            }
        }

        function getActiveFeedbackByBranch($br_code){
            $feedbacks = array();
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT id, regulation, year, sem, from_date, to_date FROM `activation` WHERE branch=? ORDER BY from_date;")){
                    $stmt->bind_param("d", $br_code);
                    if($stmt->execute()){
                        $stmt->bind_result($feed_id, $reg, $year, $sem, $from_date, $to_date);
                        $i = 0;
                        while ($stmt->fetch()) {
                            $feedbacks[$i]['feed_id'] = $feed_id;
                            $feedbacks[$i]['reg'] = $reg;
                            $feedbacks[$i]['year'] = $year;
                            $feedbacks[$i]['sem'] = $sem;
                            $feedbacks[$i]['from_date'] = date("Y-m-d\TH:i", strtotime($from_date));
                            $feedbacks[$i]['to_date'] = date("Y-m-d\TH:i", strtotime($to_date));
                            $i++;
                        }
                    }
                }
            }
            return $feedbacks;
        }

        function getActiveFeedbackByStudent($br_code, $year, $sem){
            $feedbacks = array();
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT `from_date`, `to_date` FROM `activation` WHERE `branch`=? AND `year`=? AND `sem`=?;")){
                    $stmt->bind_param("dss", $br_code, $year, $sem);
                    if($stmt->execute()){
                        $stmt->bind_result($from_date, $to_date);
                        $i = 0;
                        while ($stmt->fetch()) {
                            $feedbacks[$i]['from_date'] = date("Y-m-d\TH:i", strtotime($from_date));
                            $feedbacks[$i]['to_date'] = date("Y-m-d\TH:i", strtotime($to_date));
                            $i++;
                        }
                    }
                }
            }
            return $feedbacks;
        }

        function resetStdPass($rollno){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("SELECT  email, spass FROM `st_login` WHERE `sid`=?;")){
                    $stmt->bind_param("s", $rollno);
                    $stmt->execute();
                    $stmt->bind_result($name, $pass);
                    $stmt->fetch();
                    if(empty($name)){
                        return 'no_roll';
                    }else if($rollno == $pass){
                        return "pwd_chngd";
                    }else{
                        $stmt->close();
                        if($stmt=$this->my_conn->prepare("UPDATE `st_login` SET `spass`=? WHERE `sid`=?")){
                            $stmt->bind_param("ss", $rollno, $rollno);
                            if($stmt->execute()){
                                return "pwd_chngd";
                            }
                        }
                    }
                }
            }
        }

        function deleteFaculty($faculty){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("DELETE FROM `fac_login` WHERE `fname`=?;")){
                    $stmt->bind_param("s", $faculty);
                    if($stmt->execute()){
                        return "delsuccess";
                    }
                }
            }
            return "delfailure";
        }

        function getFaultyList($br_code){
            $faculties = array();
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT DISTINCT `fname` FROM `fac_login` WHERE `br_code`=? AND `privilege` LIKE '%staff%';")){
                    $stmt->bind_param("d", $br_code);
                    if($stmt->execute()){
                        $stmt->bind_result($faculty);
                        $i=0;
                        while($stmt->fetch()){
                            $faculties[$i++]=$faculty;
                        }
                    }
                }
            }
            return $faculties;
        }

        function getFacultyScores($br_code){
            $faculties = array();
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT fid, AVG(avg) FROM `ques` WHERE fid IN (SELECT fname FROM `fac_login` WHERE br_code=?) GROUP BY fid ORDER BY AVG(avg) DESC;")){
                    $stmt->bind_param("d", $br_code);
                    if($stmt->execute()){
                        $stmt->bind_result($faculty, $score);
                        $i = 0;
                        while ($stmt->fetch()) {
                            $faculties[$i]['fname'] = $faculty;
                            $faculties[$i]['score'] = $score;
                            $i++;
                        }
                        
                    }
                }
            }
            return $faculties;
        }

        function addSubject($reg, $year, $sem, $sub, $br_code, $branch, $cr_code){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("INSERT INTO `subjects_2` (`regulation`,`br_code`,`cr_code`,`branch`,`year`,`sem`,`sub`) VALUES (?,?,?,?,?,?,?);")){
                    $stmt->bind_param("sssssss", $reg, $br_code, $cr_code, $branch, $year, $sem, $sub);
                    if($stmt->execute()){
                        if($conn->affected_rows){
                            // subject added successfully
                        }
                    }
                }
            }
        }

        function addPartialSubject($stds, $sub, $reg, $year, $sem, $br_code){
            if($this->my_error==0 && !empty($this->my_conn)){
                foreach($stds as $std){
                    $dupcount = 0;
                    if($stmt1 = $this->my_conn->prepare("SELECT COUNT(*) FROM `partial_subjects` WHERE `std_id`=? AND `subject`=?;")){
                        $stmt1->bind_param("ss", $std, $sub);
                        if($stmt1->execute()){
                            $stmt1->bind_result($dupcount);
                            while ($stmt1->fetch()) {
                                
                            }
                        }
                    }
                    if($dupcount == 0){
                        $stmt1->close();
                        if($stmt=$this->my_conn->prepare("INSERT INTO `partial_subjects` (`std_id`,`subject`,`regulation`,`br_code`,`year`,`sem`) VALUES (?,?,?,?,?,?);")){
                            $stmt->bind_param("ssddss", $std, $sub, $reg, $br_code, $year, $sem);
                            $stmt->execute();  
                            $stmt->close();                          
                        }
                    }
                }
                if($this->my_conn->affected_rows){
                    return "parsuccess";
                }
            }
            return "parfailure";
        }

        function removeSubject($delsub){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("DELETE FROM `subjects_2` WHERE `sub` = ?;")){
                    $stmt->bind_param("s", $delsub);
                    if($stmt->execute()){
                        // subject removed
                    }
                }
                if($stmt=$this->my_conn->prepare("DELETE FROM `partial_subjects` WHERE `subject` = ?;")){
                    $stmt->bind_param("s", $delsub);
                    if($stmt->execute()){
                        // subject removed
                    }
                }
            }
        }

        function facSubjectMap($reg, $year, $sem, $sub, $br_code, $branch, $cr_code, $faculty){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("INSERT INTO `fac_course` (`regulation`,`cr_code`,`branch`,`br_code`,`year`,`sem`,`subject`,`fname`) VALUES (?,?,?,?,?,?,?,?);")){
                    $stmt->bind_param("sssdssss", $reg, $cr_code, $branch, $br_code, $year, $sem, $sub, $faculty);
                    if($stmt->execute()){
                        if($this->my_conn->affected_rows){
                            return "addsuccess";
                        }
                    }
                }
            }
        }

        function deleteFacSubjectMap($subfacid){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("DELETE FROM `fac_course` WHERE `id` = ?;")){
                    $stmt->bind_param("d", $subfacid);
                    if($stmt->execute()){
                        return "delsuccess";
                    }
                }
            }
        }

        function changePassword($username, $currentpass, $newpass, $renewpass){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("SELECT fpass FROM `fac_login` WHERE `fname`=?;")){
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->bind_result($password);
                    $stmt->fetch();
                    if($password != $currentpass){
                        return 'incorrect';
                    }else if($newpass != $renewpass){
                        return "pwd_mismatch";
                    }else{
                        $stmt->close();
                        if($stmt=$this->my_conn->prepare("UPDATE `fac_login` SET `fpass`=? WHERE `fname`=?;")){
                            $stmt->bind_param("ss", $newpass, $username);
                            if($stmt->execute()){
                                return "pwd_chngd";
                            }
                        }
                    }
                }
            }
        }

        function changeStdPassword($username, $currentpass, $newpass, $renewpass){
            if($this->my_error==0 && !empty($this->my_conn)){
                $username = $_SESSION['roll'];
                if($stmt=$this->my_conn->prepare("SELECT spass FROM `st_login` WHERE `sid`=?;")){
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->bind_result($password);
                    $stmt->fetch();
                    if($password != $currentpass){
                        return 'incorrect';
                    }else if($newpass != $renewpass){
                        return "pwd_mismatch";
                    }else{
                        $stmt->close();
                        if($stmt=$this->my_conn->prepare("UPDATE `st_login` SET `spass`=? WHERE `sid`=?;")){
                            $stmt->bind_param("ss", $newpass, $username);
                            if($stmt->execute()){
                                return "pwd_chngd";
                            }
                        }
                    }
                }
            }
        }

        function setFaculty($name, $user, $pass, $email, $priv){
            if($this->my_error==0 && !empty($this->my_conn)){
                $br_code = $_SESSION['br_code'];
                $branch = $_SESSION['branch'];
                $otp_status = 0;
                $present = date("Y-m-d\TH:i", time()-100);
                if($stmt=$this->my_conn->prepare("INSERT INTO `fac_login` (`fname`,`br_code`,`branch`,`fuser`,`fpass`,`privilege`,`email`,`otp_status`, `time`) VALUES (?,?,?,?,?,?,?,?,?);")){
                    $stmt->bind_param("sdsssssss", $name, $br_code, $branch, $user, $pass, $priv, $email, $otp_status, $present);
                    if($stmt->execute()){
                        return "setsuccess";
                    }
                }
            }
            return "setfailure";
        }

        function updateFaculty($name, $user, $pass, $email, $priv){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("UPDATE `fac_login` SET `fuser`=?, `fpass`=?, `privilege`=?, `email`=? WHERE `fname`=?;")){
                    $stmt->bind_param("sssss", $user, $pass, $priv, $email, $name);
                    if($stmt->execute()){
                        if($this->my_conn->affected_rows){
                            return "updatesuccess";
                        }
                    }
                }
            }
            return "updatefailure";
        }

        function getStudentDetails($reg, $year, $sem, $br_code){
            $students = array();
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT `sid`, `email` FROM `st_login` WHERE `regulation`=? AND `year`=? AND `sem`=? AND `br_code`=?;")){
                    $stmt->bind_param("dssd", $reg, $year, $sem, $br_code);
                    if($stmt->execute()){
                        $stmt->bind_result($roll, $name);
                        $i=0;
                        while($stmt->fetch()){
                            $students[$i]['roll'] = $roll;
                            $students[$i]['name'] = $name;
                            $i++;
                        }
                    }
                }
            }
            return $students;
        }

        function deleteStudent($student){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("DELETE FROM `st_login` WHERE `sid`=?;")){
                    $stmt->bind_param("s", $student);
                    if($stmt->execute()){
                        return "delsuccess";
                    }
                }
            }
            return "delfailure";
        }

        function updateStudent($name, $roll, $pass){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("UPDATE `st_login` SET `email`=?, `spass`=? WHERE `sid`=?;")){
                    $stmt->bind_param("sss", $name, $pass, $roll);
                    if($stmt->execute()){
                        if($this->my_conn->affected_rows){
                            return "updatesuccess";
                        }
                    }
                }
            }
            return "updatefailure";
        }

        function setStudent($name, $roll, $reg, $year, $sem){
            if($this->my_error==0 && !empty($this->my_conn)){
                $br_code = $_SESSION['br_code'];
                $otp_status = 0;
                $priv = "student";
                $cr_code = "A";
                $status = 1;
                $otp_status = 1;
                $feed_status = 1;
                $present = date("Y-m-d\TH:i", time()-100);
                if($stmt=$this->my_conn->prepare("INSERT INTO `st_login` (`sid`,`email`,`spass`,`privilege`,`cr_code`,`regulation`,`year`,`sem`, `br_code`, `status`, `otp_status`, `feedback_status`,`time`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?);")){
                    $stmt->bind_param("sssssdssdddds", $roll, $name, $roll, $priv, $cr_code, $reg, $year, $sem, $br_code, $status, $otp_status, $feed_status, $preset);
                    if($stmt->execute()){
                        return "setsuccess";
                    }
                }
            }
            return "setfailure";
        }

        function UpdateStdYearSem($fromreg, $fromyear, $fromsem, $toreg, $toyear, $tosem){
            if($this->my_error==0 && !empty($this->my_conn)){
                $br_code = $_SESSION['br_code'];
                if($stmt=$this->my_conn->prepare("UPDATE `st_login` SET `regulation`=?, `year`=?, `sem`=? WHERE `regulation`=? AND `year`=? AND `sem`=? AND `br_code`=?;")){
                    $stmt->bind_param("dssdssd", $toreg, $toyear, $tosem, $fromreg, $fromyear, $fromsem, $br_code);
                    if($stmt->execute()){
                        if($this->my_conn->affected_rows){
                            return "updatesuccess";
                        }
                    }
                }
            }
            return "updatefailure";
        }

    }

 ?>