<?php
    require("DbCredentials.php");

    class Logins extends DBCredentials{
        private $my_conn = "";
        private $my_error = 0;

        function __construct(){
            $this->my_conn = new mysqli($this->getHost(), $this->getUserName(), $this->getPassword(), $this->getDBName());
            
            if (mysqli_connect_errno()) {
                $this->my_error = mysqli_connect_errno();
            }
        }
        public function checkLogin($user, $pass){
            $res = array();
            $res['status'] = 0;

            if($this->my_error == 0 && !empty($this->my_conn)){
                if ($stmt = $this->my_conn->prepare("SELECT `privilege`, `br_code`, `branch`, `fname`, `otp_status` FROM `fac_login` WHERE `fuser`=? AND `fpass`=?")){
                    $stmt->bind_param("ss", $user, $pass);

                    if ($stmt->execute()){
                        $stmt->bind_result($privileges, $br_code, $branchs, $fnames, $otpstats);
                        while ($stmt->fetch()){
                            $privilege = $privileges;
                            $otpstatus = $otpstats;
                            $br_code = $br_code;
                            $fname = $fnames;
                        }

                        if (!empty($privilege)){
                            $res['status'] = 1;
                            $res['user'] = $user;
                            $res['fname'] = $fname;
                            $res['priv'] = $privilege;
                            $res['br_code'] = $br_code;
                            $res['branch'] = $branchs;
                            $res['otpstatus'] = $otpstatus;
                        }
                    }else{
                        $res['status'] = 0;
                        $res['error'] = "Data Error";
                    }
                }else{
                    $res['status'] = 0;
                    $res['error'] = "Query Error";
                }
            }else{
                $res['status'] = 0;
                $res['error'] = "Error";
            }
            //echo $res;
            return $res;
        }

        function __destruct(){
            if(!empty($this->myconn)){
              $this->myconn->close();
            }
        }
    }
    

?>