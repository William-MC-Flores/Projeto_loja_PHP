<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/Cliente.php";

class ClienteDAO
{

    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function inserir(Cliente $cliente)
    {
        $sql = "INSERT INTO clientes (nome, email)
                VALUES (:nome, :email)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":nome", $cliente->getNome());
        $stmt->bindValue(":email", $cliente->getEmail());

        return $stmt->execute();
    }

    public function listar()
    {
        $sql = "SELECT * FROM clientes ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM clientes WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            return new Cliente($dados["id"], $dados["nome"], $dados["email"]);
        }

        return null;
    }

    public function atualizar(Cliente $cliente)
    {
        $sql = "UPDATE clientes
                SET nome = :nome, email = :email
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":nome", $cliente->getNome());
        $stmt->bindValue(":email", $cliente->getEmail());
        $stmt->bindValue(":id", $cliente->getId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function excluir($id)
    {
        $sql = "DELETE FROM clientes WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
