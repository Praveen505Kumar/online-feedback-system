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

        function activateFeedback($reg, $year, $sem ,$fromdate, $todate, $br_code, $today){
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT `id`, `from_date`, `to_date` FROM `activation` WHERE `regulation`=? AND `branch`=? AND `year`=? AND `sem`=?;")){
                    $stmt->bind_param("ssss", $reg, $br_code, $year, $sem);
                    if($stmt->execute()){
                        $stmt->bind_result($id, $from_date, $to_date);
                        
                        $stmt->fetch();
                        if(!empty($from_date) && !empty($to_date) && $from_date <= $today && $today <= $to_date){
                            return 'feedback_exists';
                        }else{
                            if($fromdate >= $todate){
                                return 'start_end_time_error';
                            }else if($todate <= $today){
                                return 'end_time_error';
                            }else{
                                // activate feedback
                                $stmt->close();
                                $cr_code = "A";
                                $query = "INSERT INTO `activation` (`regulation`,`cr_code`,`branch`,`year`,`sem`,`from_date`,`to_date`) VALUES(?,?,?,?,?,?,?)";
                                if ($stmt = $this->my_conn->prepare($query)) {
                                    $stmt->bind_param("sssssss", $reg, $cr_code, $br_code, $year, $sem, $fromdate, $todate);
                                    if($stmt->execute()){
                                        if($this->my_conn->affected_rows){
                                            return 'feedback_activated';
                                        }else{
                                            return 'feedback_not_activated';
                                        }
                                    }
                                }
                                
                            }
                        }
                    }
                }
            }
            return 'error';
        }

        function getDepartment(){
            $branches = array();
            $br_codes = array();
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT DISTINCT `branch`, `br_code` FROM `fac_login` ORDER BY `br_code`;")){
                    if($stmt->execute()){
                        $stmt->bind_result($branch, $br_code);
                        $i=0;
                        while($stmt->fetch()){
                            if($branch != "HMS" && !in_array($branch, $branches) ){
                                $branches[$i] = $branch;
                                $br_codes[$i] = $br_code;
                                $i++;
                            }
                        }
                    }
                }
            }
            return array($branches, $br_codes);
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
                if($stmt = $this->my_conn->prepare("SELECT `id`, `regulation`, `year`, `sem`, `from_date`, `to_date` FROM `activation` WHERE `branch`=? ORDER BY `regulation`, `year`, `sem`;")){
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

        function getActiveFeedbackByStudent($br_code, $year, $sem, $reg){
            $feedbacks = array();
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT `id`, `from_date`, `to_date` FROM `activation` WHERE `branch`=? AND `year`=? AND `sem`=? AND `regulation`=?;")){
                    $stmt->bind_param("dssd", $br_code, $year, $sem, $reg);
                    if($stmt->execute()){
                        $stmt->bind_result($id, $from_date, $to_date);
                        $i = 0;
                        while ($stmt->fetch()) {
                            $feedbacks[$i]['id'] = $id;
                            $feedbacks[$i]['from_date'] = date("Y-m-d\TH:i", strtotime($from_date));
                            $feedbacks[$i]['to_date'] = date("Y-m-d\TH:i", strtotime($to_date));
                            $i++;
                        }
                    }
                }
            }
            return $feedbacks;
        }

        function getFeedsSubmitted($user, $feed_id){
            $subjects = array();
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT `subject` FROM `feedsSubmitted` WHERE `user`=? AND `feed_id`=?;")){
                    $stmt->bind_param("sd", $user, $feed_id);
                    if($stmt->execute()){
                        $stmt->bind_result($subject);
                        $i = 0;
                        while ($stmt->fetch()) {
                            $subjects[$i] = $subject;
                            $i++;
                        }
                    }
                }
            }
            return $subjects;
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
                if($stmt = $this->my_conn->prepare("SELECT `fid`, AVG(`avg`) FROM `ques` WHERE fid IN (SELECT fname FROM `fac_login` WHERE br_code=?) GROUP BY `fid` ORDER BY AVG(`avg`) DESC;")){
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
                        if($this->my_conn->affected_rows){
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

        function insertComment($facname, $subname, $user, $cmnt, $feed_id){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("INSERT INTO `comments` (`feed_id`, `fname`, `subject`, `stid`, `cmnt`) VALUES (?,?,?,?,?);")){
                    $stmt->bind_param("dssss", $feed_id, $facname, $subname, $user, $cmnt);
                    if($stmt->execute()){
                        if($this->my_conn->affected_rows){
                            // comment inserted successfully
                        }
                    }
                }
            }
        }

        function insertFeedbackData($facname, $br_code, $year, $sem, $reg, $cr_code, $subname, $q1, $q2, $q3, $q4, $q5, 
                                    $q6, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $q14, $avg, $count, $feed_id, $user){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("INSERT INTO `ques`(`feed_id`, `fid`,`br_code`,`year`,`sem`,`regulation`,`cr_code`,`sid`, `qs1`, `qs2`, `qs3`, `qs4`, `qs5`, `qs6`, `qs7`, `qs8`, `qs9`, `qs10`, `qs11`, `qs12`, `qs13`, `qs14`, `avg`, `count`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);")){
                    $stmt->bind_param("dsssssssssssssssssssssss", $feed_id, $facname, $br_code, $year, $sem, $reg, $cr_code, $subname, $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $q14, $avg, $count);
                    if($stmt->execute()){
                        if($this->my_conn->affected_rows){
                            $this->submitFeed($user, $subname, $feed_id);
                            return 1;
                        }
                    }
                }
            }
            return 0;
        }

        function updateFeedbackData($facname, $subname, $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $q14, $avg, $count, $feed_id, $user){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("UPDATE `ques` SET `qs1`=?, `qs2`=?, `qs3`=?,`qs4`=?, `qs5`=?, `qs6`=?, `qs7`=?, `qs8`=?, `qs9`=?, `qs10`=?, `qs11`=?, `qs12`=?, `qs13`=?, `qs14`=?, `avg`=?, `count`=? WHERE `feed_id`=? AND `fid`=? AND `sid`=?;")){
                    $stmt->bind_param("ssssssssssssssssdss", $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $q14, $avg, $count, $feed_id, $facname, $subname);
                    if($stmt->execute()){
                        if($this->my_conn->affected_rows){
                            $this->submitFeed($user, $subname, $feed_id);
                            return 1;
                        }
                    }
                }
            }
            return 0;
        }

        function submitFeed($user, $subname, $feed_id){
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("INSERT INTO `feedsSubmitted`(`user`, `subject`, `feed_id`) VALUES (?,?,?);")){
                    $stmt->bind_param("ssd", $user, $subname, $feed_id);
                    if($stmt->execute()){
                        if($this->my_conn->affected_rows){

                        }
                    }
                }
            }
        }

        function checkFeedbackAvailable($facname, $subname, $feed_id){
            $res = array();
            $res['status'] = 0;
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT `qs1`, `qs2`, `qs3`, `qs4`, `qs5`, `qs6`, `qs7`, `qs8`, `qs9`, `qs10`, `qs11`, `qs12`, `qs13`, `qs14`, `count` FROM `ques` WHERE `fid`=? AND `sid`=? AND `feed_id`=?;")){
                    $stmt->bind_param("ssd", $facname, $subname, $feed_id);
                    if($stmt->execute()){
                        $stmt->bind_result($q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$q9,$q10,$q11,$q12,$q13,$q14, $count);
                        while($stmt->fetch()){
                            $res['qs1']=$q1;
							$res['qs2']=$q2;
							$res['qs3']=$q3;
							$res['qs4']=$q4;
							$res['qs5']=$q5;
							$res['qs6']=$q6;
							$res['qs7']=$q7;
							$res['qs8']=$q8;
							$res['qs9']=$q9;
							$res['qs10']=$q10;
							$res['qs11']=$q11;
							$res['qs12']=$q12;
							$res['qs13']=$q13;
							$res['qs14']=$q14;
							$res['count']=$count;
							$res['status'] = 1;
                        }
                    }
                }
            }
            return $res;
        }

        function uploadStudentData($sid, $email, $spass, $privilege, $cr_code, $regulation, $year, $sem, $br_code, $status, $otp_status, $feedback_status){
            
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("INSERT IGNORE INTO `st_login` (`sid`, `email`, `spass`, `privilege`, `cr_code`, `regulation`, `year`, `sem`, `br_code`, `status`, `otp_status`, `feedback_status`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);")){
                    $stmt->bind_param("sssssssssiii", $sid, $email, $spass, $privilege, $cr_code, $regulation, $year, $sem, $br_code, $status, $otp_status, $feedback_status);
                    if($stmt->execute()){
                        if($this->my_conn->affected_rows){
                            return 'success';
                        }
                    }
                }
            }
            return "failure";
        }

        function uploadFacultyData($fname, $br_code, $branch, $fuser, $fpass, $priv, $email, $otp_status){
            
            if($this->my_error==0 && !empty($this->my_conn)){
                if($stmt=$this->my_conn->prepare("INSERT IGNORE INTO `fac_login` (`fname`, `br_code`, `branch`, `fuser`, `fpass`, `privilege`, `email`, `otp_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);")){
                    
                    $stmt->bind_param("sssssssi", $fname, $br_code, $branch, $fuser, $fpass, $priv, $email, $otp_status);
                    if($stmt->execute()){ 
                        if($this->my_conn->affected_rows){
                            return 'success1';
                        }
                    }
                }
            }
            return "failure1";
        }

        function getReportDetails($br_code, $year, $sem, $reg, $cr_code, $feed_id){
            $result = array();
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT `fid`,`sid`,`avg`, `count` FROM `ques` WHERE `br_code`=? AND `year`=? AND `sem`=? AND `regulation`=? AND `cr_code`=? AND `feed_id`=?;")){
                    $stmt->bind_param("dssssd", $br_code, $year, $sem, $reg, $cr_code, $feed_id);
                    if($stmt->execute()){
                        $stmt->bind_result($facname, $subname, $avg, $count);
                        $i=0;
                        while($stmt->fetch()){
                            $result[$i]['facname'] = $facname;
                            $result[$i]['subname'] = $subname;
                            $result[$i]['avg'] = $avg;
                            $result[$i]['count'] = $count;
                            $i++;
                        }
                    }
                }
            }
            return $result;
        }

        function getQuestionsPer($facname, $subject, $feed_id){
            $questions = array();

            $questions[0][0] = "Teacher comes to the class on time";
            $questions[0][1] = "Teacher speaks clearly and audibly";
            $questions[0][2] = "Teacher plans lesson with clear objective";
            $questions[0][3] = "Teacher has got command on the subject";
            $questions[0][4] = "Teacher writes and draws legibly";
            $questions[0][5] = "Teacher asks qstions to promote interaction and effective thinking";
            $questions[0][6] = "Teacher encourages,compliments and praises originality and creativity displayed by the student";
            $questions[0][7] = "Teacher is courteous and impartial in dealing with the students";
            $questions[0][8] = "Teacher covers the syllabus completely";
            $questions[0][9] = "Teacher evaluation of the sessional exams answer scripts,lab records etc is fair and impartial";
            $questions[0][10] = "Teacher is prompt in valuing and returning the answer scripts providing feedback on performanc";
            $questions[0][11] = "Teacher offers assistance and counseling to the needy students";
            $questions[0][12] = "Teacher imparts the practical knowledge concerned to the subject";
            $questions[0][13] = "Teacher leaves the class on time";

            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT  `qs1`, `qs2`, `qs3`, `qs4`, `qs5`, `qs6`, `qs7`, `qs8`, `qs9`, `qs10`, `qs11`, `qs12`, `qs13`, `qs14`, `avg`, `count` FROM `ques` WHERE `fid`=? AND  `sid`=? AND `feed_id`=?; ")){
                    $stmt->bind_param("ssd", $facname, $subject, $feed_id);
                    if($stmt->execute()){
                        $stmt->bind_result($qs1, $qs2, $qs3, $qs4, $qs5, $qs6, $qs7, $qs8, $qs9, $qs10, $qs11, $qs12, $qs13, $qs14, $avg, $count);
                        while($stmt->fetch()){
                            $questions[1][0] = $qs1;
                            $questions[1][1] = $qs2;
                            $questions[1][2] = $qs3;
                            $questions[1][3] = $qs4;
                            $questions[1][4] = $qs5;
                            $questions[1][5] = $qs6;
                            $questions[1][6] = $qs7;
                            $questions[1][7] = $qs8;
                            $questions[1][8] = $qs9;
                            $questions[1][9] = $qs10;
                            $questions[1][10] = $qs11;
                            $questions[1][11] = $qs12;
                            $questions[1][12] = $qs13;
                            $questions[1][13] = $qs14;
                            $questions['stdcount'] = $count;
                            $questions['average'] = $avg;
                        }
                    }
                }
            }
            return $questions;
        }

        function getComments($facname, $subject, $feed_id){
            $comments = array();            
            if($this->my_error == 0 && !empty($this->my_conn)){
                if($stmt = $this->my_conn->prepare("SELECT DISTINCT `cmnt` FROM `comments` WHERE `fname`=? AND `subject`=? AND `feed_id`=?;")){
                    $stmt->bind_param("ssd", $facname, $subject, $feed_id);
                    if($stmt->execute()){
                        $stmt->bind_result($comment);
                        $i=0;
                        while($stmt->fetch()){
                            $comments[$i] = $comment;
                            $i++;
                        }
                    }
                }
            }
            return $comments;
        }

    }

 ?>