<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $tabela = $_POST['tabela'];
    $id = (int)$_POST['referencia_id'];
    $quantidade = (int)$_POST['quantidade'];
    $descricao = $_POST['descricao'];

    // require 'funcoes.php';

    function movimentarEstoque($pdo, $tipo, $tabela, $id, $quantidade, $descricao = '') {
    if ($tipo == 'saida') {
        $quantidade = -abs($quantidade);
    }
    
    $campo_estoque = 'estoque_atual';
    $sql = "UPDATE {$tabela} SET {$campo_estoque} = {$campo_estoque} + :quantidade WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':quantidade' => $quantidade,
        ':id' => $id
    ]);

    $sql = "INSERT INTO movimentacoes_estoque (tipo, tabela_referencia, referencia_id, quantidade, descricao)
            VALUES (:tipo, :tabela, :id, :quantidade, :descricao)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':tipo' => $tipo,
        ':tabela' => $tabela,
        ':id' => $id,
        ':quantidade' => abs($quantidade),
        ':descricao' => $descricao
    ]);
}




    function checarEstoqueMinimo($pdo) {
    $alertas = [];

    $sql = "SELECT * FROM itens WHERE estoque_atual <= estoque_minimo";
    $stmt = $pdo->query($sql);
    while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alertas[] = "Item: {$item['nome']} com estoque crítico!";
    }

    $sql = "SELECT * FROM pecas WHERE estoque_atual <= estoque_minimo";
    $stmt = $pdo->query($sql);
    while ($peca = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alertas[] = "Peça: {$peca['nome']} com estoque crítico!";
    }

    return $alertas;
}


    
    movimentarEstoque($pdo, $tipo, $tabela, $id, $quantidade, $descricao);

    header('Location: movimentacoes.php?sucesso=1');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Movimentar Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">🔄 Movimentar Estoque</h1>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Movimentação realizada com sucesso!</div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Tipo:</label>
            <select name="tipo" class="form-control">
                <option value="entrada">Entrada</option>
                <option value="saida">Saída</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Tabela:</label>
            <select name="tabela" class="form-control">
                <option value="itens">Item</option>
                <option value="pecas">Peça</option>
            </select>
        </div>

        <div class="mb-3">
            <label>ID Referência:</label>
            <input type="number" name="referencia_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Quantidade:</label>
            <input type="number" name="quantidade" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Descrição:</label>
            <input type="text" name="descricao" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
