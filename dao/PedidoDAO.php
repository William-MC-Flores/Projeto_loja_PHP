<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/Pedido.php";
require_once __DIR__ . "/../models/ItemPedido.php";
require_once __DIR__ . "/../models/Cliente.php";
require_once __DIR__ . "/../models/Produto.php";

class PedidoDAO {

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function inserir(Pedido $pedido) {
        try {
            $this->conn->beginTransaction();

            $sqlPedido = "INSERT INTO pedidos (cliente_id, total)
                          VALUES (:cliente_id, :total)";
            $stmtPedido = $this->conn->prepare($sqlPedido);
            $stmtPedido->bindValue(":cliente_id", $pedido->getCliente()->getId(), PDO::PARAM_INT);
            $stmtPedido->bindValue(":total", $pedido->getTotal());
            $stmtPedido->execute();

            $pedidoId = $this->conn->lastInsertId();

            $sqlItem = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, subtotal)
                        VALUES (:pedido_id, :produto_id, :quantidade, :subtotal)";
            $stmtItem = $this->conn->prepare($sqlItem);

            foreach ($pedido->getItens() as $item) {
                $stmtItem->bindValue(":pedido_id", $pedidoId, PDO::PARAM_INT);
                $stmtItem->bindValue(":produto_id", $item->getProduto()->getId(), PDO::PARAM_INT);
                $stmtItem->bindValue(":quantidade", $item->getQuantidade(), PDO::PARAM_INT);
                $stmtItem->bindValue(":subtotal", $item->getSubtotal());
                $stmtItem->execute();
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return false;
        }
    }

    public function listar() {
        $sql = "SELECT pedidos.id, clientes.nome AS cliente_nome, pedidos.total, pedidos.data_pedido
                FROM pedidos
                INNER JOIN clientes ON pedidos.cliente_id = clientes.id
                ORDER BY pedidos.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT 
                    pedidos.id,
                    pedidos.total,
                    pedidos.data_pedido,
                    clientes.id AS cliente_id,
                    clientes.nome AS cliente_nome,
                    clientes.email AS cliente_email
                FROM pedidos
                INNER JOIN clientes ON pedidos.cliente_id = clientes.id
                WHERE pedidos.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarItens($pedidoId) {
        $sql = "SELECT 
                    itens_pedido.id,
                    itens_pedido.quantidade,
                    itens_pedido.subtotal,
                    produtos.id AS produto_id,
                    produtos.nome AS produto_nome,
                    produtos.preco AS produto_preco
                FROM itens_pedido
                INNER JOIN produtos ON itens_pedido.produto_id = produtos.id
                WHERE itens_pedido.pedido_id = :pedido_id
                ORDER BY itens_pedido.id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":pedido_id", $pedidoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir($id) {
        try {
            $this->conn->beginTransaction();

            $sqlItens = "DELETE FROM itens_pedido WHERE pedido_id = :pedido_id";
            $stmtItens = $this->conn->prepare($sqlItens);
            $stmtItens->bindValue(":pedido_id", $id, PDO::PARAM_INT);
            $stmtItens->execute();

            $sqlPedido = "DELETE FROM pedidos WHERE id = :id";
            $stmtPedido = $this->conn->prepare($sqlPedido);
            $stmtPedido->bindValue(":id", $id, PDO::PARAM_INT);
            $stmtPedido->execute();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return false;
        }
    }

    public function contarPedidos() {
        $sql = "SELECT COUNT(*) AS total FROM pedidos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado["total"];
    }
}