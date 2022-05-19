<a href="hod.php" 
    <?php
        if(!empty($menu_id) && $menu_id==1){ 
            echo "class='list-group-item active'"; 
        } else{ 
            echo "class='list-group-item'"; 
        }
    ?>
>Home</a>      
<a href="all_fac_rp.php" 
    <?php 
		if(!empty($menu_id) && $menu_id==2){ 
			echo "class='list-group-item active'"; 
		} else{ 
			echo "class='list-group-item'"; 
		} 
	?>  

>All Faculty Report</a>
<a href="indvidual_rp.php" 
    <?php 
		if(!empty($menu_id) && $menu_id==3){ 
			echo "class='list-group-item active'"; 
		} else{ 
			echo "class='list-group-item'"; 
		} 
	?> 

>Individual Report</a>          
<a href="chg_pwd.php"
    <?php 
		if(!empty($menu_id) && $menu_id==4){ 
			echo "class='list-group-item active'"; 
		} else{ 
			echo "class='list-group-item'"; 
		} 
	?>   

>Change Password</a>
<a href="flogout.php" class="list-group-item list-group-item-action">Log Out</a>