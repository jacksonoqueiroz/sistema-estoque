<?php
require 'conexao.php'; // conexão com PDO

// Buscar itens já alocados, agrupados por grupo
$stmt = $pdo->query("
    SELECT im.*, g.nome AS grupo_nome
    FROM itens_montagem im
    JOIN grupos g ON im.id_grupo = g.id
    WHERE im.localizacao IS NOT NULL
    ORDER BY im.id_grupo, im.localizacao
");
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar por grupo
$grupos = [];
foreach ($itens as $item) {
    $grupos[$item['grupo_nome']][] = $item;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Itens de Montagem Alocados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .grupo-titulo {
            background-color: #f8f9fa;
            padding: 10px;
            border-left: 5px solid #0d6efd;
            margin-top: 20px;
        }
        .card {
            border-left: 4px solid #198754;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .local {
            font-weight: bold;
            color: #198754;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4">📦 Itens de Montagem Alocados</h2>

    <?php if (empty($grupos)): ?>
        <div class="alert alert-warning">Nenhum item alocado até o momento.</div>
    <?php else: ?>
        <?php foreach ($grupos as $grupo => $itensDoGrupo): ?>
            <div class="grupo-titulo">
                <h4 class="mb-0">🔧 Grupo: <?= htmlspecialchars($grupo) ?></h4>
            </div>
            <div class="row">
                <?php foreach ($itensDoGrupo as $item): ?>
                    <div class="col-md-6">
                        <div class="card p-3 mb-3">
                            <h5><?= htmlspecialchars($item['nome']) ?></h5>
                            <p class="mb-1">📦 Quantidade: <?= $item['quantidade'] ?></p>
                            <p class="mb-0">📍 Local: <span class="local"><?= htmlspecialchars($item['localizacao']) ?></span></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
