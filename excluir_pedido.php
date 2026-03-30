<?php

require_once __DIR__ . "/dao/PedidoDAO.php";

$pedidoDAO = new PedidoDAO();

if (!isset($_GET["id"])) {
    die("ID do pedido não informado.");
}

$id = (int) $_GET["id"];

if ($pedidoDAO->excluir($id)) {
    header("Location: pedidos.php");
    exit;
} else {
    echo "Erro ao excluir pedido.";
}