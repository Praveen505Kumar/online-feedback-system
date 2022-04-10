<?php

    class DBCredentials{
        private $host = "localhost";
        private $db = "feedback";
        private $user = "root";
        private $pass = "";
        protected function getHost(){
            return $this->host;
        }

        protected function getDBname(){
            return $this->db;
        }

        protected function getUserName(){
            return $this->user;
        }

        protected function getPassword(){
            return $this->pass;
        }
    }

 ?>
