<?php
require_once "classes/Cliente.php";

$cliente = new Cliente(1, "João Silva", "joao@email.com");

echo $cliente->getNome();
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