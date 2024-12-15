<?php

class database_connection
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $database = "timetable";
    private $conn;
    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->database);
        if ($this->conn->connect_error) {
            error_log("Connection error: " . $this->conn->connect_error);
            die("Connection error: " . $this->conn->connect_error);
        }

        $this->conn->set_charset('utf8mb4');
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    public function getConnection(){
        return $this->conn;}
}