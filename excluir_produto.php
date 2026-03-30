<?php

require_once __DIR__ . "/dao/ProdutoDAO.php";

$produtoDAO = new ProdutoDAO();

if (!isset($_GET["id"])) {
    die("ID do produto não informado.");
}

$id = (int) $_GET["id"];

if ($produtoDAO->excluir($id)) {
    header("Location: produtos.php");
    exit;
} else {
    echo "Erro ao excluir produto.";
}