<?php

require_once __DIR__ . "/dao/PedidoDAO.php";

$tituloPagina = "Detalhes do Pedido";

$pedidoDAO = new PedidoDAO();

if (!isset($_GET["id"])) {
    die("ID do pedido não informado.");
}

$id = (int) $_GET["id"];

$pedido = $pedidoDAO->buscarPorId($id);

if (!$pedido) {
    die("Pedido não encontrado.");
}

$itens = $pedidoDAO->listarItens($id);

require_once __DIR__ . "/partials/header.php";
?>

<div class="container">
    <h1>Detalhes do Pedido #<?= htmlspecialchars($pedido["id"]) ?></h1>

    <div class="card">
        <h2>Dados do Pedido</h2>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($pedido["cliente_nome"]) ?></p>
        <p><strong>E-mail:</strong> <?= htmlspecialchars($pedido["cliente_email"]) ?></p>
        <p><strong>Data:</strong> <?= htmlspecialchars($pedido["data_pedido"]) ?></p>
        <p><strong>Total:</strong> R$ <?= number_format($pedido["total"], 2, ",", ".") ?></p>
    </div>

    <div class="card">
        <h2>Itens do Pedido</h2>

        <?php if (count($itens) > 0): ?>
            <div class="tabela-responsiva">
                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço Unitário</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item["produto_nome"]) ?></td>
                                <td>R$ <?= number_format($item["produto_preco"], 2, ",", ".") ?></td>
                                <td><?= htmlspecialchars($item["quantidade"]) ?></td>
                                <td>R$ <?= number_format($item["subtotal"], 2, ",", ".") ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Este pedido não possui itens cadastrados.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="linha-botoes">
            <a href="pedidos.php" class="btn-link voltar">Voltar para pedidos</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/partials/footer.php"; ?>