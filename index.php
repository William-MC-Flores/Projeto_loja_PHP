<?php

require_once __DIR__ . "/dao/ClienteDAO.php";
require_once __DIR__ . "/dao/ProdutoDAO.php";
require_once __DIR__ . "/dao/PedidoDAO.php";

$clienteDAO = new ClienteDAO();
$produtoDAO = new ProdutoDAO();
$pedidoDAO = new PedidoDAO();

$totalClientes = $clienteDAO->contarClientes();
$totalProdutos = $produtoDAO->contarProdutos();
$totalPedidos = $pedidoDAO->contarPedidos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema da Loja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Sistema de Pedidos da Loja</h1>

    <div class="dashboard-cards">
        <div class="mini-card">
            <h2><?= htmlspecialchars($totalClientes) ?></h2>
            <p>Clientes</p>
        </div>

        <div class="mini-card">
            <h2><?= htmlspecialchars($totalProdutos) ?></h2>
            <p>Produtos</p>
        </div>

        <div class="mini-card">
            <h2><?= htmlspecialchars($totalPedidos) ?></h2>
            <p>Pedidos</p>
        </div>
    </div>

    <div class="card">
        <h2>Menu Principal</h2>
        <div class="linha-botoes">
            <a href="clientes.php" class="btn-link editar">Gerenciar Clientes</a>
            <a href="produtos.php" class="btn-link voltar">Gerenciar Produtos</a>
            <a href="pedidos.php" class="btn-link btn-pedido">Gerenciar Pedidos</a>
        </div>
    </div>
</div>

</body>
</html>