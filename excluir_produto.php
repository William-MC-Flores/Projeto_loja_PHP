<?php

require_once __DIR__ . "/dao/ProdutoDAO.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$produtoDAO = new ProdutoDAO();

if (!isset($_GET["id"])) {
    die("ID do produto não informado.");
}

$id = (int) $_GET["id"];

if ($produtoDAO->excluir($id)) {
    $_SESSION["mensagem_sucesso"] = "Produto excluído com sucesso!";
    header("Location: produtos.php");
    exit;
} else {
    echo "Erro ao excluir produto.";
}