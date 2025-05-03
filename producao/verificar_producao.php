<?php

require 'conexao.php';

// ID da peça que deseja produzir (simulado aqui como 1)
$id_peca = $_GET['id_peca'] ?? 1;

// Buscar nome da peça
$peca = $pdo->prepare("SELECT nome FROM peca WHERE id = ?");
$peca->execute([$id_peca]);
$nome_peca = $peca->fetchColumn();

// Buscar os itens necessários para essa peça
$stmt = $pdo->prepare("
    SELECT ip.id_item, i.nome, i.estoque_atual, ip.quantidade_necessaria
    FROM itens_peca ip
    JOIN itens i ON i.id = ip.id_item
    WHERE ip.id_peca = ?
");
$stmt->execute([$id_peca]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

$liberado = true;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verificar Produção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <h3 class="mb-4">Verificar Produção - <?= htmlspecialchars($nome_peca) ?></h3>

        <table class="table table-bordered shadow-sm bg-white">
            <thead class="table-light">
                <tr>
                    <th>Item</th>
                    <th>Em Estoque</th>
                    <th>Necessário</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($itens as $item): 
                $ok = $item['estoque'] >= $item['quantidade_necessaria'];
                if (!$ok) $liberado = false;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['nome']) ?></td>
                    <td><?= $item['estoque'] ?></td>
                    <td><?= $item['quantidade_necessaria'] ?></td>
                    <td>
                        <?php if ($ok): ?>
                            <span class="badge bg-success">OK</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Faltando</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($liberado): ?>
            <form action="produzir_peca.php" method="POST">
                <input type="hidden" name="id_peca" value="<?= $id_peca ?>">
                <button class="btn btn-success">Liberar Produção</button>
            </form>
        <?php else: ?>
            <div class="alert alert-warning mt-3">Há itens em falta. Não é possível produzir.</div>
        <?php endif; ?>
    </div>
</body>
</html>
