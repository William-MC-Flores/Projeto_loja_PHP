<?php

require_once __DIR__ . "/dao/ClienteDAO.php";

$clienteDAO = new ClienteDAO();

if (!isset($_GET["id"])) {
    die("ID do cliente não informado.");
}

$id = (int) $_GET["id"];

if ($clienteDAO->excluir($id)) {
    header("Location: clientes.php");
    exit;
} else {
    echo "Erro ao excluir cliente.";
}