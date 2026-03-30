<?php

require_once __DIR__ . "/dao/ClienteDAO.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$clienteDAO = new ClienteDAO();

if (!isset($_GET["id"])) {
    die("ID do cliente não informado.");
}

$id = (int) $_GET["id"];

if ($clienteDAO->excluir($id)) {
    $_SESSION["mensagem_sucesso"] = "Cliente excluído com sucesso!";
    header("Location: clientes.php");
    exit;
} else {
    echo "Erro ao excluir cliente.";
}