<?php
@session_start();
    require("db_connect.php");
    $sel_branch = $_SESSION['branch'];
    // getting departments
    if($stmt = $conn->prepare("SELECT DISTINCT `branch` FROM `fac_login` ORDER BY `br_code`;")){
        if($stmt->execute()){
            $stmt->bind_result($branch);
            $i=0;
            $branches = array();
            while($stmt->fetch()){
                $branches[$i]=$branch;
                $i++;
            }
        }
        $stmt->close();
    }
    // getting faculty names
    $res = "";
    if($stmt =  $conn->prepare("SELECT `fname`, `branch` FROM `fac_login` WHERE privilege != 'admin' ORDER BY `br_code`;")){
        
        if($stmt->execute()){
            $stmt->bind_result($facname, $branch);
            $i = 0;
            $data = array();
            while($stmt->fetch()){
                $data[$i]['facname'] = $facname;
                $data[$i]['branch'] = $branch;
                $i++;
            }
            $res .= "<optgroup label='".$sel_branch."'>";
            for($j=0;$j<$i;$j++){
                if(!empty($data[$j]['branch']) && $data[$j]['branch'] == $sel_branch){
                    $res .= "<option value='".$data[$j]['facname']."'>".$data[$j]['facname']."</option>";
                    unset($data[$j]);
                }
            }
            $res.= "</optgroup>";
            foreach($branches as $branch){
                if($branch != $sel_branch){
                    $res .= "<optgroup label='".$branch."'>";
                    for($j=0;$j<$i;$j++){
                        if(!empty($data[$j]['branch']) && $data[$j]['branch'] == $branch){
                            $res .= "<option value='".$data[$j]['facname']."'>".$data[$j]['facname']."</option>";
                            unset($data[$j]);
                        }
                    }
                    $res.= "</optgroup>";
                }
                
            }

            echo $res;
        }
    }
?>