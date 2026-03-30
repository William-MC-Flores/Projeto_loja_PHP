<?php

require_once __DIR__ . "/dao/ClienteDAO.php";
require_once __DIR__ . "/models/Cliente.php";

$clienteDAO = new ClienteDAO();
$msg = "";
$erro = "";

if (isset($_POST["salvar"])) {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);

    if ($nome === "") {
        $erro = "O nome não pode ficar vazio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Informe um e-mail válido.";
    } else {
        $cliente = new Cliente(null, $nome, $email);

        if ($clienteDAO->inserir($cliente)) {
            $msg = "Cliente cadastrado com sucesso!";
        } else {
            $erro = "Erro ao cadastrar cliente.";
        }
    }
}

$clientes = $clienteDAO->listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | Projeto Loja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Cadastro de Clientes</h1>

    <?php if ($msg != ""): ?>
        <p class="mensagem sucesso"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <?php if ($erro != ""): ?>
        <p class="mensagem erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <div class="card">
        <form method="POST" class="formulario">
            <div class="campo">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" required>
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>
            </div>

            <button type="submit" name="salvar" class="btn">Salvar</button>
        </form>
    </div>

    <div class="card">
        <h2>Clientes Cadastrados</h2>

        <?php if (count($clientes) > 0): ?>
            <div class="tabela-responsiva">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= htmlspecialchars($cliente["id"]) ?></td>
                                <td><?= htmlspecialchars($cliente["nome"]) ?></td>
                                <td><?= htmlspecialchars($cliente["email"]) ?></td>
                                <td class="acoes">
                                    <a class="btn-link editar" href="editar_cliente.php?id=<?= urlencode($cliente["id"]) ?>">Editar</a>
                                    <a class="btn-link excluir"
                                       href="excluir_cliente.php?id=<?= urlencode($cliente["id"]) ?>"
                                       onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
                                       Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Nenhum cliente cadastrado até o momento.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>