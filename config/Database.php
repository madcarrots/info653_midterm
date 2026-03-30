<?php
    Class Database {
        //properties
        private $host = 'localhost';
        private $dbname = 'quotesdb';
        private $username = 'root';
        private $pass = '';
        private $conn;

        //modules
        public function connect (){
            $this->conn = null;

            try {
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname,
                $this->username, $this->pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo 'Connection error: ' . $e->getMessage();
            }

            return $this->conn;
        }

    }
?>