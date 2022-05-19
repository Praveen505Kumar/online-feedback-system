<?php

   if(!empty($_POST['username']) && !empty($_POST['password'])){
      $username = $_POST['username'];
      $password = $_POST['password'];
      
      require("Operations.php");
      $opt = new Operations();
      $res = $opt->checkLogin($username, $password);
      
      if($res == 'admin'){
         header('Location: admin.php');
      }
      else if($res == 'hod'){
         header('Location: hod.php');
      }else if($res == 'staff'){
         header('Location: faculty.php');
      }else{
         header('Location: loginerror.php');
      }
   }
   else{
      header('Location: ./');
   }
?>