<?php

require_once "classes/Cliente.php";
require_once "classes/Produto.php";
require_once "classes/Pedido.php";

$cliente = new Cliente(1, "João Silva", "joao@email.com");

$p1 = new Produto(1, "Notebook", 3500);
$p2 = new Produto(2, "Mouse Gamer", 150);
$p3 = new Produto(3, "Headset", 280);

$pedido = new Pedido(1001, $cliente);

$pedido->adicionarProduto($p1);
$pedido->adicionarProduto($p2);
$pedido->adicionarProduto($p3);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Loja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
$pedido->exibirResumo();
?>

</body>
</html>