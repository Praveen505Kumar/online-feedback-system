<?php
    $host = "localhost";
    $db = "feedback";
    $user = "root";
    $pass = "";

    $conn = new mysqli($host, $user, $pass, $db);
            
    if (mysqli_connect_errno()) {
        echo "Error in connection".mysqli_connect_errno();
    }
    

 ?>