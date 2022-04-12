<?php
    date_default_timezone_set("Asial/Kolkata");
    // require_once("DbCrendentials.php");
    require("DbCredentials.php");
    
    class CGS extends DBCredentials{

        private $my_conn = "";
        private $my_error = 0;

        function __construct(){
            $this->my_conn = new mysqli($this->getHost(), $this->getUserName(), $this->getPassword(), $this->getDBName());
            
            if (mysqli_connect_errno()) {
                $this->my_error = mysqli_connect_errno();
            }
        }
        public function updatepass($username){
            if($this->my_error==0 && !empty($this->my_conn){
                if($stmt=$this->my_conn->prepare("UPDATE `st_login` SET `spass`=? WHERE `sid`=?")){
                 
                    $stmt->bind_param("ss", $username, $username);
                    if($stmt->execute()){
                        if($this->my_conn->affected_rows){
                            $res=1;
                            return $res;
                        }else{
                            return 2;
                        }
                    }
                }
            }
            return 0;
        }
        public function chngstdpass($username){
            $res=0;
            echo "alsdfkadsfffffffffhj";
            if($this->my_error==0 && !empty($this->my_conn)){
                
                if ($stmt = $this->my_conn->prepare("SELECT  email, spass FROM `st_login` WHERE `sid`=?")) {
					$stmt->bind_param("s",$username);
					if($stmt->execute()){
                        
						$stmt->bind_result($name, $pass);
						if($stmt->fetch()){
                            if($username == $pass){
                                $res=1;
                                return $res;
                            }else{
                                //$res = $this->updatepass($username);
                                return $res;
                            }
                        }
					}else{
                        
                        $res = 2;
                        return $res;
                    }
				}
                // if($stmt=$this->my_conn->prepare("UPDATE `st_login` SET `spass`=? WHERE `sid`=?")){
                    
                //     $stmt->bind_param("ss", $username, $username);
                //     if($stmt->execute()){
                //         if($this->my_conn->affected_rows){
                //             $res=1;
                //         }else{
                //             $res = 2;
                //         }
                //     }
                // }
            }
            return $res;
        }
    }
    


?>