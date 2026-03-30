<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/Produto.php";

class ProdutoDAO {

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function inserir(Produto $produto) {
        $sql = "INSERT INTO produtos (nome, preco)
                VALUES (:nome, :preco)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":nome", $produto->getNome());
        $stmt->bindValue(":preco", $produto->getPreco());

        return $stmt->execute();
    }

    public function listar() {
        $sql = "SELECT * FROM produtos ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM produtos WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            return new Produto($dados["id"], $dados["nome"], $dados["preco"]);
        }

        return null;
    }

    public function atualizar(Produto $produto) {
        $sql = "UPDATE produtos
                SET nome = :nome, preco = :preco
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":nome", $produto->getNome());
        $stmt->bindValue(":preco", $produto->getPreco());
        $stmt->bindValue(":id", $produto->getId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function excluir($id) {
        $sql = "DELETE FROM produtos WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}