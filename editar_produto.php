<?php

require_once __DIR__ . "/dao/ProdutoDAO.php";
require_once __DIR__ . "/models/Produto.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tituloPagina = "Editar Produto";

$produtoDAO = new ProdutoDAO();
$erro = "";

if (!isset($_GET["id"]) && !isset($_POST["id"])) {
    die("ID do produto não informado.");
}

$id = isset($_GET["id"]) ? (int) $_GET["id"] : (int) $_POST["id"];
$produto = $produtoDAO->buscarPorId($id);

if (!$produto && !isset($_POST["atualizar"])) {
    die("Produto não encontrado.");
}

if (isset($_POST["atualizar"])) {
    $nome = trim($_POST["nome"]);
    $preco = str_replace(",", ".", trim($_POST["preco"]));

    if ($nome === "") {
        $erro = "O nome do produto não pode ficar vazio.";
    } elseif (!is_numeric($preco)) {
        $erro = "Informe um preço válido.";
    } elseif ($preco < 0) {
        $erro = "O preço não pode ser negativo.";
    } else {
        $produtoAtualizado = new Produto($id, $nome, $preco);

        if ($produtoDAO->atualizar($produtoAtualizado)) {
            $_SESSION["mensagem_sucesso"] = "Produto atualizado com sucesso!";
            header("Location: produtos.php");
            exit;
        } else {
            $erro = "Erro ao atualizar produto.";
        }
    }

    $produto = new Produto($id, $nome, $preco);
}

require_once __DIR__ . "/partials/header.php";
?>

<div class="container">
    <h1>Editar Produto</h1>

    <?php if ($erro != ""): ?>
        <p class="mensagem erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <div class="card">
        <form method="POST" class="formulario">
            <input type="hidden" name="id" value="<?= htmlspecialchars($produto->getId()) ?>">

            <div class="campo">
                <label for="nome">Nome do produto</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($produto->getNome()) ?>" required>
            </div>

            <div class="campo">
                <label for="preco">Preço</label>
                <input type="number" step="0.01" min="0" name="preco" id="preco" value="<?= htmlspecialchars($produto->getPreco()) ?>" required>
            </div>

            <div class="linha-botoes">
                <button type="submit" name="atualizar" class="btn">Atualizar</button>
                <a href="produtos.php" class="btn-link voltar">Voltar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . "/partials/footer.php"; ?>