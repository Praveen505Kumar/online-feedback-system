<?php
@session_start();
if(!empty($_SESSION['user'])){
  unset($_SESSION['user']);
}
if(!empty($_SESSION['priv'])){
  unset($_SESSION['priv']);
}  
if(!empty($_SESSION['count'])){
  unset($_SESSION['count']);
}
  
if(!empty($_SESSION['yr'])){
	unset($_SESSION['yr']);
}
if(!empty($_SESSION['sem'])){
	unset($_SESSION['sem']);
}
if(!empty($_SESSION['reg'])){
	unset($_SESSION['reg']);
}
if(!empty($_SESSION['br'])){
	unset($_SESSION['br']);
}
if(!empty($_SESSION['cr'])){
	unset($_SESSION['cr']);
}
if(!empty($_SESSION['br_code'])){
	unset($_SESSION['br_code']);
}
if(!empty($_SESSION['branch'])){
	unset($_SESSION['branch']);
}
if(!empty($_SESSION['yes_sub'])){
	unset($_SESSION['yes_sub']);
}

header("location: index.php");
 

 
 
?>