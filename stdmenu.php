<a href="student.php" 
    <?php
        if(!empty($menu_id) && $menu_id==1){ 
            echo "class='list-group-item active'"; 
        } else{ 
            echo "class='list-group-item'"; 
        }
    ?>
>Feedback</a>                
<a href="chg_std_pwd.php"
    <?php 
		if(!empty($menu_id) && $menu_id==2){ 
			echo "class='list-group-item active'"; 
		} else{ 
			echo "class='list-group-item'"; 
		} 
	?>   

>Change Password</a>
<a href="flogout.php" class="list-group-item list-group-item-action">Log Out</a>