<?php

class Database {

    private $host = "localhost";
    private $db   = "loja";
    private $user = "root";
    private $pass = "";

    private $conn;

    public function getConnection(){

        try {

            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db}",
                $this->user,
                $this->pass
            );

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $this->conn;

        } catch(PDOException $e){
            echo "Erro na conexão: " . $e->getMessage();
        }
    }
}