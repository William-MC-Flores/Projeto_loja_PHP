<?php

require_once __DIR__ . "/dao/PedidoDAO.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pedidoDAO = new PedidoDAO();

if (!isset($_GET["id"])) {
    die("ID do pedido não informado.");
}

$id = (int) $_GET["id"];

if ($pedidoDAO->excluir($id)) {
    $_SESSION["mensagem_sucesso"] = "Pedido excluído com sucesso!";
    header("Location: pedidos.php");
    exit;
} else {
    echo "Erro ao excluir pedido.";
}