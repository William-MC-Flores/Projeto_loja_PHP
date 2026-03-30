<?php

require_once __DIR__ . "/dao/ClienteDAO.php";
require_once __DIR__ . "/dao/ProdutoDAO.php";
require_once __DIR__ . "/dao/PedidoDAO.php";
require_once __DIR__ . "/models/Cliente.php";
require_once __DIR__ . "/models/Produto.php";
require_once __DIR__ . "/models/Pedido.php";
require_once __DIR__ . "/models/ItemPedido.php";

$clienteDAO = new ClienteDAO();
$produtoDAO = new ProdutoDAO();
$pedidoDAO = new PedidoDAO();

$clientes = $clienteDAO->listar();
$produtos = $produtoDAO->listar();
$pedidos = $pedidoDAO->listar();

$msg = "";
$erro = "";

if (isset($_POST["salvar"])) {
    $clienteId = (int) $_POST["cliente_id"];
    $produtosSelecionados = $_POST["produtos"] ?? [];
    $quantidades = $_POST["quantidades"] ?? [];

    if ($clienteId <= 0) {
        $erro = "Selecione um cliente.";
    } elseif (count($produtosSelecionados) === 0) {
        $erro = "Selecione pelo menos um produto.";
    } else {
        $clienteObj = $clienteDAO->buscarPorId($clienteId);

        if (!$clienteObj) {
            $erro = "Cliente inválido.";
        } else {
            $pedido = new Pedido(null, $clienteObj);

            foreach ($produtosSelecionados as $produtoId) {
                $produtoId = (int) $produtoId;
                $quantidade = isset($quantidades[$produtoId]) ? (int) $quantidades[$produtoId] : 0;

                if ($quantidade > 0) {
                    $produtoObj = $produtoDAO->buscarPorId($produtoId);

                    if ($produtoObj) {
                        $item = new ItemPedido($produtoObj, $quantidade);
                        $pedido->adicionarItem($item);
                    }
                }
            }

            if (count($pedido->getItens()) === 0) {
                $erro = "Informe quantidade válida para os produtos selecionados.";
            } elseif ($pedidoDAO->inserir($pedido)) {
                $msg = "Pedido cadastrado com sucesso!";
                $pedidos = $pedidoDAO->listar();
            } else {
                $erro = "Erro ao cadastrar pedido.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos | Projeto Loja</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Cadastro de Pedidos</h1>

    <?php if ($msg != ""): ?>
        <p class="mensagem sucesso"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <?php if ($erro != ""): ?>
        <p class="mensagem erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <div class="card">
        <form method="POST" class="formulario">
            <div class="campo">
                <label for="cliente_id">Cliente</label>
                <select name="cliente_id" id="cliente_id" required>
                    <option value="">Selecione um cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= htmlspecialchars($cliente["id"]) ?>">
                            <?= htmlspecialchars($cliente["nome"]) ?> - <?= htmlspecialchars($cliente["email"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label>Produtos</label>

                <?php if (count($produtos) > 0): ?>
                    <div class="lista-produtos">
                        <?php foreach ($produtos as $produto): ?>
                            <div class="produto-item">
                                <label class="produto-check">
                                    <input type="checkbox" name="produtos[]" value="<?= htmlspecialchars($produto["id"]) ?>">
                                    <?= htmlspecialchars($produto["nome"]) ?> — R$ <?= number_format($produto["preco"], 2, ",", ".") ?>
                                </label>

                                <input
                                    type="number"
                                    name="quantidades[<?= htmlspecialchars($produto["id"]) ?>]"
                                    min="1"
                                    value="1"
                                    class="input-quantidade"
                                >
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Cadastre produtos antes de criar um pedido.</p>
                <?php endif; ?>
            </div>

            <button type="submit" name="salvar" class="btn">Salvar Pedido</button>
        </form>
    </div>

    <div class="card">
        <h2>Pedidos Cadastrados</h2>

        <?php if (count($pedidos) > 0): ?>
            <div class="tabela-responsiva">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?= htmlspecialchars($pedido["id"]) ?></td>
                                <td><?= htmlspecialchars($pedido["cliente_nome"]) ?></td>
                                <td>R$ <?= number_format($pedido["total"], 2, ",", ".") ?></td>
                                <td><?= htmlspecialchars($pedido["data_pedido"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Nenhum pedido cadastrado até o momento.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>