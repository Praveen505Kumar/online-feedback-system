<?php
   @session_start();

   if(!empty($_POST['username']) && !empty($_POST['password'])){
      $username = $_POST['username'];
      $password = $_POST['password'];
      echo $username.$password;
      require("config/db_connect.php");

      if($stmt = $conn->prepare("SELECT `privilege`, `br_code`, `branch`, `fname` FROM `fac_login` WHERE `fuser`=? AND `fpass`=?;")){
         $stmt->bind_param("ss", $username, $password);
         if($stmt->execute()){
            $stmt->bind_result($priv, $br_code, $branch, $facname);
            while($stmt->fetch()){
               $_SESSION['priv']= $priv;
               $_SESSION['br_code']= $br_code;
               $_SESSION['branch']= $branch;
               $_SESSION['user']= $facname;
            }
            if($_SESSION['priv']=="admin"){
               header('Location: admin.php');
            }
            else if($_SESSION['priv']=="hod"){
               header('Location: hod.php');
            }
            else if($_SESSION['priv']=="staff"){
               header('Location: faculty.php');
            }else{
               header('Location: loginerror.php');
            }

         }
     }
     


































      // require_once("slogins.php");

      // $login = new Logins();
      // $res = $login->checkLogin($username, $password);

      // if($res['otpstatus'] == 1 && $res['status'] == 1){
      //    $_SESSION['user'] = $res['fname'];
      //    $_SESSION['priv'] = $res['priv'];
      //    $_SESSION['br_code'] = $res['br_code'];
      //    $_SESSION['branch'] = $res['branch'];
      //    $_SESSION['br'] = $res['br_code'];

      //    if($res['priv'] == 'admin'){
      //       //$_SESSION['fac_sub'] = array(); 
      //       header('Location: admin.php');
      //    }else{
      //       header('Location:staff1.php');
      //    }
      // }
      // else if($res['otpstatus'] == 0 && $res['status'] == 1){

      //    $_SESSION['user'] = $res['fname']; 
      //    $_SESSION['priv'] = $res['priv'];
      //    $_SESSION['br_code']=  $res['br_code'];
      //    $_SESSION['branch']=  $res['branch'];
        
      //    header('Location: chngpwd.php');
      //  }
      //  else{
      //    header('Location: loginerror.php');
      //  }
   }
   else{
      header('Location: ./');
   }
?>