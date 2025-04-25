<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require 'conexao.php';

$movimentacoes = $pdo->query("SELECT * FROM movimentacoes_estoque ORDER BY data_movimentacao DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatórios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">📑 Relatório de Movimentações</h1>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Tipo</th>
                <th>Referência</th>
                <th>Quantidade</th>
                <th>Descrição</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimentacoes as $mov): ?>
                <tr>
                    <td><?= ucfirst($mov['tipo']) ?></td>
                    <td><?= ucfirst($mov['tabela_referencia']) ?> #<?= $mov['referencia_id'] ?></td>
                    <td><?= $mov['quantidade'] ?></td>
                    <td><?= htmlspecialchars($mov['descricao']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($mov['data_movimentacao'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary mt-3">🏠 Voltar</a>
</div>
</body>
</html>
