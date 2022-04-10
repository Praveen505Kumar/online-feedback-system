<?php
   session_start();

   if(!empty($_POST['username']) && !empty($_POST['password'])){
      $username = $_POST['username'];
      $password = $_POST['password'];
      require_once("slogins.php");

      $login = new Logins();
      $res = $login->checkLogin($username, $password);

      if($res['otpstatus'] == 1 && $res['status'] == 1){
         $_SESSION['user'] = $res['fname'];
         $_SESSION['priv'] = $res['priv'];
         $_SESSION['br_code'] = $res['br_code'];
         $_SESSION['branch'] = $res['branch'];
         $_SESSION['br'] = $res['br_code'];

         if($res['priv'] == 'admin'){
            //$_SESSION['fac_sub'] = array(); 
            header('Location: admin.php');
         }else{
            header('Location:staff1.php');
         }
      }
      else if($res['otpstatus'] == 0 && $res['status'] == 1){

         $_SESSION['user'] = $res['fname']; 
         $_SESSION['priv'] = $res['priv'];
         $_SESSION['br_code']=  $res['br_code'];
         $_SESSION['branch']=  $res['branch'];
        
         header('Location: chngpwd.php');
       }
       else{
         header('Location: loginerror.php');
       }
   }else{
      header('Location: ./');
   }
?>