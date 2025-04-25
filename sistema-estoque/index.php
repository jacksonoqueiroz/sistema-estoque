<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require 'conexao.php';

function checarEstoqueMinimo($pdo) {
    $alertas = [];

    $sql = "SELECT * FROM itens WHERE estoque_atual <= estoque_minimo";
    $stmt = $pdo->query($sql);
    while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alertas[] = "Item: {$item['nome']} com estoque crítico!";
    }

    $sql = "SELECT * FROM peca WHERE estoque_atual <= estoque_minimo";
    $stmt = $pdo->query($sql);
    while ($peca = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alertas[] = "Peça: {$peca['nome']} com estoque crítico!";
    }

    return $alertas;
}

$alertas = checarEstoqueMinimo($pdo);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Controle de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4">Dashboard de Estoque</h1>

    <?php
// Buscar alguns dados para os cards
$total_itens = $pdo->query("SELECT COUNT(*) FROM itens")->fetchColumn();
$total_pecas = $pdo->query("SELECT COUNT(*) FROM peca")->fetchColumn();
$pedidos_pendentes = $pdo->query("SELECT COUNT(*) FROM pedidos_compra WHERE status = 'pendente'")->fetchColumn();
?>
<div class="row mb-5">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3 shadow rounded-4">
            <div class="card-body">
                <h5 class="card-title">Itens Cadastrados</h5>
                <p class="card-text fs-2"><?= $total_itens ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3 shadow rounded-4">
            <div class="card-body">
                <h5 class="card-title">Peças Cadastradas</h5>
                <p class="card-text fs-2"><?= $total_pecas ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3 shadow rounded-4">
            <div class="card-body">
                <h5 class="card-title">Pedidos Pendentes</h5>
                <p class="card-text fs-2"><?= $pedidos_pendentes ?></p>
            </div>
        </div>
    </div>
</div>


    <?php if ($alertas): ?>
        <div class="alert alert-danger">
            <h4>⚠️ Alertas de Estoque:</h4>
            <ul>
                <?php foreach ($alertas as $alerta): ?>
                    <li><?= htmlspecialchars($alerta) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="alert alert-success">Tudo certo no estoque!</div>
    <?php endif; ?>

    <div class="mt-5">
        <a href="relatorios.php" class="btn btn-primary">📑 Relatórios</a>
        <a href="relatorio_pdf.php" class="btn btn-outline-success">Gerar Relatório PDF</a>

        <a href="movimentacoes.php" class="btn btn-warning">🔄 Movimentar Estoque</a>
        <a href="pedidos_compra.php" class="btn btn-danger">🛒 Pedidos de Compra</a>
        <a href="recebimento.php" class="btn btn-success">📥 Recebimento</a>
        <a href="logout.php" class="btn btn-outline-danger">Sair</a>

    </div>
    <div class="mt-5">
    <h3>📊 Visão Geral de Estoque</h3>
    <canvas id="estoqueChart" width="500" height="500"></canvas>
</div>

</div>
<?php
// Buscar quantidade de itens e peças para o gráfico
$total_itens = $pdo->query("SELECT SUM(estoque_atual) FROM itens")->fetchColumn();
$total_pecas = $pdo->query("SELECT SUM(estoque_atual) FROM peca")->fetchColumn();
?>
<script>
const ctx = document.getElementById('estoqueChart').getContext('2d');
const estoqueChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Itens', 'Peças'],
        datasets: [{
            label: 'Estoque',
            data: [<?= $total_itens ?: 0 ?>, <?= $total_pecas ?: 0 ?>],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});
</script>


</body>
</html>
