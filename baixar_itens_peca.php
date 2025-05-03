<?php
require 'conexao.php';

$id_peca = $_GET['id_peca'] ?? null;
$id_solicitacao = $_GET['id_solicitacao'] ?? null;

if (!$id_peca || !$id_solicitacao) {
    echo "Parâmetros inválidos!";
    exit;
}

// Buscar itens da peça
$stmt = $pdo->prepare("SELECT i.id, i.codigo, i.nome, li.local
    FROM itens i
    LEFT JOIN local_itens li ON li.id_item = i.id
    WHERE i.id_peca = ?");
$stmt->execute([$id_peca]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar itens baixados
$stmt = $pdo->prepare("SELECT id_item FROM itens_baixados WHERE id_solicitacao = ?");
$stmt->execute([$id_solicitacao]);
$baixados = $stmt->fetchAll(PDO::FETCH_COLUMN);
$baixados_map = array_flip($baixados);

// Verifica se todos estão baixados
$todos_baixados = count($baixados) === count($itens);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Baixar Itens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h3>Baixar Itens da Peça</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Local</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($itens as $item): ?>
            <?php $ja_baixado = isset($baixados_map[$item['id']]); ?>
            <tr>
                <td><?= htmlspecialchars($item['codigo']) ?></td>
                <td><?= htmlspecialchars($item['nome']) ?></td>
                <td><?= htmlspecialchars($item['local']) ?></td>
                <td>
                    <?php if ($ja_baixado): ?>
                        <span class="badge bg-success">Baixado</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Pendente</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!$ja_baixado): ?>
                        <form method="post" action="baixar_item.php" style="display:inline;">
                            <input type="hidden" name="id_item" value="<?= $item['id'] ?>">
                            <input type="hidden" name="id_solicitacao" value="<?= $id_solicitacao ?>">
                            <input type="hidden" name="id_peca" value="<?= $id_peca ?>">
                            <button type="submit" class="btn btn-sm btn-primary">Dar Baixa</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-sm btn-secondary" disabled>Baixado</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($todos_baixados): ?>
        <form method="post" action="confirmar_baixa.php">
            <input type="hidden" name="id_solicitacao" value="<?= $id_solicitacao ?>">
            <button class="btn btn-success">✔ Confirmar Baixa Completa</button>
        </form>
    <?php else: ?>
        <div class="alert alert-info">Baixe todos os itens para liberar a confirmação.</div>
    <?php endif; ?>
</body>
</html>
