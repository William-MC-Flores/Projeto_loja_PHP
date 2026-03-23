<?php
require_once "classes/Produto.php";

$p = new Produto(1, "Mouse", -50);

echo $p->getPreco();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Sistema de Loja</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Sistema de Pedidos da Loja</h1>

</body>

</html>