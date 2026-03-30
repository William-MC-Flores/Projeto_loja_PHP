<?php

require_once __DIR__ . "/dao/ProdutoDAO.php";
require_once __DIR__ . "/models/Produto.php";

$produtoDAO = new ProdutoDAO();
$msg = "";
$erro = "";

if (isset($_POST["salvar"])) {
    $nome = trim($_POST["nome"]);
    $preco = str_replace(",", ".", trim($_POST["preco"]));

    if ($nome === "") {
        $erro = "O nome do produto não pode ficar vazio.";
    } elseif (!is_numeric($preco)) {
        $erro = "Informe um preço válido.";
    } elseif ($preco < 0) {
        $erro = "O preço não pode ser negativo.";
    } else {
        $produto = new Produto(null, $nome, $preco);

        if ($produtoDAO->inserir($produto)) {
            $msg = "Produto cadastrado com sucesso!";
        } else {
            $erro = "Erro ao cadastrar produto.";
        }
    }
}

$produtos = $produtoDAO->listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Projeto Loja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Cadastro de Produtos</h1>

    <?php if ($msg != ""): ?>
        <p class="mensagem sucesso"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <?php if ($erro != ""): ?>
        <p class="mensagem erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <div class="card">
        <form method="POST" class="formulario">
            <div class="campo">
                <label for="nome">Nome do produto</label>
                <input type="text" name="nome" id="nome" required>
            </div>

            <div class="campo">
                <label for="preco">Preço</label>
                <input type="number" step="0.01" min="0" name="preco" id="preco" required>
            </div>

            <button type="submit" name="salvar" class="btn">Salvar</button>
        </form>
    </div>

    <div class="card">
        <h2>Produtos Cadastrados</h2>

        <?php if (count($produtos) > 0): ?>
            <div class="tabela-responsiva">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                            <tr>
                                <td><?= htmlspecialchars($produto["id"]) ?></td>
                                <td><?= htmlspecialchars($produto["nome"]) ?></td>
                                <td>R$ <?= number_format($produto["preco"], 2, ",", ".") ?></td>
                                <td class="acoes">
                                    <a class="btn-link editar" href="editar_produto.php?id=<?= urlencode($produto["id"]) ?>">Editar</a>
                                    <a class="btn-link excluir"
                                       href="excluir_produto.php?id=<?= urlencode($produto["id"]) ?>"
                                       onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                                       Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Nenhum produto cadastrado até o momento.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>