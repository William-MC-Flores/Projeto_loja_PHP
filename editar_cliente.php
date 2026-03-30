<?php

require_once __DIR__ . "/dao/ClienteDAO.php";
require_once __DIR__ . "/models/Cliente.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tituloPagina = "Editar Cliente";

$clienteDAO = new ClienteDAO();
$erro = "";

if (!isset($_GET["id"]) && !isset($_POST["id"])) {
    die("ID do cliente não informado.");
}

$id = isset($_GET["id"]) ? (int) $_GET["id"] : (int) $_POST["id"];

$cliente = $clienteDAO->buscarPorId($id);

if (!$cliente && !isset($_POST["atualizar"])) {
    die("Cliente não encontrado.");
}

if (isset($_POST["atualizar"])) {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);

    if ($nome === "") {
        $erro = "O nome não pode ficar vazio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Informe um e-mail válido.";
    } else {
        $clienteAtualizado = new Cliente($id, $nome, $email);

        if ($clienteDAO->atualizar($clienteAtualizado)) {
            $_SESSION["mensagem_sucesso"] = "Cliente atualizado com sucesso!";
            header("Location: clientes.php");
            exit;
        } else {
            $erro = "Erro ao atualizar cliente.";
        }
    }

    $cliente = new Cliente($id, $nome, $email);
}

require_once __DIR__ . "/partials/header.php";
?>

<div class="container">
    <h1>Editar Cliente</h1>

    <?php if ($erro != ""): ?>
        <p class="mensagem erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <div class="card">
        <form method="POST" class="formulario">
            <input type="hidden" name="id" value="<?= htmlspecialchars($cliente->getId()) ?>">

            <div class="campo">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($cliente->getNome()) ?>" required>
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($cliente->getEmail()) ?>" required>
            </div>

            <div class="linha-botoes">
                <button type="submit" name="atualizar" class="btn">Atualizar</button>
                <a href="clientes.php" class="btn-link voltar">Voltar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . "/partials/footer.php"; ?>