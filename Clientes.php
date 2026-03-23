<?php

require_once "dao/ClienteDAO.php";
require_once "models/Cliente.php";

$clienteDAO = new ClienteDAO();

$msg = "";

if(isset($_POST['salvar'])){

    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $cliente = new Cliente(null, $nome, $email);

    if($clienteDAO->inserir($cliente)){
        $msg = "Cliente cadastrado com sucesso!";
    } else {
        $msg = "Erro ao cadastrar!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Clientes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>Cadastro de Clientes</h1>

    <?php if($msg != ""): ?>
        <p><?= $msg ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <button type="submit" name="salvar">Salvar</button>

    </form>

</div>

</body>
</html>