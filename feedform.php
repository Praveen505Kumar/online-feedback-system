<?php
    @session_start();
    date_default_timezone_set("Asia/Kolkata");
    if(!empty($_SESSION['user']) && !empty($_SESSION['priv']) && $_SESSION['priv']=="student"){
        // connection
        require("Operations.php");
        $opt = new Operations();
        $facname = $_POST['facname'];
        $subname = $_POST['subname'];
        echo $_POST['cmnt'];
        echo $facname.$subname;
        $br_code = $_SESSION['br_code'];
        $cr_code = "A";
        $year = $_SESSION['year'];
        $sem = $_SESSION['sem'];
        $reg = $_SESSION['reg'];
        $rollno = $_SESSION['roll'];
        $today = date("Y-m-d\TH:i",time());	
        // getting active feedbacks
        $feedbacks = $opt->getActiveFeedbackByStudent($br_code, $year, $sem, $reg);
        $temp = 0;
        foreach($feedbacks as $feedback){
            $feed_id = $feedback['id'];
            if(!empty($feedback['from_date']) && !empty($feedback['to_date']) && $feedback['from_date'] <= $today && $today <= $feedback['to_date']){
                $subjects = $opt->getFeedsSubmitted($rollno, $feed_id);
                if(!empty($subjects) && sizeof($subjects) > 0 && in_array($subname, $subjects)){			
                    header('Location: student.php');
                }else{
                    if(!empty($facname) && !empty($subname)){
                        // insert comments in ti comment table if comment exist.
                        if(!empty($_POST['cmnt'])){
                            $opt->insertComment($facname, $subname, $rollno, $_POST['cmnt'], $feed_id);
                        }
                        // check if feedback data already available or not.
                        $res = $opt->checkFeedbackAvailable($facname, $subname, $feed_id);
                        // insert feedback data
                        if($res['status'] == 0){
                            $count = 1;
                            $q1 = (int)$_POST['q1'];
                            $q2 = (int)$_POST['q2'];
                            $q3 = (int)$_POST['q3'];
                            $q4 = (int)$_POST['q4'];
                            $q5 = (int)$_POST['q5'];
                            $q6 = (int)$_POST['q6'];
                            $q7 = (int)$_POST['q7'];
                            $q8 = (int)$_POST['q8'];
                            $q9 = (int)$_POST['q9'];
                            $q10 = (int)$_POST['q10'];
                            $q11 = (int)$_POST['q11'];
                            $q12 = (int)$_POST['q12'];
                            $q13 = (int)$_POST['q13'];
                            $q14 = (int)$_POST['q14'];
                            $avg = ($q1+$q2+$q3+$q4+$q5+$q6+$q7+$q8+$q9+$q10+$q11+$q12+$q13+$q14)/14;
                            $insupdres = $opt->insertFeedbackData($facname, $br_code, $year, $sem, $reg, $cr_code, $subname, $q1, $q2, $q3, $q4, $q5, 
                                                    $q6, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $q14, $avg, $count, $feed_id, $rollno);
                            
                        }
                        // update existing one
                        else{
                            $val=0;
                            for($j=1;$j<15;$j++){
                                if(isset($_POST['q'.$j.'']))
                                    $val=1;
                            }
                            if($val == 1){
                                $q1 = $_POST['q1'] + $res['qs1'];
                                $q2 = $_POST['q2'] + $res['qs2'];
                                $q3 = $_POST['q3'] + $res['qs3'];
                                $q4 = $_POST['q4'] + $res['qs4'];
                                $q5 = $_POST['q5'] + $res['qs5'];
                                $q6 = $_POST['q6'] + $res['qs6'];
                                $q7 = $_POST['q7'] + $res['qs7'];
                                $q8 = $_POST['q8'] + $res['qs8'];
                                $q9 = $_POST['q9'] + $res['qs9'];
                                $q10 = $_POST['q10'] + $res['qs10'];
                                $q11 = $_POST['q11'] + $res['qs11'];
                                $q12 = $_POST['q12'] + $res['qs12'];
                                $q13 = $_POST['q13'] + $res['qs13'];
                                $q14 = $_POST['q14'] + $res['qs14'];
                                $total = $q1+$q2+$q3+$q4+$q5+$q6+$q7+$q8+$q9+$q10+$q11+$q12+$q13+$q14;
                                $count = ++$res['count'];
                                $avg = ($total/(14*$count));
                                echo $facname.$subname.$feed_id.$rollno;
                                $insupdres = $opt->updateFeedbackData($facname, $subname, $q1, $q2, $q3, $q4, $q5, 
                                                    $q6, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $q14, $avg, $count, $feed_id, $rollno);
                            }
                            
                        }
                        if($insupdres){
                            // remove below line
                            $_SESSION['count']++;
                            header('Location:student.php');
                        }else{
                            echo "data not inserted";
                        }
                    }else{
                        header("Location: student.php");
                    }
                }
                $temp = 1;
                break;
            }
            else{
                header('Location: logout.php?msg=feed_time_out');
            }
        }

    }else{
        header('Location: index.php');
    }
?>