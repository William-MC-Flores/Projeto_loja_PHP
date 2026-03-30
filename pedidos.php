<?php

require_once __DIR__ . "/dao/ClienteDAO.php";
require_once __DIR__ . "/dao/ProdutoDAO.php";
require_once __DIR__ . "/dao/PedidoDAO.php";
require_once __DIR__ . "/models/Cliente.php";
require_once __DIR__ . "/models/Produto.php";
require_once __DIR__ . "/models/Pedido.php";
require_once __DIR__ . "/models/ItemPedido.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tituloPagina = "Pedidos | Sistema da Loja";

$clienteDAO = new ClienteDAO();
$produtoDAO = new ProdutoDAO();
$pedidoDAO = new PedidoDAO();

$clientes = $clienteDAO->listar();
$produtos = $produtoDAO->listar();
$pedidos = $pedidoDAO->listar();

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
                $_SESSION["mensagem_sucesso"] = "Pedido cadastrado com sucesso!";
                header("Location: pedidos.php");
                exit;
            } else {
                $erro = "Erro ao cadastrar pedido.";
            }
        }
    }
}

require_once __DIR__ . "/partials/header.php";
?>

<div class="container">
    <h1>Cadastro de Pedidos</h1>

    <?php if (!empty($_SESSION["mensagem_sucesso"])): ?>
        <p class="mensagem sucesso"><?= htmlspecialchars($_SESSION["mensagem_sucesso"]) ?></p>
        <?php unset($_SESSION["mensagem_sucesso"]); ?>
    <?php endif; ?>

    <?php if ($erro != ""): ?>
        <p class="mensagem erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <div class="card">
        <form method="POST" class="formulario" id="form-pedido">
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
                                    <input
                                        type="checkbox"
                                        name="produtos[]"
                                        value="<?= htmlspecialchars($produto["id"]) ?>"
                                        class="produto-checkbox"
                                        data-preco="<?= htmlspecialchars($produto["preco"]) ?>">
                                    <?= htmlspecialchars($produto["nome"]) ?> — R$ <?= number_format($produto["preco"], 2, ",", ".") ?>
                                </label>

                                <input
                                    type="number"
                                    name="quantidades[<?= htmlspecialchars($produto["id"]) ?>]"
                                    min="1"
                                    value="1"
                                    class="input-quantidade"
                                    data-produto-id="<?= htmlspecialchars($produto["id"]) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Cadastre produtos antes de criar um pedido.</p>
                <?php endif; ?>
            </div>

            <div class="card total-previo-box">
                <h2>Total Prévio</h2>
                <p id="total-previo">R$ 0,00</p>
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
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><?= htmlspecialchars($pedido["id"]) ?></td>
                                <td><?= htmlspecialchars($pedido["cliente_nome"]) ?></td>
                                <td>R$ <?= number_format($pedido["total"], 2, ",", ".") ?></td>
                                <td><?= htmlspecialchars($pedido["data_pedido"]) ?></td>
                                <td class="acoes">
                                    <a class="btn-link editar" href="visualizar_pedido.php?id=<?= urlencode($pedido["id"]) ?>">Ver Detalhes</a>
                                    <a class="btn-link excluir"
                                        href="excluir_pedido.php?id=<?= urlencode($pedido["id"]) ?>"
                                        onclick="return confirm('Tem certeza que deseja excluir este pedido?')">
                                        Excluir
                                    </a>
                                </td>
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

<script>
    const checkboxes = document.querySelectorAll(".produto-checkbox");
    const totalPrevio = document.getElementById("total-previo");

    function calcularTotalPrevio() {
        let total = 0;

        checkboxes.forEach((checkbox) => {
            const produtoId = checkbox.value;
            const preco = parseFloat(checkbox.dataset.preco);
            const inputQuantidade = document.querySelector('[data-produto-id="' + produtoId + '"]');
            const quantidade = parseInt(inputQuantidade.value) || 0;

            if (checkbox.checked && quantidade > 0) {
                total += preco * quantidade;
            }
        });

        totalPrevio.textContent = total.toLocaleString("pt-BR", {
            style: "currency",
            currency: "BRL"
        });
    }

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", calcularTotalPrevio);
    });

    document.querySelectorAll(".input-quantidade").forEach((input) => {
        input.addEventListener("input", calcularTotalPrevio);
    });

    calcularTotalPrevio();
</script>

<?php require_once __DIR__ . "/partials/footer.php"; ?>