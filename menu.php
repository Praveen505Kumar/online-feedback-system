<a href="admin.php" 
    <?php
        if(!empty($menu_id) && $menu_id==1){ 
            echo "class='list-group-item active'"; 
        } else{ 
            echo "class='list-group-item'"; 
        }
    ?>
>Home (<?php echo $_SESSION['branch'];?>)</a>                
<a href="activate_fb.php" 
    <?php 
		if(!empty($menu_id) && $menu_id==2){ 
			echo "class='list-group-item active'"; 
		} else{ 
			echo "class='list-group-item'"; 
		} 
	?> 
      

>
    Activate Feedback
</a>
<a href="show_activ_fb.php" 
    <?php 
		if(!empty($menu_id) && $menu_id==3){
			echo "class='list-group-item active'"; 
		} else{ 
			echo "class='list-group-item'"; 
		} 
	?> 

>Show Activated Feedbacks</a>
<?php
	if($_SESSION['user'] == "admin" || strtolower($_SESSION['user']) == "administrator"){

		if(!empty($menu_id) && $menu_id==4){
			echo '<a href="pwdreset.php" class="list-group-item active" >Reset Student Password</a>';
		}else{
			echo '<a href="pwdreset.php" class="list-group-item" >Reset Student Password</a>';
		}
		if(!empty($menu_id) && $menu_id==5){
			echo '<a href="delete_fac.php" class="list-group-item active" >Delete Faculty</a>';
		}else{
			echo '<a href="delete_fac.php" class="list-group-item" >Delete Faculty</a>';
		}
		if(!empty($menu_id) && $menu_id==6){
			echo '<a href="fac_list.php" class="list-group-item active" >Faculty List</a>';
		}else{
			echo '<a href="fac_list.php" class="list-group-item" >Faculty List</a>';
		}
		if(!empty($menu_id) && $menu_id==7){
			echo '<a href="upload_dt.php" class="list-group-item active" >Upload Data</a>';
		}else{
			echo '<a href="upload_dt.php" class="list-group-item" >Upload Data</a>';
		}
				
		
	}

	if(!empty($menu_id) && $menu_id==8){
		echo '<a href="add_subjects.php" class="list-group-item active" >Add/View Subjects</a>';
	}else{
		echo '<a href="add_subjects.php" class="list-group-item" >Add/View Subjects</a>';
	}
	
	if(!empty($menu_id) && $menu_id==9){
		echo '<a href="fac_course.php" class="list-group-item active" >Fac_Course Mapping</a>';
	}else{
		echo '<a href="fac_course.php" class="list-group-item" >Fac_Course Mapping</a>';
	}
	
?>

<a href="all_fac_rp.php" 
    <?php 
		if(!empty($menu_id) && $menu_id==10){ 
			echo "class='list-group-item active'"; 
		} else{ 
			echo "class='list-group-item'"; 
		} 
	?>  

>All Faculty Report</a>
<a href="indvidual_rp.php" 
    <?php 
		if(!empty($menu_id) && $menu_id==11){ 
			echo "class='list-group-item active'"; 
		} else{ 
			echo "class='list-group-item'"; 
		} 
	?> 

>Individual Report</a>

 
<?php
    if($_SESSION['user']=="admin" || strtolower($_SESSION['user'])=="administrator"){
        if(!empty($menu_id) && $menu_id==12){
            echo '<a href="writ_fb.php" class="list-group-item active" >Individual Written Feedback</a>';
        }else{
            echo '<a href="writ_fb.php" class="list-group-item" >Individual Written Feedback</a>';
        }
    }
?>

<a href="chg_pwd.php"
    <?php 
		if(!empty($menu_id) && $menu_id==13){ 
			echo "class='list-group-item active'"; 
		} else{ 
			echo "class='list-group-item'"; 
		} 
	?>   

>Change Password</a>
<a href="flogout.php" class="list-group-item list-group-item-action">Log Out</a>
