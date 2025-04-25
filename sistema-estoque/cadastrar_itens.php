<?php
require 'conexao.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION['usuario_tipo'] != 'admin') {
    die('Acesso negado!');
}

// Busca materiais cadastrados
$materiais = $pdo->query("SELECT id, nome FROM material ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// Se enviou o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = trim($_POST['codigo']);
    $nome = trim($_POST['nome']);
    $quantidade = (int) $_POST['qtd_itens'];
    $estoque_minimo = (int) $_POST['estoque_minimo'];
    $selecionados = $_POST['selecionados'] ?? [];

    // Inserir no banco
    $stmt = $pdo->prepare("INSERT INTO itens (codigo, nome, id_material, qtd_itens, estoque_minimo) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$codigo, $nome, $selecionados, $qtd_itens, $estoque_minimo]);

    // Redireciona para lista de itens
    header('Location: itens.php?sucesso=1');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Novo Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">    
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    
</head>
<body>

<?php include 'include/sidebar.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2 class="mb-4">➕ Cadastrar Novo Item</h2>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" class="form-control" id="codigo" name="codigo" required>
            </div>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Item</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Materiais</label>
                    <select name="selecionados[]" class="form-select" required>
                        <option value="">Selecione...</option>
                            <?php foreach ($materiais as $material): ?>
                        <option value="<?= $material['id'] ?>">
                            <?= htmlspecialchars($material['nome']) ?>
                        </option>
                            <?php endforeach; ?>
                    </select>
            </div>
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade Inicial</label>
                <input type="number" class="form-control" id="qtd_itens" name="qtd_itens" required min="0">
            </div>
            <div class="mb-3">
                <label for="estoque_minimo" class="form-label">Estoque Mínimo</label>
                <input type="number" class="form-control" id="estoque_minimo" name="estoque_minimo" required min="0">
            </div>
            <button type="submit" class="btn btn-primary">Salvar Item</button>
            <a href="itens.php" class="btn btn-secondary">Cancelar</a>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
