<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/Pedido.php";
require_once __DIR__ . "/../models/ItemPedido.php";

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
            $this->conn->rollBack();
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
}