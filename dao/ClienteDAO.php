<?php

require_once "config/Database.php";
require_once "models/Cliente.php";

class ClienteDAO {

    private $conn;

    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function inserir(Cliente $cliente){

        $sql = "INSERT INTO clientes (nome, email)
                VALUES (:nome, :email)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":nome", $cliente->getNome());
        $stmt->bindValue(":email", $cliente->getEmail());

        return $stmt->execute();
    }
}