<a href="faculty.php" 
    <?php
        if(!empty($menu_id) && $menu_id==1){ 
            echo "class='list-group-item active'"; 
        } else{ 
            echo "class='list-group-item'"; 
        }
    ?>
>Get Report</a>                
<a href="chg_pwd.php"
    <?php 
		if(!empty($menu_id) && $menu_id==2){ 
			echo "class='list-group-item active'"; 
		} else{ 
			echo "class='list-group-item'"; 
		} 
	?>   

>Change Password</a>
<a href="flogout.php" class="list-group-item list-group-item-action">Log Out</a>